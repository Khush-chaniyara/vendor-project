<?php
session_start();
// Go up one level to find config.php
require_once "../config.php"; 

if (!isset($_SESSION['user_id']) || $_SESSION['role_id'] != 4) { 
    header("Location: ../login.php"); 
    exit();
}

$user_name = $_SESSION['first_name'] . " " . $_SESSION['last_name'];
$current_page = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vendor Portal - VendorBridge</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    <style>
        body { background-color: #f4f7f6; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
        
        /* Top Navigation Bar */
        .top-bar { background: linear-gradient(135deg, #364fc7, #5c7cfa); color: white; padding: 10px 25px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        .navbar-brand { font-weight: bold; font-size: 1.5rem; color: white; }
        
        /* Sidebar Styling */
        .wrapper { display: flex; width: 100%; height: calc(100vh - 60px); }
        .sidebar { width: 250px; background: #fff; border-right: 1px solid #e0e0e0; padding-top: 20px; }
        .sidebar a { display: block; padding: 12px 20px; color: #495057; text-decoration: none; font-weight: 500; border-left: 4px solid transparent; transition: all 0.2s; }
        .sidebar a:hover, .sidebar a.active { background: #f8f9fa; color: #364fc7; border-left: 4px solid #364fc7; }
        .sidebar i { margin-right: 10px; }
        
        /* Main Content Area */
        .main-content { flex: 1; padding: 30px; overflow-y: auto; }
        
        /* Cards */
        .stat-card { background: #fff; border-radius: 10px; padding: 20px; box-shadow: 0 2px 8px rgba(0,0,0,0.05); border: 1px solid #eee; }
        .stat-card h3 { font-size: 2rem; font-weight: bold; color: #343a40; margin-bottom: 5px; }
        .stat-card p { color: #6c757d; margin: 0; font-size: 0.85rem; text-transform: uppercase; font-weight: 600; }
    </style>
</head>
<body>

    <nav class="top-bar d-flex justify-content-between align-items-center">
        <a class="navbar-brand" href="dashboard.php">VendorBridge</a>
        <div class="text-white">Welcome, <strong><?php echo htmlspecialchars($user_name); ?></strong></div>
        <a href="../logout.php" class="btn btn-sm btn-outline-light"><i class="bi bi-box-arrow-right"></i> Logout</a>
    </nav>

    <div class="wrapper">
        <div class="sidebar">
            <div class="px-3 mb-3 text-muted small fw-bold text-uppercase">Vendor Portal</div>
            <a href="dashboard.php" class="<?php echo ($current_page == 'dashboard.php') ? 'active' : ''; ?>"><i class="bi bi-speedometer2"></i> Dashboard</a>
            <a href="open_rfqs.php" class="<?php echo ($current_page == 'open_rfqs.php') ? 'active' : ''; ?>"><i class="bi bi-tags"></i> Open RFQs</a>
            <a href="my_quotations.php" class="<?php echo ($current_page == 'my_quotations.php') ? 'active' : ''; ?>"><i class="bi bi-ui-checks"></i> My Quotations</a>
            <a href="my_pos.php" class="<?php echo ($current_page == 'my_pos.php') ? 'active' : ''; ?>"><i class="bi bi-cart-check"></i> My POs</a>
        </div>
        <div class="main-content">