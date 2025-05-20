<?php
session_start();
include 'database.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $email = $_POST["email"];
    $password = password_hash($_POST["password"], PASSWORD_DEFAULT);
    $name = $_POST["name"];
    $address = $_POST["address"];
    $contact_number = $_POST["contact_number"];
    $id_number = $_POST["id_number"];
    $profile = ""; // Placeholder for profile picture
    $created_at = date("Y-m-d H:i:s");

    $sql = "INSERT INTO admins (username, email, password, name, address, contact_number, id_number, profile, created_at) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssssssss", $username, $email, $password, $name, $address, $contact_number, $id_number, $profile, $created_at);

    if ($stmt->execute()) {
        header("Location: login.php");
        exit;
    } else {
        $error = "Error creating account: " . $conn->error;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Account</title>
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
        .register-container {
            background-color: #fff;
            padding: 25px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 300px;
            text-align: center;
        }
        .register-container h2 {
            margin-bottom: 20px;
        }
        .register-container label {
            display: block;
            margin-bottom: 5px;
            text-align: left;
        }
        .register-container input[type="text"],
        .register-container input[type="email"],
        .register-container input[type="password"] {
            width: 92%;
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #000;
            border-radius: 10px;
        }
        .register-container input[type="submit"] {
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
        .register-container input[type="submit"]:hover {
            background-color: #45a049;
        }
        .error {
            color: red;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="register-container">
        <h2>Create Account</h2>
        <?php if (isset($error)): ?>
            <p class="error"><?php echo $error; ?></p>
        <?php endif; ?>
        <form action="register.php" method="post">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" required>
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>
            <label for="name">Full Name:</label>
            <input type="text" id="name" name="name" required>
            <label for="address">Address:</label>
            <input type="text" id="address" name="address" required>
            <label for="contact_number">Contact Number:</label>
            <input type="text" id="contact_number" name="contact_number" required>
            <label for="id_number">ID Number:</label>
            <input type="text" id="id_number" name="id_number" required>
            <input type="submit" value="Register">
        </form>
    </div>
</body>
</html>
