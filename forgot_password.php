<?php
session_start();
require_once "config.php";

$error = "";
$success = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST["email"]);

    // 1. Check if the email exists in the users table
    $sql = "SELECT user_id, first_name FROM users WHERE email = ? AND status = 'Active'";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if ($user = mysqli_fetch_assoc($result)) {
        $success = "Success! Password reset instructions have been sent to <strong>" . htmlspecialchars($email) . "</strong>.";
    } else {
        $error = "No active account found with that email address.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password - VendorBridge</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background: #f8f9fa; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
        
        .left-panel { background: linear-gradient(135deg, #1971c2, #2f9e44); color: white; padding: 60px; display: flex; align-items: center; }
        .left-panel h1 { font-size: 3.5rem; font-weight: 700; margin-bottom: 15px; letter-spacing: -1px; }
        .left-panel p { font-size: 1.2rem; opacity: 0.9; }
        
        .auth-card { width: 100%; max-width: 450px; background: white; padding: 40px; border-radius: 18px; box-shadow: 0 10px 30px rgba(0,0,0,.08); }
        .auth-card h2 { font-weight: 700; color: #343a40; }
        
        .form-control { height: 50px; border-radius: 8px; background-color: #f8f9fa; border: 1px solid #dee2e6; }
        .form-control:focus { box-shadow: none; border-color: #1971c2; background-color: #fff; }
        
        .btn-primary { height: 50px; border: none; background: #1971c2; font-weight: 600; border-radius: 8px; }
        .btn-primary:hover { background: #1864ab; }
        
        a { text-decoration: none; color: #1971c2; font-weight: 600; }
        a:hover { color: #1864ab; }
    </style>
</head>
<body>

<div class="container-fluid vh-100">
    <div class="row h-100">

        <div class="col-lg-6 left-panel d-none d-lg-flex">
            <div>
                <h1>VendorBridge</h1>
                <p>Smart Procurement & Vendor Management Platform</p>
                <div class="mt-5">
                    <div class="d-flex align-items-center mb-3">
                        <div class="bg-white text-primary rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px; font-weight: bold;">✓</div>
                        <span class="fs-5">Enterprise Grade Security</span>
                    </div>
                    <div class="d-flex align-items-center mb-3">
                        <div class="bg-white text-success rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px; font-weight: bold;">✓</div>
                        <span class="fs-5">Encrypted Credentials</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-6 d-flex align-items-center justify-content-center">
            <div class="auth-card">
                
                <div class="text-center mb-4">
                    <h2>Reset Password</h2>
                    <p class="text-muted">Enter your email to receive reset instructions</p>
                </div>

                <?php if(!empty($error)) { ?>
                    <div class="alert alert-danger border-0 shadow-sm text-center">
                        <?php echo $error; ?>
                    </div>
                <?php } ?>

                <?php if(!empty($success)) { ?>
                    <div class="alert alert-success border-0 shadow-sm text-center py-4">
                        <div class="mb-3" style="font-size: 3rem;">✉️</div>
                        <p class="mb-0"><?php echo $success; ?></p>
                    </div>
                    <a href="login.php" class="btn btn-outline-primary w-100 mt-3" style="height: 50px; line-height: 38px; font-weight: 600;">Return to Login</a>
                <?php } else { ?>
                    <form method="POST">
                        <div class="mb-4">
                            <label class="form-label text-muted fw-bold small text-uppercase">Registered Email</label>
                            <input type="email" name="email" class="form-control" placeholder="name@company.com" required autofocus>
                        </div>

                        <button type="submit" class="btn btn-primary w-100 shadow-sm">
                            Send Reset Link
                        </button>
                    </form>

                    <div class="text-center mt-4 text-muted">
                        Remembered your password? <a href="login.php">Back to Login</a>
                    </div>
                <?php } ?>

            </div>
        </div>
    </div>
</div>

</body>
</html>
