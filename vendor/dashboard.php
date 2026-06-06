<?php 
include 'header.php'; 

// Fetch the vendor 
$vendor_email = $_SESSION['email']; 
$v_query = mysqli_query($conn, "SELECT vendor_id FROM vendors WHERE email = '$vendor_email'");
$vendor = mysqli_fetch_assoc($v_query);

$v_id = $vendor ? $vendor['vendor_id'] : 0;

// Get counts 
$rfq_count = ($v_id > 0) ? mysqli_num_rows(mysqli_query($conn, "SELECT * FROM rfq_vendor_assignments WHERE vendor_id = $v_id")) : 0;
$quote_count = ($v_id > 0) ? mysqli_num_rows(mysqli_query($conn, "SELECT * FROM quotations WHERE vendor_id = $v_id")) : 0;
$po_count = ($v_id > 0) ? mysqli_num_rows(mysqli_query($conn, "SELECT * FROM purchase_orders WHERE vendor_id = $v_id")) : 0;
?>

<div class="mb-4">
    <h2>Welcome, <?php echo htmlspecialchars($_SESSION['first_name']); ?>!</h2>
    <p class="text-muted">Manage your business relationship with VendorBridge.</p>
</div>

<div class="row g-3">
    <div class="col-md-4">
        <a href="open_rfqs.php" class="text-decoration-none">
            <div class="stat-card border-bottom border-primary border-3">
                <h3><?php echo $rfq_count; ?></h3>
                <p>RFQs Assigned</p>
            </div>
        </a>
    </div>
    
    <div class="col-md-4">
        <a href="my_quotations.php" class="text-decoration-none">
            <div class="stat-card border-bottom border-warning border-3">
                <h3><?php echo $quote_count; ?></h3>
                <p>Quotes Submitted</p>
            </div>
        </a>
    </div>
    
    <div class="col-md-4">
        <a href="my_pos.php" class="text-decoration-none">
            <div class="stat-card border-bottom border-success border-3">
                <h3><?php echo $po_count; ?></h3>
                <p>Active POs</p>
            </div>
        </a>
    </div>
</div>

<div class="row mt-4">
    <div class="col-12">
        <div class="card shadow-sm border-0 p-4">
            <h5 class="text-primary mb-3"><i class="bi bi-info-circle"></i> Quick Help</h5>
            <p class="text-muted">Stay updated on your business opportunities. Ensure you review all line items carefully before submitting a quotation, and acknowledge your Purchase Orders immediately upon receipt to expedite your payment cycle.</p>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>
