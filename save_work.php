<?php
session_start();
date_default_timezone_set('Asia/Kolkata');
include("db_connection.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $managerEmail   = $_SESSION['manager_email'] ?? "manager@thinkinggrey.com";
    $managerName    = $_SESSION['manager'] ?? "Manager";
    $employee_id    = $_POST['employee_id'] ?? '';
    $employee_name  = $_POST['employee_name'] ?? '';
    $employee_email = $_POST['employee_email'] ?? '';
    $work           = $_POST['work'] ?? '';
    $deadline       = $_POST['deadline'] ?? '';

    // ✅ Get Manager ID from managers table
    $stmt1 = $conn->prepare("SELECT id FROM managers WHERE email=? LIMIT 1");
    $stmt1->bind_param("s", $managerEmail);
    $stmt1->execute();
    $stmt1->bind_result($manager_id);
    $stmt1->fetch();
    $stmt1->close();

    // ✅ Get Employee ID from employee table
    $stmt2 = $conn->prepare("SELECT id FROM employee WHERE email=? LIMIT 1");
    $stmt2->bind_param("s", $employee_email);
    $stmt2->execute();
    $stmt2->bind_result($emp_id);
    $stmt2->fetch();
    $stmt2->close();

    if ($manager_id && $emp_id) {
        // ✅ Insert into work_assigned with IDs
        $stmt = $conn->prepare("INSERT INTO work_assigned
            (manager_id, employee_id, work_status, created_date, deadline, work)
            VALUES (?,?,?,?,?,?)");
        $status   = "Pending";
        $created  = date('Y-m-d H:i:s');
        $stmt->bind_param("iissss", $manager_id, $emp_id, $status, $created, $deadline, $work);

        if ($stmt->execute()) {
            // ✅ Send initial mail
            $postData = [
                'manager_email'  => $managerEmail,
                'manager_name'   => $managerName,
                'employee_email' => $employee_email,
                'employee_name'  => $employee_name,
                'work'           => $work,
                'deadline'       => $deadline,
                'type'           => 'assign'
            ];
            $ch = curl_init("https://thinkinggrey.in/todo-list/mail.php");
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
            curl_exec($ch);
            curl_close($ch);

            echo "<script>alert('Work Assigned & Mail Sent Successfully!'); window.location.href='manager_dashboard.php';</script>";
        } else {
            echo "<script>alert('Error Assigning Work!'); window.location.href='manager_dashboard.php';</script>";
        }
    } else {
        echo "<script>alert('Invalid Manager or Employee Email!'); window.location.href='manager_dashboard.php';</script>";
    }
}
?>
