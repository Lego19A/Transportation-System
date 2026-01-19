<?php
// Database connection parameters
$servername = "jombusmysql.cdkkthw5xyg7.us-east-1.rds.amazonaws.com";
$username = "jombus_user";  // default XAMPP username
$password = "JomBusPass123!";      // default XAMPP password
$dbname = "jom_bus";

// Create connection
$conn = mysqli_connect($servername, $username, $password, $dbname);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
?>
