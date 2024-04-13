<?php
// Include config file
require_once "connection/config.php";

// Start the session
session_start();

// Unset all of the session variables
$_SESSION = array();

// Destroy the session
session_destroy();

// Redirect to the login page or any other page
header("location: login.php");
exit;
?>
<!-- unchange-->