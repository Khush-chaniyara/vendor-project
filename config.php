<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

$host = "localhost";
$username = "root";
$password = "";
$database = "vendorBridge";

$conn = mysqli_connect($host, $username, $password, $database);

// if($conn){
//     echo "connected success";
// }