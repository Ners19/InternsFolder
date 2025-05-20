<?php
session_start();

if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true || $_SESSION["role"] !== "admin") {
    header("Location: login.php");
    exit;
}

include 'database.php';

$update_success = "";
$update_error = "";

// Handle profile update
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_profile'])) {
    $username = $_POST["username"];
    $email = $_POST["email"];

    $sql = "UPDATE admins SET username='$username', email='$email' WHERE id=" . $_SESSION["id"];
    if (mysqli_query($conn, $sql)) {
        $update_success = "Profile updated successfully.";
    } else {
        $update_error = "Error updating profile: " . mysqli_error($conn);
    }
}

// Handle password change
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['change_password'])) {
    $current_password = $_POST["current_password"];
    $new_password = $_POST["new_password"];
    $confirm_password = $_POST["confirm_password"];

    $sql = "SELECT password FROM admins WHERE id=" . $_SESSION["id"];
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);

    if (password_verify($current_password, $row["password"])) {
        if ($new_password == $confirm_password) {
            $new_password_hashed = password_hash($new_password, PASSWORD_DEFAULT);
            $sql = "UPDATE admins SET password='$new_password_hashed' WHERE id=" . $_SESSION["id"];
            if (mysqli_query($conn, $sql)) {
                $update_success = "Password changed successfully.";
            } else {
                $update_error = "Error changing password: " . mysqli_error($conn);
            }
        } else {
            $update_error = "New password and confirm password do not match.";
        }
    } else {
        $update_error = "Current password is incorrect.";
    }
}

// Fetch current profile information
//$sql = "SELECT username, email FROM admins WHERE id=" . $_SESSION["id"];
//$result = mysqli_query($conn, $sql);
//$row = mysqli_fetch_assoc($result);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Profile</title>
    <style>
        body {
            font-family: system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, 'Open Sans', 'Helvetica Neue', sans-serif, sans-serif;
            display: flex;
            height: 100vh;
            margin: 0;
        }
        .sidebar {
            font-family: Arial, sans-serif;
            font-size: 22px;
            width: 180px;
            background-color: #6A80B9;
            color: white;
            display: flex;
            flex-direction: column;
            padding: 20px;
            height: 100vh;
            overflow: hidden;
        }
        .sidebar a {
            color: white;
            text-decoration: none;
            padding: 10px 0;
            margin: 5px 0;
            border-bottom: 1px solid #444;
            display: flex;
            align-items: center;
        }
        .sidebar a img {
            margin-right: 10px;
            width: 20px; /* Adjust the size of the icons */
            height: 20px; /* Adjust the size of the icons */
        }
        .sidebar a:hover {
            background-color: rgb(140, 138, 138);
        }
        .main-content {
            margin-left: 220px; /* Adjust based on sidebar width */
            padding: 20px;
            background-color: #f4f4f4;
            height: 100vh;
            overflow-y: auto;
        }
        .header {
            background-color: lightblue;
            color: black;
            padding: 15px;
            text-align: center;
        }
        .section {
            background-color: white;
            border: 1px solid #ccc;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 20px;
        }
        .section h2 {
            margin-top: 0;
        }
        .form-group {
            margin-bottom: 15px;
        }
        .form-group label {
            display: block;
            margin-bottom: 5px;
        }
        .form-group input {
            width: 100%;
            padding: 10px;
            box-sizing: border-box;
        }
        .form-group button {
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            border: none;
            cursor: pointer;
        }
        .form-group button:hover {
            background-color: #45a049;
        }
        .message {
            margin-bottom: 15px;
            padding: 10px;
            border-radius: 5px;
        }
        .success {
            background-color: #d4edda;
            color: #155724;
        }
        .error {
            background-color: #f8d7da;
            color: #721c24;
        }
    </style>
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
            <img src="data.png" alt="Data" class="data-icon"> Data
        </a>
        <a href="settings.php">
            <img src="settings.png" alt="Settings" class="setting-icon"> Settings
        </a>
        <a href="adminprofile.php">
            <img src="profile.png" alt="Profile" class="profile-icon"> Profile
        </a>
        <a href="logout.php" onclick="confirmLogout(event)">
            <img src="logout.png" alt="Logout" class="logout-icon"> Logout
        </a>
    </div>
    <div class="main-content">
        <div class="header">
            <h1>Admin Profile</h1>
        </div>
        <div class="section">
            <h2>Update Profile</h2>
            <?php if ($update_success): ?>
                <div class="message success"><?php echo $update_success; ?></div>
            <?php endif; ?>
            <?php if ($update_error): ?>
                <div class="message error"><?php echo $update_error; ?></div>
            <?php endif; ?>
            <form action="adminprofile.php" method="post">
                <div class="form-group">
                    <label for="username">Username:</label>
                    <input type="text" id="username" name="username" value="<?php echo $row['username']; ?>" required>
                </div>
                <div class="form-group">
                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email" value="<?php echo $row['email']; ?>" required>
                </div>
                <div class="form-group">
                    <button type="submit" name="update_profile">Update Profile</button>
                </div>
            </form>
        </div>
        <div class="section">
            <h2>Change Password</h2>
            <form action="adminprofile.php" method="post">
                <div class="form-group">
                    <label for="current_password">Current Password:</label>
                    <input type="password" id="current_password" name="current_password" required>
                </div>
                <div class="form-group">
                    <label for="new_password">New Password:</label>
                    <input type="password" id="new_password" name="new_password" required>
                </div>
                <div class="form-group">
                    <label for="confirm_password">Confirm New Password:</label>
                    <input type="password" id="confirm_password" name="confirm_password" required>
                </div>
                <div class="form-group">
                    <button type="submit" name="change_password">Change Password</button>
                </div>
            </form>
        </div>
    </div>
    <script>
        function confirmLogout(event) {
            if (!confirm("Are you sure you want to log out?")) {
                event.preventDefault();
            }
        }
    </script>
</body>
</html>