<?php
include("db_connection.php");

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $id = intval($_POST['id']);
    $status = $_POST['status'];

    $stmt = $conn->prepare("UPDATE work_assigned SET work_status=? WHERE id=?");
    $stmt->bind_param("si", $status, $id);
    $success = $stmt->execute();

    echo json_encode(["success" => $success]);
}
?>
