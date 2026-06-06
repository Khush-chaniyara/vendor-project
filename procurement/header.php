<?php
session_start();
require_once "../config.php"; 

if (!isset($_SESSION['user_id']) || $_SESSION['role_id'] != 2) { 
    header("Location: ../login.php"); exit();
}

$user_name = $_SESSION['first_name'] . " " . $_SESSION['last_name'];
$user_id = $_SESSION['user_id'];
$current_page = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Procurement Desk - VendorBridge</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    <style>
        body { background-color: #f4f7f6; font-family: 'Segoe UI', sans-serif; }
        .top-bar { background: linear-gradient(135deg, #0c8599, #099268); color: white; box-shadow: 0 2px 10px rgba(0,0,0,0.1); z-index: 1000; }
        .top-bar .navbar-brand { font-weight: bold; font-size: 1.5rem; color: white; text-decoration: none; }
        .wrapper { display: flex; width: 100%; height: calc(100vh - 60px); }
        .sidebar { width: 250px; background: #fff; border-right: 1px solid #e0e0e0; padding-top: 20px; overflow-y: auto; }
        .sidebar a { display: block; padding: 12px 20px; color: #495057; text-decoration: none; font-weight: 500; border-left: 4px solid transparent; }
        .sidebar a:hover, .sidebar a.active { background: #f8f9fa; color: #0c8599; border-left: 4px solid #0c8599; }
        .sidebar i { margin-right: 10px; }
        .main-content { flex: 1; padding: 30px; overflow-y: auto; }
        .stat-card { background: #fff; border-radius: 10px; padding: 20px; box-shadow: 0 2px 8px rgba(0,0,0,0.05); border: 1px solid #eee; height: 100%; }
        .stat-card h3 { font-size: 2rem; font-weight: bold; color: #343a40; margin-bottom: 5px; }
        .stat-card p { color: #6c757d; margin: 0; font-size: 0.85rem; text-transform: uppercase; font-weight: 600; }
    </style>
</head>
<body>

    <nav class="navbar top-bar px-4 py-2 d-flex justify-content-between align-items-center">
        <a class="navbar-brand" href="dashboard.php"><i class="bi bi-box-seam"></i> Procurement Desk</a>
        <div class="user-details text-white text-center">
            <span class="d-block fw-bold"><?php echo htmlspecialchars($user_name); ?> (Buyer)</span>
        </div>
        <a href="../logout.php" class="btn btn-sm btn-light text-danger fw-bold shadow-sm"><i class="bi bi-box-arrow-right"></i> Logout</a>
    </nav>

    <div class="wrapper">
        <div class="sidebar">
            <div class="px-3 mb-3 text-muted small fw-bold">SOURCING MENU</div>
            
            <a href="dashboard.php" class="<?php echo ($current_page == 'dashboard.php') ? 'active' : ''; ?>">
                <i class="bi bi-speedometer2"></i> Dashboard
            </a>
            <a href="rfqs.php" class="<?php echo ($current_page == 'rfqs.php' || $current_page == 'create_rfq.php') ? 'active' : ''; ?>">
                <i class="bi bi-tags"></i> Manage RFQs
            </a>
            <a href="quotations.php" class="<?php echo ($current_page == 'quotations.php') ? 'active' : ''; ?>">
                <i class="bi bi-ui-checks"></i> Evaluate Bids
            </a>
            <a href="purchase_orders.php" class="<?php echo ($current_page == 'purchase_orders.php') ? 'active' : ''; ?>">
                <i class="bi bi-cart-check"></i> Purchase Orders
            </a>
            <a href="invoices.php" class="<?php echo ($current_page == 'invoices.php') ? 'active' : ''; ?>">
                <i class="bi bi-receipt"></i> Invoices
            </a>
        </div>
        <div class="main-content">