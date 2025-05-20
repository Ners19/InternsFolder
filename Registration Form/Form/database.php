<?php
$servername = "localhost";
$username = "root";
$password = ""; // Ensure this matches your MySQL setup
$database = "workers_association"; // Use the workers_association database

$conn = mysqli_connect($servername, $username, $password, $database);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
?>