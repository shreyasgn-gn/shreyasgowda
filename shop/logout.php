<?php
session_start();

// 🔒 Destroy session and clear all session variables
$_SESSION = [];
session_unset();
session_destroy();

// 🚪 Redirect to login page
header("Location: login.php");
exit;
?>