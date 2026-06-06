<?php
include 'header.php';
$error = '';

// Handle Form Submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $company_name = trim($_POST['company_name']);
    $category_id = intval($_POST['category_id']);
    $contact_person = trim($_POST['contact_person']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $gst_number = trim($_POST['gst_number']);
    $address = trim($_POST['address']);
    $city = trim($_POST['city']);
    $state = trim($_POST['state']);

    // 1. Check for duplicate Email or GST
    $checkSql = "SELECT vendor_id FROM vendors WHERE email = ? OR gst_number = ?";
    $stmt = mysqli_prepare($conn, $checkSql);
    mysqli_stmt_bind_param($stmt, "ss", $email, $gst_number);
    mysqli_stmt_execute($stmt);
    $checkResult = mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($checkResult) > 0) {
        $error = "A vendor with this Email or GST Number already exists.";
    } else {
        // 2. Insert into Database (Admins instantly create 'Active' vendors)
        $insertSql = "INSERT INTO vendors 
                      (category_id, company_name, gst_number, contact_person, email, phone, address, city, state, vendor_status) 
                      VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, 'Active')";
        
        $insertStmt = mysqli_prepare($conn, $insertSql);
        mysqli_stmt_bind_param($insertStmt, "issssssss", $category_id, $company_name, $gst_number, $contact_person, $email, $phone, $address, $city, $state);
        
        if (mysqli_stmt_execute($insertStmt)) {
            $_SESSION['success'] = "Vendor <strong>$company_name</strong> was successfully added to the directory.";
            // Redirect back to vendors directory to prevent form resubmission
            echo "<script>window.location.href='vendors.php';</script>";
            exit();
        } else {
            $error = "Database Error: " . mysqli_error($conn);
        }
    }
}

// Fetch Vendor Categories for the Dropdown
$catQuery = "SELECT * FROM vendor_categories ORDER BY category_name ASC";
$categories = mysqli_query($conn, $catQuery);
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <a href="vendors.php" class="btn btn-light border shadow-sm me-2"><i class="bi bi-arrow-left"></i> Back</a>
        <h2 class="d-inline-block align-middle mb-0">Register New Vendor</h2>
    </div>
</div>

<?php if (!empty($error)): ?>
    <div class="alert alert-danger border-0 shadow-sm">
        <i class="bi bi-exclamation-triangle-fill"></i> <?php echo $error; ?>
    </div>
<?php endif; ?>

<div class="card shadow-sm border-0">
    <div class="card-body p-4">
        <form method="POST" action="">
            
            <h5 class="text-primary mb-3"><i class="bi bi-building"></i> Company Information</h5>
            <div class="row g-3 mb-4">
                <div class="col-md-6">
                    <label class="form-label fw-bold text-muted small text-uppercase">Company Name *</label>
                    <input type="text" name="company_name" class="form-control" required placeholder="e.g. Tech Solutions Pvt Ltd" value="<?php echo isset($_POST['company_name']) ? htmlspecialchars($_POST['company_name']) : ''; ?>">
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-bold text-muted small text-uppercase">Vendor Category *</label>
                    <select name="category_id" class="form-select" required>
                        <option value="">-- Select Category --</option>
                        <?php while ($cat = mysqli_fetch_assoc($categories)) { ?>
                            <option value="<?php echo $cat['category_id']; ?>" 
                                <?php echo (isset($_POST['category_id']) && $_POST['category_id'] == $cat['category_id']) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($cat['category_name']); ?>
                            </option>
                        <?php } ?>
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-bold text-muted small text-uppercase">GST Number *</label>
                    <input type="text" name="gst_number" class="form-control font-monospace" required placeholder="22AAAAA0000A1Z5" value="<?php echo isset($_POST['gst_number']) ? htmlspecialchars($_POST['gst_number']) : ''; ?>">
                </div>
            </div>

            <h5 class="text-primary mb-3 border-top pt-4"><i class="bi bi-person-badge"></i> Contact Details</h5>
            <div class="row g-3 mb-4">
                <div class="col-md-4">
                    <label class="form-label fw-bold text-muted small text-uppercase">Primary Contact Person *</label>
                    <input type="text" name="contact_person" class="form-control" required placeholder="John Doe" value="<?php echo isset($_POST['contact_person']) ? htmlspecialchars($_POST['contact_person']) : ''; ?>">
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-bold text-muted small text-uppercase">Email Address *</label>
                    <input type="email" name="email" class="form-control" required placeholder="vendor@example.com" value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>">
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-bold text-muted small text-uppercase">Phone Number *</label>
                    <input type="text" name="phone" class="form-control" required placeholder="+91 9876543210" value="<?php echo isset($_POST['phone']) ? htmlspecialchars($_POST['phone']) : ''; ?>">
                </div>
            </div>

            <h5 class="text-primary mb-3 border-top pt-4"><i class="bi bi-geo-alt"></i> Location</h5>
            <div class="row g-3 mb-4">
                <div class="col-12">
                    <label class="form-label fw-bold text-muted small text-uppercase">Street Address</label>
                    <input type="text" name="address" class="form-control" placeholder="123 Industrial Estate..." value="<?php echo isset($_POST['address']) ? htmlspecialchars($_POST['address']) : ''; ?>">
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-bold text-muted small text-uppercase">City</label>
                    <input type="text" name="city" class="form-control" placeholder="Mumbai" value="<?php echo isset($_POST['city']) ? htmlspecialchars($_POST['city']) : ''; ?>">
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-bold text-muted small text-uppercase">State</label>
                    <input type="text" name="state" class="form-control" placeholder="Maharashtra" value="<?php echo isset($_POST['state']) ? htmlspecialchars($_POST['state']) : ''; ?>">
                </div>
            </div>

            <div class="border-top pt-4 text-end">
                <a href="vendors.php" class="btn btn-light border me-2">Cancel</a>
                <button type="submit" class="btn btn-success shadow-sm"><i class="bi bi-save"></i> Save Vendor Profile</button>
            </div>

        </form>
    </div>
</div>

<?php include 'footer.php'; ?>