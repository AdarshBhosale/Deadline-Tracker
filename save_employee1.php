<?php
// save_employee.php
session_start();
include("db_connection.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $name = trim($_POST['name']);
  $email_prefix = trim($_POST['email_prefix']);
  $password = trim($_POST['password']);

  if (empty($name) || empty($email_prefix) || empty($password)) {
    die("All fields are required!");
  }

  // Construct email
  $email = $email_prefix . "@thinkinggrey.com";

  // Hash password
  $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

  // Check if employee already exists
  $check = $conn->prepare("SELECT id FROM employee WHERE name = ? AND email = ?");
  $check->bind_param("ss", $name, $email);
  $check->execute();
  $check->store_result();

  if ($check->num_rows > 0) {
    // Update password if record exists
    $update = $conn->prepare("UPDATE employee SET password = ? WHERE name = ? AND email = ?");
    $update->bind_param("sss", $hashedPassword, $name, $email);
    if ($update->execute()) {
      echo "<script>alert('Password updated successfully!');window.location='user_login.php';</script>";
    } else {
      echo "<script>alert('Error: Could not update password.');window.location='user_login.php';</script>";
    }
    $update->close();
  } else {
    // Insert new record
    $stmt = $conn->prepare("INSERT INTO employee (name, email, password) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $name, $email, $hashedPassword);
    if ($stmt->execute()) {
      echo "<script>alert('Employee account created successfully!');window.location='user_login.php';</script>";
    } else {
      echo "<script>alert('Error: Could not create account.');window.location='user_login.php';</script>";
    }
    $stmt->close();
  }

  $check->close();
  $conn->close();
}
?>