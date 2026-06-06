<?php
session_destroy();

// Start a fresh session specifically to pass a success message to the login page
session_start();
$_SESSION['success'] = "You have been successfully logged out.";

header("Location: login.php");
exit();
?>