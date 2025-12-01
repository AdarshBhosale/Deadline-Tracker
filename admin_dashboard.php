<?php
session_start();
if (!isset($_SESSION['admin'])) {
  header("Location: admin_login.php");
  exit;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <title>Admin Dashboard - Multi Login System</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <style>
    body {
      font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
      background: #f8fafc;
      margin: 0;
      padding: 0;
      color: #1e293b;
      min-height: 100vh;
      display: flex;
      flex-direction: column;
    }

    .navbar {
      background: #ffffff;
      padding: 15px 40px;
      display: flex;
      align-items: center;
      justify-content: space-between;
      box-shadow: 50px 0px 50px rgb(0 0 0 / 8%);
      position: sticky;
      top: 0;
      z-index: 1000;
    }

    .navbar .logo {
      width: 120px;
      height: auto;
    }

    .navbar h2 {
      font-size: 20px;
      font-weight: 600;
      color: #000;
    }

    .navbar .right {
      font-size: 14px;
      color: #374151;
    }

    .navbar .right a {
      color: #1d4ed8;
      text-decoration: none;
      margin-left: 12px;
      font-weight: 600;
    }

    .container {
      flex: 1;
      padding: 40px 20px;
      max-width: 1200px;
      margin: 0 auto;
    }

    .dashboard-header {
      text-align: center;
      margin-bottom: 40px;
    }

    .dashboard-header h1 {
      font-size: 28px;
      font-weight: 700;
    }

    .card-grid {
      display: grid;
      grid-template-columns: repeat(2, 1fr);
      gap: 25px;
    }

    @media (max-width: 768px) {
      .card-grid {
        grid-template-columns: 1fr;
      }
    }

    .card {
      background: #fff;
      padding: 30px;
      border-radius: 15px;
      box-shadow: 0 6px 20px rgba(0, 0, 0, 0.08);
      text-align: center;
      transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .card:hover {
      transform: translateY(-6px);
      box-shadow: 0 12px 25px rgba(0, 0, 0, 0.15);
    }

    .card i {
      font-size: 40px;
      color: #3b82f6;
      margin-bottom: 15px;
    }

    .card h2 {
      font-size: 20px;
      margin-bottom: 10px;
      color: #1e293b;
    }

    .card p {
      font-size: 14px;
      color: #475569;
      margin-bottom: 20px;
    }

    .btn {
      display: inline-block;
      padding: 12px 20px;
      border-radius: 10px;
      text-decoration: none;
      color: #fff;
      background: linear-gradient(90deg, #06b6d4, #3b82f6);
      font-size: 14px;
      font-weight: 600;
      transition: all 0.3s ease;
      cursor: pointer;
    }

    .btn:hover {
      background: linear-gradient(90deg, #3b82f6, #1d4ed8);
      box-shadow: 0 6px 18px rgba(59, 130, 246, 0.4);
    }

    footer {
      background: #ffffff;
      color: #000000;
      padding: 20px 15px;
      text-align: center;
      font-size: 14px;
      margin-top: auto;
      box-shadow: 50px 0px 50px rgb(0 0 0 / 8%);
    }

    footer a {
      color: #60a5fa;
      text-decoration: none;
      margin: 0 8px;
    }

    footer a:hover {
      color: #93c5fd;
    }

    /* Modal */
    .modal {
      display: none;
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: rgba(0, 0, 0, 0.6);
      justify-content: center;
      align-items: center;
      z-index: 2000;
    }

    .modal-content {
      background: #fff;
      padding: 25px;
      border-radius: 12px;
      width: 100%;
      max-width: 400px;
      box-shadow: 0 6px 20px rgba(0, 0, 0, 0.2);
      text-align: center;
      position: relative;
    }

    .modal-content h2 {
      margin-bottom: 20px;
      font-size: 22px;
    }

    .modal-content input {
      width: 100%;
      padding: 12px;
      margin-bottom: 15px;
      border: 1px solid #ccc;
      border-radius: 8px;
    }

    .close {
      position: absolute;
      top: 10px;
      right: 15px;
      font-size: 20px;
      cursor: pointer;
      color: #555;
    }

    .modal .btn {
      width: 100%;
    }
  </style>
</head>

<body>
  <!-- Navbar -->
  <div class="navbar">
    <a href="index.html">
      <img src="assets/logo.png" class="logo" alt="Logo" />
    </a>


    <h2>Admin Dashboard</h2>
    <div class="right">
      Welcome, <?php echo $_SESSION['admin']; ?> |
      <a href="logout.php">Logout</a>
    </div>

  </div>

  <!-- Main -->
  <div class="container">
    <div class="dashboard-header">
      <h1>Manage Your System</h1>
      <p>Quick access to manager and employee management.</p>
    </div>

    <div class="card-grid">
      <!-- Add Manager -->
      <div class="card">
        <i class="fa-solid fa-user-tie"></i>
        <h2>Add New Manager</h2>
        <p>Create and manage manager accounts to oversee projects.</p>
        <button class="btn" onclick="openModal('managerModal')">Add Manager</button>
      </div>

      <!-- Add Employee -->
      <div class="card">
        <i class="fa-solid fa-users"></i>
        <h2>Add New Employee</h2>
        <p>Register new employees and assign them to managers.</p>
        <button class="btn" onclick="openModal('employeeModal')">Add Employee</button>
      </div>
    </div>
  </div>

  <!-- Manager Modal -->
  <div id="managerModal" class="modal">
    <div class="modal-content">
      <span class="close" onclick="closeModal('managerModal')">&times;</span>
      <h2>Add New Manager</h2>
      <form method="POST" action="save_manager.php">
        <input type="text" name="name" placeholder="Manager Name" required>
        <div style="display:flex;">
          <input type="text" name="email_prefix" placeholder="Enter Email First Part" required style="flex:1;">
          <span style="padding:12px;">@thinkinggrey.com</span>
        </div>
        <button type="submit" class="btn">Save Manager</button>
      </form>
    </div>
  </div>

  <!-- Employee Modal -->
  <div id="employeeModal" class="modal">
    <div class="modal-content">
      <span class="close" onclick="closeModal('employeeModal')">&times;</span>
      <h2>Add New Employee</h2>
      <form method="POST" action="save_employee.php">
        <input type="text" name="name" placeholder="Employee Name" required>
        <div style="display:flex;">
          <input type="text" name="email_prefix" placeholder="Enter Email First Part" required style="flex:1;">
          <span style="padding:12px;">@thinkinggrey.com</span>
        </div>
        <button type="submit" class="btn">Save Employee</button>
      </form>
    </div>
  </div>

  <!-- Footer -->
  <footer>
    <p>&copy; 2025 Multi Login System. All Rights Reserved.</p>
    <p><a href="#">Privacy Policy</a> | <a href="#">Terms of Service</a></p>
  </footer>

  <script>
    function openModal(id) {
      document.getElementById(id).style.display = "flex";
    }

    function closeModal(id) {
      document.getElementById(id).style.display = "none";
    }
  </script>
</body>

</html>