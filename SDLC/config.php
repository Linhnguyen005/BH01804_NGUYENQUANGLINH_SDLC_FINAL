<?php
$servername = "localhost"; // MySQL server
$username = "root"; // MySQL username
$password = ""; // Password (leave empty if using XAMPP)
$dbname = "shop_vpp"; // Database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Error: Unable to connect to the database. Please check 'config.php'. " . $conn->connect_error);
}
?>
