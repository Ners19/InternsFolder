<?php
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register Admin</title>
    <link rel="icon" href="dole.png" type="image/png">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .login-container {
            background-color: #fff;
            padding: 25px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 300px;
            text-align: center;
            position: relative;
        }
        .login-container h3 {
            margin-top: 45px;
            margin-bottom: 20px;
        }
        .login-container label {
            display: block;
            margin-bottom: 11px;
            text-align: left;
            margin-left: 4px;
        }
        .login-container input[type="text"],
        .login-container input[type="password"] {
            width: 92%;
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #000;
            border-radius: 10px;
        }
        .login-container input[type="submit"] {
            width: 80%;
            padding: 10px;
            margin-top: 20px;
            background-color: #4CAF50;
            border: 2px solid #000;
            border-radius: 10px;
            color: white;
            font-size: 16px;
            cursor: pointer;
        }
        .login-container input[type="submit"]:hover {
            background-color: #45a049;
        }
        .error {
            color: red;
            text-align: center;
        }
        .logo {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            position: absolute;
            top: -70px;
            left: 50%;
            transform: translateX(-50%);
            background-color: #fff;
            border: 1px solid #000;
        }
        .forgot-password {
            display: block;
            margin-top: 10px;
            text-align: right;
            margin-right: 4px;
            font-size: 14px;
        }
        .forgot-password a {
            color: #4CAF50;
            text-decoration: none;
        }
        .forgot-password a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <img src="dole.png" alt="Logo" class="logo">
        <h2>Create an Admin Account</h2>
        <?php if (isset($error)): ?>
            <p class="error"><?php echo $error; ?></p>
        <?php endif; ?>
        <form action="register_admin.php" method="post" enctype="multipart/form-data">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" required>
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>
            <label for="name">Name:</label>
            <input type="text" id="name" name="name" required>
            <label for="address">Address:</label>
            <input type="text" id="address" name="address" required>
            <label for="contact_number">Contact Number:</label>
            <input type="text" id="contact_number" name="contact_number" required>
            <label for="id_number">ID Number:</label>
            <input type="text" id="id_number" name="id_number" required>
            <label for="profile">Profile Picture:</label>
            <input type="file" id="profile" name="profile" required>
            <input type="submit" value="Register">
        </form>
    </div>
</body>
</html>
