<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
require_once "config.php";

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $email = trim($_POST["email"]);
    $password = trim($_POST["password"]);

    $sql = "SELECT *
            FROM users
            WHERE email = ?
            AND status = 'Active'
            LIMIT 1";

    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);

    if ($user = mysqli_fetch_assoc($result)) {

        if (password_verify($password, $user['password_hash'])) {

            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['role_id'] = $user['role_id'];
            $_SESSION['email'] = $user['email'];
            $_SESSION['first_name'] = $user['first_name'];
            $_SESSION['last_name'] = $user['last_name'];

            $update = "UPDATE users
                       SET last_login = NOW()
                       WHERE user_id = ?";

            $updateStmt = mysqli_prepare($conn, $update);
            mysqli_stmt_bind_param($updateStmt, "i", $user['user_id']);
            mysqli_stmt_execute($updateStmt);

            switch($user['role_id']) {

                case 1:
                    header("Location: admin/dashboard.php");
                    break;

                case 2:
                    header("Location: procurement/dashboard.php");
                    break;

                case 3:
                    header("Location: manager/dashboard.php");
                    break;

                case 4:
                    header("Location: vendor/dashboard.php");
                    break;

                default:
                    header("Location: dashboard.php");
                    break;
            }

            exit();
        }
    }

    $error = "Invalid Email or Password";
}
?>
<?php
if(isset($_SESSION['success']))
{
    echo '<div class="alert alert-success text-center">'
         . $_SESSION['success'] .
         '</div>';

    unset($_SESSION['success']);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>VendorBridge Login</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="style.css">

</head>
<body>

<div class="container-fluid vh-100">
    <div class="row h-100">

        <!-- Left Panel -->

        <div class="col-lg-6 left-panel d-none d-lg-flex">

            <div>

                <h1>VendorBridge</h1>

                <p>
                    Smart Procurement & Vendor Management Platform
                </p>

                <ul>
                    <li>RFQ Management</li>
                    <li>Vendor Quotations</li>
                    <li>Approval Workflow</li>
                    <li>Purchase Orders</li>
                    <li>Invoice Management</li>
                    <li>Reports & Analytics</li>
                </ul>

            </div>

        </div>

        <!-- Login Form -->

        <div class="col-lg-6 d-flex align-items-center justify-content-center">

            <div class="login-card">

                <h2>Welcome Back</h2>

                <p class="text-muted mb-4">
                    Sign in to continue
                </p>

                <?php if(!empty($error)) { ?>

                    <div class="alert alert-danger">
                        <?php echo $error; ?>
                    </div>

                <?php } ?>

                <form method="POST">

                    <div class="mb-3">
                        <label class="form-label">
                            Email Address
                        </label>

                        <input
                            type="email"
                            name="email"
                            class="form-control"
                            required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">
                            Password
                        </label>

                        <div class="password-wrapper">

                            <input
                                type="password"
                                id="password"
                                name="password"
                                class="form-control"
                                placeholder="Enter your password"
                                required>

                            <button
                                type="button"
                                class="show-password-btn"
                                onclick="togglePassword()">
                                    👁️
                            </button>

                        </div>
                    </div>

                    <div class="d-flex justify-content-between mb-3">

                        <div>
                            <input type="checkbox" id="remember">
                            <label for="remember">
                                Remember Me
                            </label>
                        </div>

                        <a href="forgot_password.php">
                            Forgot Password?
                        </a>

                    </div>

                    <button type="submit" class="btn btn-primary w-100">
                        Login
                    </button>

                </form>

                <div class="text-center mt-4">

                    Don't have an account?

                    <a href="registration.php">
                        Sign Up
                    </a>
                </div>
                <script>
                function togglePassword() {

                    const passwordField = document.getElementById("password");
                    const button = document.querySelector(".show-password-btn");

                    if (passwordField.type === "password") {
                        passwordField.type = "text";
                        button.textContent = "Hide";
                    } else {
                        passwordField.type = "password";
                        button.textContent = "👁️";
                    }
                }
                </script>
            </div>
        </div>
    </div>
</div>
</body>

</html>
