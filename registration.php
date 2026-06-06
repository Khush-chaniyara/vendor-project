<?php
session_start();
require_once "config.php";

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $first_name = trim($_POST['first_name']);
    $last_name = trim($_POST['last_name']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $role_id = $_POST['role_id'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Validate fields

    if (
        empty($first_name) ||
        empty($last_name) ||
        empty($email) ||
        empty($phone) ||
        empty($role_id) ||
        empty($password) ||
        empty($confirm_password)
    ) {
        $error = "All fields are required.";
    }

    // Check password match

    elseif ($password != $confirm_password) {
        $error = "Passwords do not match.";
    }

    else {

        // Check email already exists

        $checkEmail = "SELECT user_id FROM users WHERE email = ?";
        $stmt = mysqli_prepare($conn, $checkEmail);
        mysqli_stmt_bind_param($stmt, "s", $email);
        mysqli_stmt_execute($stmt);

        $result = mysqli_stmt_get_result($stmt);

        if (mysqli_num_rows($result) > 0) {

            $error = "Email already registered.";

        } else {

            // Hash Password

            $hashed_password = password_hash(
                $password,
                PASSWORD_DEFAULT
            );

            // Insert User

            $sql = "INSERT INTO users
            (
                role_id,
                first_name,
                last_name,
                email,
                password_hash,
                phone,
                status
            )
            VALUES
            (
                ?, ?, ?, ?, ?, ?, 'Active'
            )";

            $stmt = mysqli_prepare($conn, $sql);

            mysqli_stmt_bind_param(
                $stmt,
                "isssss",
                $role_id,
                $first_name,
                $last_name,
                $email,
                $hashed_password,
                $phone
            );

            if (mysqli_stmt_execute($stmt)) {

                $_SESSION['success'] =
                    "Registration successful. Please login.";

                header("Location: login.php");
                exit();

            } else {

                $error = "Registration failed.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>VendorBridge Registration</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<style>

body{
    background:#f8f9fa;
    font-family:Segoe UI,sans-serif;
}

.left-panel{
    background:linear-gradient(135deg,#1971c2,#2f9e44);
    color:white;
    padding:60px;
    display:flex;
    align-items:center;
}

.left-panel h1{
    font-size:3rem;
    font-weight:700;
    margin-bottom:15px;
}

.left-panel p{
    font-size:1.1rem;
}

.register-card{
    width:100%;
    max-width:500px;
    background:white;
    padding:40px;
    border-radius:18px;
    box-shadow:0 10px 30px rgba(0,0,0,.08);
}

.register-card h2{
    font-weight:700;
}

.form-control,
.form-select{
    height:50px;
}

.btn-primary{
    height:50px;
    border:none;
    background:#1971c2;
}

.btn-primary:hover{
    background:#1864ab;
}

.password-wrapper{
    position:relative;
}

.password-wrapper .form-control{
    padding-right:70px;
}

.show-password-btn{
    position:absolute;
    right:15px;
    top:50%;
    transform:translateY(-50%);
    border:none;
    background:none;
    color:#1971c2;
    font-weight:600;
    cursor:pointer;
}

.show-password-btn:hover{
    color:#1864ab;
}

a{
    text-decoration:none;
}

</style>

</head>

<body>

<div class="container-fluid vh-100">

    <div class="row h-100">

        <!-- LEFT SIDE -->

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

        <!-- RIGHT SIDE -->

        <div class="col-lg-6 d-flex align-items-center justify-content-center">

            <div class="register-card">

                <h2>Create Your Account</h2>

                <p class="text-muted mb-4">
                    Register to access VendorBridge
                </p>

                <?php if(!empty($error)){ ?>

                    <div class="alert alert-danger">
                        <?php echo $error; ?>
                    </div>

                <?php } ?>

                <form method="POST">

                    <div class="row">

                        <div class="col-md-6 mb-3">

                            <label class="form-label">
                                First Name
                            </label>

                            <input
                                type="text"
                                name="first_name"
                                class="form-control"
                                required>

                        </div>

                        <div class="col-md-6 mb-3">

                            <label class="form-label">
                                Last Name
                            </label>

                            <input
                                type="text"
                                name="last_name"
                                class="form-control"
                                required>

                        </div>

                    </div>

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
                            Phone Number
                        </label>

                        <input
                            type="text"
                            name="phone"
                            class="form-control"
                            required>

                    </div>

                    <div class="mb-3">

                        <label class="form-label">
                            Role
                        </label>

                        <select
                            name="role_id"
                            class="form-select"
                            required>

                            <option value="">
                                Select Role
                            </option>

                            <option value="2">
                                Procurement Officer
                            </option>

                            <option value="3">
                                Manager
                            </option>

                            <option value="4">
                                Vendor
                            </option>

                        </select>

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
                                required>

                            <button
                                type="button"
                                class="show-password-btn"
                                onclick="togglePassword('password',this)">
                                Show
                            </button>

                        </div>

                    </div>

                    <div class="mb-3">

                        <label class="form-label">
                            Confirm Password
                        </label>

                        <div class="password-wrapper">

                            <input
                                type="password"
                                id="confirm_password"
                                name="confirm_password"
                                class="form-control"
                                required>

                            <button
                                type="button"
                                class="show-password-btn"
                                onclick="togglePassword('confirm_password',this)">
                                Show
                            </button>

                        </div>

                    </div>

                    <button
                        type="submit"
                        class="btn btn-primary w-100">

                        Register

                    </button>

                </form>

                <div class="text-center mt-4">

                    Already have an account?

                    <a href="login.php">
                        Login
                    </a>

                </div>

            </div>

        </div>

    </div>

</div>

<script>

function togglePassword(fieldId, button){

    let field = document.getElementById(fieldId);

    if(field.type === "password"){
        field.type = "text";
        button.innerHTML = "Hide";
    }else{
        field.type = "password";
        button.innerHTML = "Show";
    }
}

</script>

</body>
</html>