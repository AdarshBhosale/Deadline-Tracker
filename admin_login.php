<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <title>Admin Login - Multi Login System</title>
  <!-- Font Awesome -->
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
      color: #fff;
    }

    .container {
      flex: 1;
      display: flex;
      justify-content: center;
      align-items: center;
      padding: 40px 20px;
    }

    .login-box {
      background: #fff;
      padding: 40px 30px;
      border-radius: 15px;
      box-shadow: 0 6px 20px rgba(0, 0, 0, 0.08);
      width: 100%;
      max-width: 400px;
      text-align: center;
    }

    .login-box h1 {
      margin-bottom: 25px;
      font-size: 26px;
      font-weight: 700;
      color: #1e293b;
    }

    .form-group {
      margin-bottom: 20px;
      position: relative;
    }

    .form-group input {
      width: 100%;
      padding: 14px 12px;
      border: 1px solid #d1d5db;
      border-radius: 10px;
      font-size: 15px;
      outline: none;
      transition: border 0.3s;
    }

    .form-group input:focus {
      border-color: #3b82f6;
    }

    /* Eye icon */
    .toggle-password {
      position: absolute;
      right: 12px;
      top: 50%;
      transform: translateY(-50%);
      cursor: pointer;
      font-size: 18px;
      color: #64748b;
    }

    .btn {
      width: 100%;
      padding: 14px;
      border-radius: 10px;
      color: #fff;
      background: linear-gradient(90deg, #06b6d4, #3b82f6);
      font-size: 16px;
      font-weight: 600;
      border: none;
      cursor: pointer;
      transition: 0.3s;
    }

    .btn:hover {
      background: linear-gradient(90deg, #3b82f6, #1d4ed8);
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
  </style>
</head>

<body>
  <!-- Navbar -->
  <div class="navbar">
    <a href="index.html">
      <img src="assets/logo.png" class="logo" alt="Logo" />
    </a>
    <h2>Multi Login System</h2>
  </div>

  <?php if (isset($_GET['message']) && $_GET['message'] == 'loggedout') { ?>
    <p style="color: green; text-align:center; margin-bottom:15px;">
      You have logged out successfully.
    </p>
  <?php } ?>

  <!-- Main -->
  <div class="container">
    <div class="login-box">
      <h1>Admin Login</h1>
      <form method="POST" action="admin_auth.php">
        <div class="form-group">
          <input type="text" name="username" placeholder="Admin Username" required />
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
  </script>
</body>

</html>