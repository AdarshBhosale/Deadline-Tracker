<?php
session_start();
if (!isset($_SESSION["admin"]) && !isset($_SESSION["manager"]) && !isset($_SESSION["user"])) {
  header("Location: index.html");
  exit;
}
$role = $_GET["role"];
?>
<!DOCTYPE html>
<html>

<head>
  <title>Dashboard</title>
  <link rel="stylesheet" href="style.css">
</head>

<body>
  <div class="dashboard">
    <h1>Welcome to <?php echo ucfirst($role); ?> Dashboard</h1>
    <a href="index.html" class="btn">Logout</a>
  </div>
</body>

</html>