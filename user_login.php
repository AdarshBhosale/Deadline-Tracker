<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Employee Login - To-do List</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <style>
    body {
      font-family: "Segoe UI", Tahoma, sans-serif;
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
    .navbar .logo { width: 120px; height: auto; }
    .container {
      flex: 1; display: flex; justify-content: center; align-items: center; padding: 40px 20px;
    }
    .login-box {
      background: #fff; padding: 40px 30px; border-radius: 15px;
      box-shadow: 0 6px 20px rgba(0, 0, 0, .08);
      width: 100%; max-width: 400px; text-align: center;
    }
    .login-box h1 { margin-bottom: 25px; font-size: 26px; font-weight: 700; }
    .form-group { margin-bottom: 20px; position: relative; }
    .form-group input {
      width: 100%; padding: 14px 12px; border: 1px solid #d1d5db;
      border-radius: 10px; font-size: 15px;
    }
    .form-group input:focus { border-color: #3b82f6; outline: none; }
    .toggle-password {
      position: absolute; right: 12px; top: 50%; transform: translateY(-50%);
      cursor: pointer; font-size: 18px; color: #64748b;
    }
    .btn {
      width: 100%; padding: 14px; border-radius: 10px; color: #fff;
      background: linear-gradient(90deg, #06b6d4, #3b82f6);
      font-size: 16px; font-weight: 600; border: none; cursor: pointer;
    }
    .btn:hover { background: linear-gradient(90deg, #3b82f6, #1d4ed8); }
    .create-link { margin-top: 15px; display: block; font-size: 14px; color: #1d4ed8; cursor: pointer; text-decoration: none; }
    footer {
      background: #fff; color: #000; padding: 20px; text-align: center;
      font-size: 14px; margin-top: auto; box-shadow: 0 -2px 10px rgba(0, 0, 0, .08);
    }
    .modal {
      display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%;
      background: rgba(0, 0, 0, .6); justify-content: center; align-items: center; z-index: 2000;
    }
    .modal-content {
      background: #fff; padding: 25px; border-radius: 12px;
      width: 100%; max-width: 400px; text-align: center; position: relative;
    }
    .modal-content h2 { margin-bottom: 20px; font-size: 22px; }
    .modal-content input {
      width: 100%; padding: 12px; margin-bottom: 15px;
      border: 1px solid #ccc; border-radius: 8px;
    }
    .close { position: absolute; top: 10px; right: 15px; font-size: 20px; cursor: pointer; color: #555; }
    .modal .btn { width: 100%; }
  </style>
</head>
<body>
  <!-- Navbar -->
  <div class="navbar">
    <img src="assets/logo.png" class="logo" alt="Logo" />
    <h2>Multi Login System</h2>
  </div>

  <!-- Main -->
  <div class="container">
    <div class="login-box">
      <h1>Employee Login</h1>
    <form method="POST" action="employee_auth.php">
  <div class="form-group" style="display:flex;">
    <input type="text" name="email_prefix" placeholder="Enter Email First Part" required style="flex:1;">
    <span style="padding:14px;">@thinkinggrey.com</span>
  </div>
  <div class="form-group">
    <input type="password" name="password" id="password" placeholder="Password" required />
    <span class="toggle-password" onclick="togglePassword()">
      <i id="eyeOpen" class="fa-solid fa-eye-slash"></i>
      <i id="eyeClosed" class="fa-solid fa-eye" style="display:none;"></i>
    </span>
  </div>
  <button type="submit" class="btn">Login</button>
</form>

      <a class="create-link" onclick="openModal()">Create Account!</a>
    </div>
  </div>

  <!-- Create Account Modal -->
  <div id="createModal" class="modal">
    <div class="modal-content">
      <span class="close" onclick="closeModal()">&times;</span>
      <h2>Create Employee Account</h2>
      <form method="POST" action="save_employee1.php">
        <div style="display:flex; margin-bottom:10px;">
          <input type="text" name="name" placeholder="Employee Name" required style="flex:1; margin-right:5px;">
        </div>
        <div style="display:flex; margin-bottom:10px;">
          <input type="text" name="email_prefix" placeholder="Enter Email First Part" required style="flex:1;">
          <span style="padding:12px;">@thinkinggrey.com</span>
        </div>
        <input type="password" name="password" placeholder="Password" required>
        <button type="submit" class="btn">Save Account</button>
      </form>
    </div>
  </div>

  <!-- Footer -->
  <footer>
    <p>&copy; 2025 Multi Login System. All Rights Reserved.</p>
    <p><a href="#">Privacy Policy</a> | <a href="#">Terms of Service</a></p>
  </footer>

  <script>
    function togglePassword() {
      const pwd = document.getElementById("password");
      const eyeOpen = document.getElementById("eyeOpen");
      const eyeClosed = document.getElementById("eyeClosed");
      if (pwd.type === "password") {
        pwd.type = "text";
        eyeOpen.style.display = "none";
        eyeClosed.style.display = "inline";
      } else {
        pwd.type = "password";
        eyeOpen.style.display = "inline";
        eyeClosed.style.display = "none";
      }
    }
    function openModal() { document.getElementById("createModal").style.display = "flex"; }
    function closeModal() { document.getElementById("createModal").style.display = "none"; }
  </script>
</body>
</html>
