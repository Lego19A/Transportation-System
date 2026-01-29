<?php
// Database connection parameters
$servername = "jombusmysql.cmkotmz32fia.us-east-1.rds.amazonaws.com";
$username = "admin";  
$password = "jom123bus";      
$dbname = "jom_bus";

// Create connection
$conn = mysqli_connect($servername, $username, $password, $dbname);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
?>
