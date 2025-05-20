<?php
session_start();

if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true || $_SESSION["role"] !== "admin") {
    header("Location: login.php");
    exit;
}

if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') {
    $http_url = "http://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
    header("Location: $http_url");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="icon" href="dole.png" type="dole.png"> 
    <style>
        body {
            font-family: Arial, sans-serif;
            display: flex;
            height: 100vh;
            margin: 0;
            overflow: hidden;
        }
        .sidebar {
            width: 150px;
            background-color: #4A6FA5;
            color: white;
            display: flex;
            flex-direction: column;
            padding: 10px;
            height: 100vh;
        }
        .sidebar a {
            color: white;
            text-decoration: none;
            padding: 8px 0;
            margin: 8px 0;
            font-size: 14px;
            display: flex;
            align-items: center;
        }
        .sidebar a img {
            margin-right: 8px;
            width: 20px; 
            height: 20px; 
        }
        .sidebar a:hover {
            background-color: #3A5F85;
        }
        .main-content {
            flex: 1;
            padding: 10px;
            background-color: #f9f9f9;
            overflow-y: auto;
        }
        .header {
            background-color: #E3F2FD;
            color: #333;
            padding: 10px;
            text-align: center;
            font-size: 18px;
            position: relative;
        }
        .clock {
            position: absolute;
            top: 50%;
            right: 10px;
            transform: translateY(-50%);
            font-size: 12px;
            font-weight: bold;
        }
        .home-box-container {
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
            margin-top: 20px;
        }
        .home-box {
            width: 150px;
            height: 120px;
            background-color: #4A6FA5;
            color: white;
            display: flex;
            justify-content: center;
            align-items: center;
            text-align: center;
            font-size: 14px;
            font-weight: bold;
            border-radius: 10px;
            cursor: pointer;
            transition: transform 0.2s, background-color 0.2s;
        }
        .home-box:hover {
            transform: scale(1.05);
            background-color: #3A5F85;
        }
        .home-box a {
            color: white;
            text-decoration: none;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            width: 100%;
            height: 100%;
        }
        .home-box img {
            width: 40px;
            height: 40px;
            margin-bottom: 10px;
        }
    </style>
    <script>
        function confirmLogout(event) {
            if (!confirm("Are you sure you want to log out?")) {
                event.preventDefault();
            }
        }

        function updateGreetingAndClock() {
            const now = new Date();
            let hours = now.getHours();
            const minutes = now.getMinutes().toString().padStart(2, '0');
            const seconds = now.getSeconds().toString().padStart(2, '0');
            const ampm = hours >= 12 ? 'PM' : 'AM';
            hours = hours % 12 || 12;
            const timeString = `${hours}:${minutes}:${seconds} ${ampm}`;
            document.getElementById('clock').textContent = timeString;

            let greeting = "GOOD MORNING!";
            if (now.getHours() >= 12 && now.getHours() < 18) {
                greeting = "GOOD AFTERNOON!";
            } else if (now.getHours() >= 18 || now.getHours() < 6) {
                greeting = "GOOD EVENING!";
            }
            document.getElementById('greeting').textContent = greeting;
        }

        setInterval(updateGreetingAndClock, 1000);
        window.onload = updateGreetingAndClock;
    </script>
</head>
<body>
<div class="sidebar">
        <h5>Dashboard</h5>
        <a href="admin.php">
            <img src="home.png" alt="Home"> Home
        </a>
        <a href="statistics.php">
            <img src="statics.png" alt="Statistics"> Statistics
        </a>
        <a href="data.php">
            <img src="data.png" alt="Data"> Workers Association Data
        </a>
        <a href="Registration Establishment/registered_data.php">
            <img src="data.png" alt="Registered Data"> Registered Data of Establishment
        </a>
        <a href="settings.php">
            <img src="settings.png" alt="Settings"> Settings
        </a>
        <a href="logout.php" onclick="confirmLogout(event)">
            <img src="logout.png" alt="Logout"> Logout
        </a>
    </div>
    <div class="main-content">
        <div class="header">
            <h1 id="greeting">GOOD MORNING!</h1>
            <div id="clock" class="clock"></div>
        </div>
        <div class="home-box-container">
            <div class="home-box">
                <a href="data.php">
                    <img src="data-collection.gif" alt="Data Icon">
                   Workers Association Data
                </a>
            </div>
            <div class="home-box">
                <a href="statistics.php">
                    <img src="evolution.gif" alt="Statistics Icon">
                    Statistics
                </a>
            </div>
            <div class="home-box">
                <a href="WAsform.php">
                    <img src="form.gif" alt="Form Icon">
                    Workers Association Form
                </a>
            </div>
            <div class="home-box">
                <a href="Registration Establishment/form.php">
                    <img src="form.gif" alt="Registration Icon">
                    Registration Establishment Form
                </a>
            </div>
            <div class="home-box">
                <a href="Registration Establishment/registered_data.php">
                    <img src="data-collection.gif" alt="Registered Data Icon">
                    Registered Data of Establishment
                </a>
            </div>
        </div>
    </div>
</body>
</html>