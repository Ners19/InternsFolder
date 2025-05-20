<?php
session_start();

if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true || $_SESSION["role"] !== "admin") {
    header("Location: login.php");
    exit;
}

// Include the database connection file
include 'database.php';

// Fetch data for registrations per municipality
$sql = "SELECT applicantaddress as municipality, COUNT(*) as count FROM register GROUP BY applicantaddress";
$result = mysqli_query($conn, $sql);

$municipalities = [];
$municipalityCounts = [];

if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $municipalities[] = $row["municipality"];
        $municipalityCounts[] = $row["count"];
    }
}

// Fetch data for registrations per day
$sql = "SELECT DATE(dateaccomplished) as date, COUNT(*) as count FROM register GROUP BY DATE(dateaccomplished)";
$result = mysqli_query($conn, $sql);

$dates = [];
$dayCounts = [];

if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $dates[] = $row["date"];
        $dayCounts[] = $row["count"];
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Statistics Data</title>
    <link rel="icon" href="dole.png" type="image/png">
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
        }
        .section {
            background-color: white;
            border: 1px solid #ccc;
            border-radius: 8px;
            padding: 10px;
            margin-bottom: 10px;
        }
        .chart-container {
            position: relative;
            width: 100%;
            max-width: 500px;
            max-height: 500px;
            margin: auto;
        }
        @media (max-width: 768px) {
            .chart-container {
                max-width: 100%;
                max-height: 400px;
            }
        }
    </style>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
<div class="sidebar">
        <h5>Admin Dashboard</h5>
        <a href="admin.php">
            <img src="home.png" alt="Home" class="home-icon"> Home
        </a>
        <a href="statistics.php">
            <img src="statics.png" alt="Statistics" class="statistics-icon"> Statistics
        </a>
        <a href="data.php">
            <img src="data.png" alt="Data" class="data-icon"> Workers Association Data
        </a>
        <a href="Registration Establishment/registered_data.php">
            <img src="data.png" alt="Data" class="data-icon"> Registered Data of Establishment
        </a>
        <a href="settings.php">
            <img src="settings.png" alt="Settings" class="setting-icon"> Settings
        </a>
        <a href="logout.php" onclick="confirmLogout(event)">
            <img src="logout.png" alt="Logout" class="logout-icon"> Logout
        </a>
    </div>
    <div class="main-content">
        <div class="header">
            <h1>Statistics</h1>
        </div>
        <div class="section">
            <h2>Registrations per Municipality</h2>
            <div class="chart-container">
                <canvas id="municipalityChart"></canvas>
            </div>
        </div>
        <div class="section">
            <h2>Registrations per Day</h2>
            <div class="chart-container">
                <canvas id="dayChart"></canvas>
            </div>
        </div>
    </div>
    <script>
        function confirmLogout(event) {
            if (!confirm("Are you sure you want to log out?")) {
                event.preventDefault();
            }
        }

        // Data for the municipality chart
        const municipalities = <?php echo json_encode($municipalities); ?>;
        const municipalityCounts = <?php echo json_encode($municipalityCounts); ?>;

        // Create the municipality chart
        const municipalityCtx = document.getElementById('municipalityChart').getContext('2d');
        const municipalityChart = new Chart(municipalityCtx, {
            type: 'pie',
            data: {
                labels: municipalities,
                datasets: [{
                    label: 'Registrations per Municipality',
                    data: municipalityCounts,
                    backgroundColor: [
                        'rgba(255, 99, 132, 0.2)',
                        'rgba(54, 162, 235, 0.2)',
                        'rgba(255, 206, 86, 0.2)',
                        'rgba(75, 192, 192, 0.2)',
                        'rgba(153, 102, 255, 0.2)',
                        'rgba(255, 159, 64, 0.2)'
                    ],
                    borderColor: [
                        'rgba(255, 99, 132, 1)',
                        'rgba(54, 162, 235, 1)',
                        'rgba(255, 206, 86, 1)',
                        'rgba(75, 192, 192, 1)',
                        'rgba(153, 102, 255, 1)',
                        'rgba(255, 159, 64, 1)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'top',
                    },
                    title: {
                        display: true,
                        text: 'Registrations per Municipality'
                    }
                }
            }
        });

        // Data for the day chart
        const dates = <?php echo json_encode($dates); ?>;
        const dayCounts = <?php echo json_encode($dayCounts); ?>;

        // Create the day chart
        const dayCtx = document.getElementById('dayChart').getContext('2d');
        const dayChart = new Chart(dayCtx, {
            type: 'bar',
            data: {
                labels: dates,
                datasets: [{
                    label: 'Registrations per Day',
                    data: dayCounts,
                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                    borderColor: 'rgba(75, 192, 192, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top',
                    },
                    title: {
                        display: true,
                        text: 'Registrations per Day'
                    }
                }
            }
        });
    </script>
</body>
</html>