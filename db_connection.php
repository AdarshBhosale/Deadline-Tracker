<?php
$host = "localhost";
$user = "server2_todo_list";
$pass = "X(n?})GG6F*e0ueq"; // or your MySQL password
$db = "server2_todo_list";

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
  die("Database connection failed: " . $conn->connect_error);
}
?>