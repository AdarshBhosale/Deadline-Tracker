<?php
// /public_html/todo-list/reminder.php
date_default_timezone_set('Asia/Kolkata');
require __DIR__ . '/db_connection.php';

$logFile = __DIR__ . '/reminder.log';
function rlog($msg) {
    global $logFile;
    @file_put_contents($logFile, '['.date('Y-m-d H:i:s')."] $msg\n", FILE_APPEND);
}

/* Make MySQL NOW() match IST so comparisons are correct */
$conn->query("SET time_zone = '+05:30'");

/* 
   Pick tasks where deadline has JUST passed or is now, still Pending.
   The 15-minute guard prevents very old items from being spammed if they were left Pending.
   (Adjust the window if you like, or remove it.)
*/
$sql = "
    SELECT id, manager, employee, work, deadline
    FROM work_assigned
    WHERE reminder_mail = 'Pending'
      AND deadline <= NOW()
      AND deadline >= DATE_SUB(NOW(), INTERVAL 15 MINUTE)
    ORDER BY deadline ASC
";
$res = $conn->query($sql);
if (!$res) { rlog('DB ERROR selecting due tasks: ' . $conn->error); exit; }

while ($row = $res->fetch_assoc()) {
    $id       = (int)$row['id'];
    $manager  = $row['manager'];   // manager email
    $employee = $row['employee'];  // employee email
    $work     = $row['work'];
    $deadline = $row['deadline'];

    /* Atomic claim so only one process sends */
    $lock = $conn->prepare("
        UPDATE work_assigned
        SET reminder_mail='Processing'
        WHERE id=? AND reminder_mail='Pending'
    ");
    $lock->bind_param("i", $id);
    $lock->execute();
    if ($conn->affected_rows !== 1) continue;

    // Optional: resolve names
    $managerName  = $manager;
    $employeeName = $employee;
    if ($q = $conn->query("SELECT name FROM managers WHERE email='".$conn->real_escape_string($manager)."' LIMIT 1")) {
        if ($m = $q->fetch_assoc()) $managerName = $m['name'];
    }
    if ($q = $conn->query("SELECT name FROM employee WHERE email='".$conn->real_escape_string($employee)."' LIMIT 1")) {
        if ($e = $q->fetch_assoc()) $employeeName = $e['name'];
    }

    // Send mail
    $post = [
        'manager_email'  => $manager,
        'manager_name'   => $managerName,
        'employee_email' => $employee,
        'employee_name'  => $employeeName,
        'work'           => $work,
        'deadline'       => $deadline,
        'type'           => 'reminder'
    ];

    $ch = curl_init("https://thinkinggrey.in/todo-list/mail.php");
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST           => true,
        CURLOPT_POSTFIELDS     => $post,
        CURLOPT_TIMEOUT        => 30,
        CURLOPT_SSL_VERIFYPEER => true,
        CURLOPT_SSL_VERIFYHOST => 2
    ]);
    $resp     = curl_exec($ch);
    $curlErr  = curl_error($ch);
    $httpCode = (int)curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    $ok = false;
    if ($resp !== false && $httpCode >= 200 && $httpCode < 300) {
        $json = @json_decode($resp, true);
        $ok = is_array($json) ? !empty($json['success']) : true;
    }

    if ($ok) {
        $upd = $conn->prepare("UPDATE work_assigned SET reminder_mail='Sent', reminder_sent_at=NOW() WHERE id=?");
        $upd->bind_param("i", $id);
        $upd->execute();
        rlog("Reminder SENT id=$id to $employee (deadline=$deadline)");
    } else {
        $upd = $conn->prepare("UPDATE work_assigned SET reminder_mail='Pending' WHERE id=?");
        $upd->bind_param("i", $id);
        $upd->execute();
        rlog("Reminder FAILED id=$id HTTP=$httpCode CURL='$curlErr' RESP='$resp'");
    }
}
rlog('Run complete.');
