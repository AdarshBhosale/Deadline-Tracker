<?php
session_start();
include("db_connection.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $username = trim($_POST['username']);
  $password = trim($_POST['password']);

  // Query admin table
  $stmt = $conn->prepare("SELECT * FROM admin WHERE username = ?");
  $stmt->bind_param("s", $username);
  $stmt->execute();
  $result = $stmt->get_result();

  if ($result->num_rows === 1) {
    $row = $result->fetch_assoc();

    // If passwords are hashed (MD5 in your screenshot)
    if (md5($password) === $row['password']) {
      $_SESSION['admin'] = $row['username'];
      header("Location: admin_dashboard.php");
      exit;
    } else {
      echo "<script>alert('Invalid password!'); window.location='admin_login.php';</script>";
    }
  } else {
    echo "<script>alert('Admin not found!'); window.location='admin_login.php';</script>";
  }
}
?>