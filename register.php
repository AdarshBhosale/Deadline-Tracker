<?php
include("includes/db.php");
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $name = $_POST["name"];
  $email = $_POST["email"];
  $password = md5($_POST["password"]);

  $sql = "INSERT INTO users (name, email, password) VALUES ('$name', '$email', '$password')";
  if ($conn->query($sql)) {
    $_SESSION["user"] = $email;
    header("Location: dashboard.php?role=user");
  } else {
    $error = "User already exists!";
  }
}
?>
<!DOCTYPE html>
<html>

<head>
  <title>User Register</title>
  <link rel="stylesheet" href="style.css">
</head>

<body>
  <div class="form-container">
    <h2>Create Account</h2>
    <form method="POST">
      <input type="text" name="name" placeholder="Full Name" required>
      <input type="email" name="email" placeholder="Email" required>
      <input type="password" name="password" placeholder="Password" required>
      <button type="submit">Register</button>
      <?php if (!empty($error))
        echo "<p class='error'>$error</p>"; ?>
    </form>
  </div>
</body>

</html>