<?php
// IF YOU GET THIS MESSAGE THIS IS ONLY NEEDS TO BE FIXED 
// session_start();

// // Redirect to login if the user is not logged in or not an admin
// if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true || $_SESSION["role"] !== "admin") {
//     header("Location: login.php");
//     exit;
// }

// // Ensure the admin ID is set in the session
// if (!isset($_SESSION["id"])) {
//     session_destroy();
//     header("Location: admin.php");
//     exit;
// }

// include 'database.php';

// $update_success = "";
// $update_error = "";

// // Handle admin profile update
// if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_profile'])) {
//     $username = trim($_POST["username"]);
//     $email = trim($_POST["email"]);
//     $name = trim($_POST["name"]);
//     $address = trim($_POST["address"]);
//     $contact_number = trim($_POST["contact_number"]);
//     $id_number = trim($_POST["id_number"]);
//     $profile = trim($_POST["profile"]);

//     // Validate inputs
//     if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
//         $update_error = "Invalid email format.";
//     } elseif (!preg_match("/^[a-zA-Z0-9_]{3,20}$/", $username)) {
//         $update_error = "Username must be 3-20 characters long and contain only letters, numbers, and underscores.";
//     } else {
//         $username = mysqli_real_escape_string($conn, $username);
//         $email = mysqli_real_escape_string($conn, $email);
//         $name = mysqli_real_escape_string($conn, $name);
//         $address = mysqli_real_escape_string($conn, $address);
//         $contact_number = mysqli_real_escape_string($conn, $contact_number);
//         $id_number = mysqli_real_escape_string($conn, $id_number);
//         $profile = mysqli_real_escape_string($conn, $profile);

//         $sql = "UPDATE admins SET 
//                     username='$username', 
//                     email='$email', 
//                     name='$name', 
//                     address='$address', 
//                     contact_number='$contact_number', 
//                     id_number='$id_number', 
//                     profile='$profile' 
//                 WHERE id=" . intval($_SESSION["id"]);
//         if (mysqli_query($conn, $sql)) {
//             $update_success = "Admin profile updated successfully.";
//             session_regenerate_id(true); // Regenerate session ID for security
//         } else {
//             $update_error = "Error updating admin profile: " . mysqli_error($conn);
//         }
//     }
// }

// // Handle admin password change
// if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['change_password'])) {
//     $current_password = $_POST["current_password"];
//     $new_password = $_POST["new_password"];
//     $confirm_password = $_POST["confirm_password"];

//     if (strlen($new_password) < 8) {
//         $update_error = "New password must be at least 8 characters long.";
//     } elseif (!preg_match("/[A-Z]/", $new_password) || !preg_match("/[0-9]/", $new_password)) {
//         $update_error = "New password must contain at least one uppercase letter and one number.";
//     } elseif ($new_password !== $confirm_password) {
//         $update_error = "New password and confirm password do not match.";
//     } else {
//         $sql = "SELECT password FROM admins WHERE id=" . intval($_SESSION["id"]);
//         $result = mysqli_query($conn, $sql);
//         if ($result && $row = mysqli_fetch_assoc($result)) {
//             if (password_verify($current_password, $row["password"])) {
//                 $new_password_hashed = password_hash($new_password, PASSWORD_DEFAULT);
//                 $sql = "UPDATE admins SET password='$new_password_hashed' WHERE id=" . intval($_SESSION["id"]);
//                 if (mysqli_query($conn, $sql)) {
//                     $update_success = "Admin password changed successfully.";
//                     session_regenerate_id(true); // Regenerate session ID for security
//                 } else {
//                     $update_error = "Error changing admin password: " . mysqli_error($conn);
//                 }
//             } else {
//                 $update_error = "Current password is incorrect.";
//             }
//         } else {
//             $update_error = "Error fetching current password.";
//         }
//     }
// }

// // Handle admin profile deletion
// if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete_profile'])) {
//     $sql = "DELETE FROM admins WHERE id=" . intval($_SESSION["id"]);
//     if (mysqli_query($conn, $sql)) {
//         session_destroy();
//         header("Location: login.php");
//         exit;
//     } else {
//         $update_error = "Error deleting admin profile: " . mysqli_error($conn);
//     }
// }

