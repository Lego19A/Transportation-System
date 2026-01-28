<?php
// Database connection parameters
$servername = "local";
$username = "user";  
$password = "password123";      
$dbname = "jom_bus";

// Create connection
$conn = mysqli_connect($servername, $username, $password, $dbname);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
?>
