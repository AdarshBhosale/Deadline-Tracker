<?php
include("db_connection.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $name = trim($_POST['name']);
  $email_prefix = trim($_POST['email_prefix']);
  $email = $email_prefix . "@thinkinggrey.com";

  if (!empty($name) && !empty($email_prefix)) {
    // Insert name + email, leave password empty
    $stmt = $conn->prepare("INSERT INTO managers (name, email, password) VALUES (?, ?, '')");
    $stmt->bind_param("ss", $name, $email);

    if ($stmt->execute()) {
      echo "<script>alert('Manager added successfully!'); window.location.href='admin_dashboard.php';</script>";
    } else {
      echo "<script>alert('Error: Could not save manager'); window.location.href='admin_dashboard.php';</script>";
    }
    $stmt->close();
  } else {
    echo "<script>alert('All fields are required'); window.location.href='admin_dashboard.php';</script>";
  }
}
$conn->close();
?>