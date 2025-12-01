<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json");
date_default_timezone_set('Asia/Kolkata');

$from          = "admin@thinkinggrey.in";
$managerEmail  = $_POST['manager_email']  ?? "manager@thinkinggrey.com";
$managerName   = $_POST['manager_name']   ?? "Manager";
$employeeEmail = $_POST['employee_email'] ?? "employee@thinkinggrey.com";
$employeeName  = $_POST['employee_name']  ?? "Employee";
$work          = $_POST['work']           ?? "Work not provided";
$deadline      = $_POST['deadline']       ?? "Not specified";
$type          = $_POST['type']           ?? "assign"; // assign | reminder

if ($type === "assign") {
    $subject = "New Work Assigned - ($managerName), Thinking Grey";
    $title   = "🚀 New Work Assigned";
    $line    = "You have been assigned new work by your manager.";
} else {
    $subject = "⏰ Work Reminder - ($managerName), Thinking Grey";
    $title   = "⏰ Work Reminder";
    $line    = "This is a reminder for your pending work.";
}

// Build employee dashboard link
$dashboardUrl = "https://thinkinggrey.in/todo-list/employee_dashboard.php?employee=" . urlencode($employeeEmail);

$message = "
<html><body style='font-family:Arial,sans-serif;color:#333;background:#f9fafb;padding:20px;'>
  <table width='600' align='center' style='background:#fff;border-radius:8px;box-shadow:0 2px 8px rgba(0,0,0,.1);'>
    <tr><td style='background:#1d4ed8;color:#fff;padding:15px;font-size:20px;font-weight:bold;'>$title</td></tr>
    <tr><td style='padding:20px;font-size:15px;'>
      <p>Hi <b>$employeeName</b>,</p>
      <p>$line</p>
      <p><b>Work:</b> $work</p>
      <p><b>Deadline:</b> <span style='color:#e11d48;'>$deadline</span></p>
      
      <p style='margin:25px 0;text-align:center;'>
        <a href='$dashboardUrl' style='display:inline-block;padding:12px 20px;background:#1d4ed8;color:#fff;
           text-decoration:none;border-radius:6px;font-size:15px;font-weight:bold;'>Go to Dashboard</a>
      </p>
      
      <p style='margin-top:20px;font-size:13px;color:#555;'>Regards,<br><b>Thinking Grey System</b></p>
    </td></tr>
    <tr><td style='background:#f3f4f6;color:#666;text-align:center;padding:10px;font-size:12px;'>&copy; 2025 Thinking Grey. All rights reserved.</td></tr>
  </table>
</body></html>
";

$headers  = "From: $from\r\n";
$headers .= "Reply-To: $from\r\n";
//$headers .= "CC: $managerEmail\r\n";
$headers .= "MIME-Version: 1.0\r\n";
$headers .= "Content-type: text/html; charset=UTF-8\r\n";

$result = mail($employeeEmail, $subject, $message, $headers);
echo json_encode($result ? ["success"=>true] : ["success"=>false, "error"=>"Mail sending failed"]);
?>
