<?php
session_start();
require_once "../config.php"; 

// Protect all admin pages globally
if (!isset($_SESSION['user_id']) || $_SESSION['role_id'] != 1) { // 1 = Admin
    header("Location: ../login.php");
    exit();
}

$user_name = $_SESSION['first_name'] . " " . $_SESSION['last_name'];
$user_email = $_SESSION['email'];

// Get the current page name (e.g., "vendors.php") to set the active class on the sidebar
$current_page = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>VendorBridge - Admin Panel</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    <style>
        body { 
            background-color: #f4f7f6; 
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; 
        }
        
        .top-bar { 
            background: linear-gradient(135deg, #1971c2, #2f9e44); 
            color: white; 
            box-shadow: 0 2px 10px rgba(0,0,0,0.1); 
            z-index: 1000; 
        }
        .top-bar .navbar-brand { font-weight: bold; font-size: 1.5rem; color: white; text-decoration: none; }
        .top-bar .user-details { font-size: 0.9rem; text-align: center; }
        
        .wrapper { display: flex; width: 100%; height: calc(100vh - 60px); }
        
        .sidebar { 
            width: 250px; 
            background: #fff; 
            border-right: 1px solid #e0e0e0; 
            padding-top: 20px; 
            overflow-y: auto;
        }
        .sidebar a { 
            display: block; 
            padding: 12px 20px; 
            color: #495057; 
            text-decoration: none; 
            font-weight: 500; 
            border-left: 4px solid transparent; 
            transition: all 0.2s ease-in-out;
        }
        .sidebar a:hover, .sidebar a.active { 
            background: #f8f9fa; 
            color: #1971c2; 
            border-left: 4px solid #1971c2; 
        }
        .sidebar i { margin-right: 10px; }
        
        .main-content { 
            flex: 1; 
            padding: 30px; 
            overflow-y: auto; 
        }
        
        .stat-card { 
            background: #fff; 
            border-radius: 10px; 
            padding: 20px; 
            box-shadow: 0 2px 8px rgba(0,0,0,0.05); 
            border: 1px solid #eee; 
            height: 100%;
        }
        .stat-card h3 { font-size: 2rem; font-weight: bold; color: #343a40; margin-bottom: 5px; }
        .stat-card p { color: #6c757d; margin: 0; font-size: 0.85rem; text-transform: uppercase; letter-spacing: 0.5px; font-weight: 600; }
        
        .chart-placeholder { 
            background: #fff; border-radius: 10px; padding: 20px; height: 300px; 
            display: flex; align-items: center; justify-content: center; 
            border: 1px dashed #ccc; color: #999; margin-bottom: 30px; 
        }
    </style>
</head>
<body>

    <nav class="navbar top-bar px-4 py-2 d-flex justify-content-between align-items-center">
        <a class="navbar-brand" href="dashboard.php"><i class="bi bi-buildings"></i> VendorBridge</a>
        
        <div class="user-details text-white">
            <span class="d-block fw-bold"><?php echo htmlspecialchars($user_name); ?> (Admin)</span>
            <small class="text-light"><?php echo htmlspecialchars($user_email); ?></small>
        </div>
        
        <a href="../logout.php" class="btn btn-sm btn-light text-danger fw-bold shadow-sm">
            <i class="bi bi-box-arrow-right"></i> Logout
        </a>
    </nav>

    <div class="wrapper">
        
        <div class="sidebar">
            <div class="px-3 mb-3 text-muted small fw-bold">ADMIN MENU</div>
            
            <a href="dashboard.php" class="<?php echo ($current_page == 'dashboard.php') ? 'active' : ''; ?>">
                <i class="bi bi-speedometer2"></i> Dashboard
            </a>
            <a href="vendors.php" class="<?php echo ($current_page == 'vendors.php') ? 'active' : ''; ?>">
                <i class="bi bi-people"></i> Vendors
            </a>
            <a href="quotations.php" class="<?php echo ($current_page == 'quotations.php') ? 'active' : ''; ?>">
                <i class="bi bi-file-earmark-text"></i> Quotations
            </a>
            <a href="invoices.php" class="<?php echo ($current_page == 'invoices.php') ? 'active' : ''; ?>">
                <i class="bi bi-receipt"></i> PO & Invoices
            </a>
            <a href="reports.php" class="<?php echo ($current_page == 'reports.php') ? 'active' : ''; ?>">
                <i class="bi bi-graph-up-arrow"></i> Reports & Analytics
            </a>
        </div>

        <div class="main-content">
