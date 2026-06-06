<?php
session_destroy();

session_start();
$_SESSION['success'] = "You have been successfully logged out.";

header("Location: login.php");
exit();
?>
