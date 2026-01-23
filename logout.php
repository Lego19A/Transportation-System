<?php
// Include session management
include 'session.php';

// Destroy the session
session_start();
session_unset();
session_destroy();

// Redirect to home page
header("Location: home.php");
exit();
?>
