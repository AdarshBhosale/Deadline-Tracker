<?php
session_start();
include("db_connection.php");

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email_prefix = trim($_POST['email_prefix']);
    $password     = trim($_POST['password']);
    $email        = $email_prefix . "@thinkinggrey.com";

    $stmt = $conn->prepare("SELECT * FROM employee WHERE email=? LIMIT 1");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        if (password_verify($password, $row['password'])) {
            $_SESSION['employee'] = $row['name'];
            $_SESSION['employee_email'] = $row['email'];

            // ✅ Always redirect employees to employee_dashboard.php
            header("Location: employee_dashboard.php");
            exit;
        } else {
            echo "<script>alert('Invalid password!');window.location.href='employee_login.php';</script>";
        }
    } else {
        echo "<script>alert('Employee not found!');window.location.href='employee_login.php';</script>";
    }
}
?>
