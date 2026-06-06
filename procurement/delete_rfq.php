<?php
session_start();
require_once "../config.php";

if (!isset($_SESSION['user_id']) || $_SESSION['role_id'] != 2) {
    header("Location: ../login.php"); exit();
}

$rfq_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$user_id = $_SESSION['user_id'];

if ($rfq_id > 0) {
    $checkSql = "SELECT rfq_number FROM rfqs WHERE rfq_id = $rfq_id AND created_by = $user_id AND status IN ('Open', 'Draft')";
    $checkResult = mysqli_query($conn, $checkSql);

    if (mysqli_num_rows($checkResult) > 0) {
        $rfq = mysqli_fetch_assoc($checkResult);
        $rfq_number = $rfq['rfq_number'];
        
        $deleteSql = "DELETE FROM rfqs WHERE rfq_id = $rfq_id";
        
        if (mysqli_query($conn, $deleteSql)) {
            $_SESSION['success'] = "RFQ <strong>$rfq_number</strong> was successfully deleted.";
        } else {
            $_SESSION['error'] = "Failed to delete RFQ.";
        }
    } else {
        $_SESSION['error'] = "You cannot delete this RFQ. It may be locked or you lack permission.";
    }
}

header("Location: rfqs.php");
exit();
?>