<?php
session_start();
include("db_connection.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $email_prefix = trim($_POST['email_prefix']);
  $email = $email_prefix . "@thinkinggrey.com";
  $password = md5(trim($_POST['password']));

  $stmt = $conn->prepare("SELECT * FROM managers WHERE email=? AND password=? LIMIT 1");
  $stmt->bind_param("ss", $email, $password);
  $stmt->execute();
  $result = $stmt->get_result();

  if ($result->num_rows === 1) {
    $row = $result->fetch_assoc();
    $_SESSION['manager'] = $row['name'];
    $_SESSION['manager_email'] = $row['email'];
    header("Location: manager_dashboard.php?login=1");  // send flag
    exit;
  } else {
    echo "<script>alert('Invalid login!'); window.location.href='manager_login.php';</script>";
  }
}
?>