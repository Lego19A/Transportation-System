<?php
// Database connection parameters
$servername = "localhost";
$username = "user";  // default XAMPP username
$password = "password123";      // default XAMPP password
$dbname = "jom_bus";

// Create connection
$conn = mysqli_connect($servername, $username, $password, $dbname);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
?>
