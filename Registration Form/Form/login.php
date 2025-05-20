<?php
session_start();
include 'database.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];

    $sql = "SELECT * FROM admins WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['password'])) {
            $_SESSION["loggedin"] = true;
            $_SESSION["role"] = "admin";
            $_SESSION["username"] = $user['username'];
            header("Location: admin.php");
            exit;
        } else {
            $error = "Invalid password.";
        }
    } else {
        $error = "Username does not exist.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Glassmorphism Login Form</title>
  <link rel="stylesheet" href="style.css">
  <style>
    @import url("https://fonts.googleapis.com/css2?family=Open+Sans:wght@200;300;400;500;600;700&display=swap");
    * { margin: 0; padding: 0; box-sizing: border-box; font-family: "Open Sans", sans-serif; }
    body { display: flex; align-items: center; justify-content: center; min-height: 100vh; width: 100%; padding: 0 10px; }
    body::before { content: ""; position: absolute; width: 100%; height: 100%; background: url("dolebg.jpg"), #000; background-position: center; background-size: cover; }
    .wrapper { width: 400px; border-radius: 8px; padding: 30px; text-align: center; border: 1px solid rgba(255,255,255,0.5); backdrop-filter: blur(8px); -webkit-backdrop-filter: blur(8px); }
    form { display: flex; flex-direction: column; }
    h2 { font-size: 2rem; margin-bottom: 20px; color: #fff; }
    .input-field { position: relative; border-bottom: 2px solid #ccc; margin: 15px 0; }
    .input-field label { position: absolute; top: 50%; left: 0; transform: translateY(-50%); color: #fff; font-size: 16px; pointer-events: none; transition: 0.15s ease; }
    .input-field input { width: 100%; height: 40px; background: transparent; border: none; outline: none; font-size: 16px; color: #fff; }
    .input-field input:focus~label, .input-field input:valid~label { font-size: 0.8rem; top: 10px; transform: translateY(-120%); }
    .forget { display: flex; align-items: center; justify-content: space-between; margin: 25px 0 35px 0; color: #fff; }
    #remember { accent-color: #fff; }
    .forget label { display: flex; align-items: center; }
    .forget label p { margin-left: 8px; }
    .wrapper a { color: #efefef; text-decoration: none; }
    .wrapper a:hover { text-decoration: underline; }
    button { background: #fff; color: #000; font-weight: 600; border: none; padding: 12px 20px; cursor: pointer; border-radius: 3px; font-size: 16px; border: 2px solid transparent; transition: 0.3s ease; }
    button:hover { color: #fff; border-color: #fff; background: rgba(255,255,255,0.15); }
    .register { text-align: center; margin-top: 30px; color: #fff; }
    .error { color: #ffb3b3; margin-bottom: 10px; }
  </style>
</head>
<body>
  <div class="wrapper">
    <form action="login.php" method="post" autocomplete="off">
      <h2>Login</h2>
      <?php if (isset($error)): ?>
        <div class="error"><?php echo $error; ?></div>
      <?php endif; ?>
      <div class="input-field">
        <input type="text" name="username" id="username" required>
        <label for="username">Enter your email</label>
      </div>
      <div class="input-field">
        <input type="password" name="password" id="password" required>
        <label for="password">Enter your password</label>
      </div>
      <div class="forget">
        <label for="remember">
          <input type="checkbox" id="remember">
          <p>Remember me</p>
        </label>
        <a href="#">Forgot password?</a>
      </div>
      <button type="submit">Log In</button>
      <div class="register">
        <p>Don't have an account? <a href="register.php">Register</a></p>
      </div>
    </form>
  </div>
</body>
</html>