// // Fetch current admin profile information
// $sql = "SELECT username, email, name, address, contact_number, id_number, profile FROM admins WHERE id=" . intval($_SESSION["id"]);
// $result = mysqli_query($conn, $sql);
// if ($result) {
//     $row = mysqli_fetch_assoc($result);
//     // Sanitize output
//     foreach ($row as $key => $value) {
//         $row[$key] = htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
//     }
// } else {
//     die("Error fetching admin profile: " . mysqli_error($conn));
// }

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Settings</title>
    <link rel="icon" href="dole.png" type="image/png"> 
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            display: flex;
            height: 100vh;
            margin: 0;
            background: linear-gradient(135deg, #1f1c2c, #928dab);
            color: #fff;
        }
        .sidebar {
            font-size: 22px;
            width: 200px;
            background-color: #2c3e50;
            color: white;
            display: flex;
            flex-direction: column;
            padding: 20px;
            height: 100vh;
            overflow: hidden;
            box-shadow: 2px 0 5px rgba(0,0,0,0.1);
        }
        .sidebar a {
            color: white;
            text-decoration: none;
            padding: 15px 0;
            margin: 10px 0;
            border-bottom: 1px solid #444;
            display: flex;
            align-items: center;
            transition: background 0.3s;
        }
        .sidebar a img {
            margin-right: 10px;
            width: 25px; 
            height: 25px; 
        }
        .sidebar a:hover {
            background-color: #34495e;
        }
        .main-content {
            flex: 1;
            padding: 20px;
            background-color: #ecf0f1;
            overflow-y: auto;
            height: 100vh;
            color: #2c3e50;
        }
        .header {
            background-color: #3498db;
            color: white;
            padding: 15px;
            text-align: center;
            border-radius: 8px;
            margin-bottom: 20px;
        }
        .section {
            background-color: white;
            border: 1px solid #ccc;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        .section h2 {
            margin-top: 0;
            color: #3498db;
        }
        .form-group {
            margin-bottom: 18px;
        }
        .form-group label {
            display: block;
            margin-bottom: 5px;
            color: #2c3e50;
        }
        .form-group input {
            width: 100%;
            padding: 10px;
            box-sizing: border-box;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        .form-group button {
            padding: 10px 15px;
            background-color: #3498db;
            color: white;
            border: none;
            cursor: pointer;
            border-radius: 4px;
            transition: background 0.3s;
        }
        .form-group button:hover {
            background-color: #2980b9;
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
        @media (max-width: 768px) {
            .sidebar {
                width: 100%;
                height: auto;
                flex-direction: row;
                overflow-x: auto;
            }
            .main-content {
                padding: 10px;
            }
            .form-group input {
                width: 100%;
            }
        }
    </style>
    <script>
        function confirmLogout(event) {
            if (!confirm("Are you sure you want to log out?")) {
                event.preventDefault();
            }
        }
    </script>
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
            <h1>Settings</h1>
        </div>
        <div class="section">
            <h2>Update Profile</h2>
            <?php if ($update_success): ?>
                <div class="message success"><?php echo $update_success; ?></div>
            <?php endif; ?>
            <?php if ($update_error): ?>
                <div class="message error"><?php echo $update_error; ?></div>
            <?php endif; ?>
            <form action="settings.php" method="post">
                <div class="form-group">
                    <label for="username">Username:</label>
                    <input type="text" id="username" name="username" value="<?php echo $row['username']; ?>" required>
                </div>
                <div class="form-group">
                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email" value="<?php echo $row['email']; ?>" required>
                </div>
                <div class="form-group">
                    <label for="name">Name:</label>
                    <input type="text" id="name" name="name" value="<?php echo $row['name']; ?>" required>
                </div>
                <div class="form-group">
                    <label for="address">Address:</label>
                    <input type="text" id="address" name="address" value="<?php echo $row['address']; ?>" required>
                </div>
                <div class="form-group">
                    <label for="contact_number">Contact Number:</label>
                    <input type="text" id="contact_number" name="contact_number" value="<?php echo $row['contact_number']; ?>" required>
                </div>
                <div class="form-group">
                    <label for="id_number">ID Number:</label>
                    <input type="text" id="id_number" name="id_number" value="<?php echo $row['id_number']; ?>" required>
                </div>
                <div class="form-group">
                    <label for="profile">Profile:</label>
                    <input type="text" id="profile" name="profile" value="<?php echo $row['profile']; ?>" required>
                </div>
                <div class="form-group">
                    <button type="submit" name="update_profile">Update Profile</button>
                </div>
            </form>
        </div>
        <div class="section">
            <h2>Change Password</h2>
            <form action="settings.php" method="post">
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
        <div class="section">
            <h2>Delete Profile</h2>
            <form action="settings.php" method="post">
                <div class="form-group">
                    <button type="submit" name="delete_profile" onclick="return confirm('Are you sure you want to delete your profile?');">Delete Profile</button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
