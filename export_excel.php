<?php
session_start();
if (!isset($_SESSION['manager'])) {
  header("Location: manager_login.php");
  exit;
}

include("db_connection.php");

if (!isset($_GET['employee']) || empty($_GET['employee'])) {
  die("Employee not specified.");
}

$employee = $_GET['employee'];

// Fetch work assigned to this employee
$stmt = $conn->prepare("SELECT id, manager, employee, work, deadline, created_date FROM work_assigned WHERE employee = ? ORDER BY id DESC");
$stmt->bind_param("s", $employee);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
  die("No data found for this employee.");
}

// Set headers to force download as Excel
header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=\"{$employee}_assigned_work.xls\"");
header("Pragma: no-cache");
header("Expires: 0");

// Output Excel table
echo "<table border='1'>";
echo "<thead>
        <tr>
          <th>ID</th>
          <th>Manager</th>
          <th>Employee</th>
          <th>Work</th>
          <th>Deadline</th>
          <th>Assigned On</th>
        </tr>
      </thead>";
echo "<tbody>";
while ($row = $result->fetch_assoc()) {
  echo "<tr>
            <td>{$row['id']}</td>
            <td>{$row['manager']}</td>
            <td>{$row['employee']}</td>
            <td>{$row['work']}</td>
            <td>{$row['deadline']}</td>
            <td>{$row['created_date']}</td>
          </tr>";
}
echo "</tbody></table>";

$stmt->close();
$conn->close();
?>