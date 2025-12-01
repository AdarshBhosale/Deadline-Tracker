<?php
include("db_connection.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $email_prefix = trim($_POST['email_prefix']);
  $email = $email_prefix . "@thinkinggrey.com";
  $password = md5(trim($_POST['password']));

  // Check if already exists
  $check = $conn->prepare("SELECT * FROM managers WHERE email=? LIMIT 1");
  $check->bind_param("s", $email);
  $check->execute();
  $res = $check->get_result();

  if ($res->num_rows > 0) {
    // Update password if exists
    $stmt = $conn->prepare("UPDATE managers SET password=? WHERE email=?");
    $stmt->bind_param("ss", $password, $email);
    $stmt->execute();
    echo "<script>alert('Account already exists. Password updated!'); window.location.href='manager_login.php';</script>";
  } else {
    // Insert new manager
    $stmt = $conn->prepare("INSERT INTO managers (name, email, password) VALUES (?, ?, ?)");
    $name = ucfirst($email_prefix);
    $stmt->bind_param("sss", $name, $email, $password);
    $stmt->execute();
    echo "<script>alert('Account created successfully!'); window.location.href='manager_login.php';</script>";
  }
}
?>