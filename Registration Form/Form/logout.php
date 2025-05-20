<?php
session_start();
$_SESSION = array();
session_destroy();
header("Location: login.php");
exit;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Logout</title>
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
        .logout-container {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            text-align: center;
        }
        .logout-container h2 {
            margin-bottom: 20px;
        }
        .logout-container form {
            display: flex;
            justify-content: center;
            gap: 10px;
        }
        .logout-container button {
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .logout-container .confirm {
            background-color: #4CAF50;
            color: white;
        }
        .logout-container .cancel {
            background-color: #f44336;
            color: white;
        }
    </style>
</head>
<body>
    <div class="logout-container">
        <h2>You have been logged out.</h2>
        <a href="login.php" class="confirm">Login Again</a>
    </div>
</body>
</html>