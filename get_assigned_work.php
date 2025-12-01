<?php
// get_assigned_work.php
session_start();
header('Content-Type: application/json');
include("db_connection.php");

if (!isset($_GET['employee'])) {
  echo json_encode(["status" => "error", "message" => "Employee not provided"]);
  exit;
}

$employee = $_GET['employee'];

// Fetch assigned work including status
$stmt = $conn->prepare("SELECT id, manager, work, work_status, deadline, created_date 
                        FROM work_assigned 
                        WHERE employee = ? 
                        ORDER BY created_date DESC");
$stmt->bind_param("s", $employee);
$stmt->execute();
$result = $stmt->get_result();

$data = [];
while ($row = $result->fetch_assoc()) {
  $data[] = $row;
}

echo json_encode(["status" => "success", "data" => $data]);
