<?php
session_start();
require_once "../config.php";

if (!isset($_SESSION['user_id']) || $_SESSION['role_id'] != 1) {
    header("Location: ../login.php"); exit();
}

$invoice_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($invoice_id > 0) {
    $updateQuery = "UPDATE invoices SET payment_status = 'Paid' WHERE invoice_id = $invoice_id";
    
    if (mysqli_query($conn, $updateQuery)) {
        $_SESSION['success'] = "Success! Invoice has been officially marked as Paid.";
    } else {
        $_SESSION['error'] = "Database Error: " . mysqli_error($conn);
    }
}

header("Location: invoices.php");
exit();
?>
