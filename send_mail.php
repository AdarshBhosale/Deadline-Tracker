<?php
// send_mail.php
date_default_timezone_set('Asia/Kolkata'); // ✅ Set timezone to IST

include("db_connection.php"); // ✅ DB connection

$to = "adarsh@thinkinggrey.com"; // recipient email
$subject = "📌 Work Status Reminder";

// Get current time (Hour:Minute)
$currentTime = date("H:i");

// Debugging (Optional – remove later)
echo "Current Time: " . $currentTime . "<br>";

// Only send at 13:35
if ($currentTime === "16:21") {

    // ✅ Fetch last assigned work per employee
    $query = "
        SELECT wa.id, e.name AS employee_name, m.name AS manager_name, 
               wa.work, wa.work_status, wa.created_date, wa.deadline
        FROM work_assigned wa
        JOIN employee e ON wa.employee_id = e.id
        JOIN managers m ON wa.manager_id = m.id
        WHERE wa.id IN (
            SELECT MAX(id) 
            FROM work_assigned 
            GROUP BY employee_id
        )
        ORDER BY wa.created_date DESC
    ";

    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        $message = "
        <div style='font-family: Arial, sans-serif; font-size: 13px; color: #444;'>
            <h2 style='color:#2c3e50; text-align:center; margin-bottom:15px;'>
                📌 Work Status Reminder
            </h2>
            <table border='0' cellpadding='8' cellspacing='0' 
                   style='border-collapse:collapse; width:100%; font-size:12px;'>
                <thead>
                    <tr style='background:#3498db; color:#fff; text-align:center;'>
                        <th style='padding:6px;'>Employee</th>
                        <th style='padding:6px;'>Manager</th>
                        <th style='padding:6px;'>Work</th>
                        <th style='padding:6px;'>Status</th>
                        <th style='padding:6px;'>Deadline</th>
                        <th style='padding:6px;'>Assigned On</th>
                    </tr>
                </thead>
                <tbody>";
        
        while ($row = $result->fetch_assoc()) {
            $statusColor = ($row['work_status'] === "Completed") ? "#27ae60" : "#e67e22";
            
            $message .= "<tr style='background:#f9f9f9; text-align:center;'>
                            <td style='padding:6px; border:1px solid #ddd;'>{$row['employee_name']}</td>
                            <td style='padding:6px; border:1px solid #ddd;'>{$row['manager_name']}</td>
                            <td style='padding:6px; border:1px solid #ddd;'>{$row['work']}</td>
                            <td style='padding:6px; border:1px solid #ddd; color:{$statusColor}; font-weight:bold;'>
                                {$row['work_status']}
                            </td>
                            <td style='padding:6px; border:1px solid #ddd;'>{$row['deadline']}</td>
                            <td style='padding:6px; border:1px solid #ddd;'>{$row['created_date']}</td>
                         </tr>";
        }
        $message .= "</tbody></table>
            <p style='font-size:11px; color:#999; text-align:center; margin-top:15px;'>
                This is an automated reminder. Please complete your pending tasks on time. ⏳
            </p>
        </div>";
    } else {
        $message = "<p>No work assigned yet.</p>";
    }

    // ✅ Email headers for HTML content
    $headers = "From: admin@thinkinggrey.in\r\n";
    $headers .= "MIME-Version: 1.0\r\n";
    $headers .= "Content-Type: text/html; charset=UTF-8\r\n";

    if (mail($to, $subject, $message, $headers)) {
        echo "✅ Stylish Email sent at 1:35 PM!";
    } else {
        echo "❌ Failed to send email.";
    }

} else {
    echo "⏳ Not yet 1:35 PM.";
}
?>
