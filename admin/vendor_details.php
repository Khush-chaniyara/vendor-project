<?php
include 'header.php';

// 1. Get Vendor ID Via GET
$vendor_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($vendor_id == 0) {
    echo "<div class='alert alert-danger m-4'>Invalid Vendor ID.</div>";
    include 'footer.php';
    exit();
}

// 2. Fetch Vendor Details
$vendorQuery = "
    SELECT v.*, c.category_name 
    FROM vendors v 
    LEFT JOIN vendor_categories c ON v.category_id = c.category_id 
    WHERE v.vendor_id = $vendor_id
";
$vendorResult = mysqli_query($conn, $vendorQuery);

if (mysqli_num_rows($vendorResult) == 0) {
    echo "<div class='alert alert-warning m-4'>Vendor profile not found.</div>";
    include 'footer.php';
    exit();
}

$vendor = mysqli_fetch_assoc($vendorResult);

// Determine Status Badge Color
$badgeClass = 'bg-secondary';
if ($vendor['vendor_status'] == 'Active') $badgeClass = 'bg-success';
if ($vendor['vendor_status'] == 'Pending') $badgeClass = 'bg-warning text-dark';
if ($vendor['vendor_status'] == 'Blacklisted') $badgeClass = 'bg-danger';

// 3. Fetch Recent Purchase Orders for this specific Vendor
$poQuery = "SELECT * FROM purchase_orders WHERE vendor_id = $vendor_id ORDER BY po_date DESC LIMIT 5";
$poResult = mysqli_query($conn, $poQuery);
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <a href="vendors.php" class="btn btn-light border shadow-sm me-2"><i class="bi bi-arrow-left"></i> Back</a>
        <h2 class="d-inline-block align-middle mb-0">Vendor Profile</h2>
    </div>
    <div>
        <button class="btn btn-outline-dark shadow-sm"><i class="bi bi-pencil"></i> Edit Profile</button>
    </div>
</div>

<div class="row g-4">
    <div class="col-lg-4">
        <div class="card shadow-sm border-0 mb-4 h-100">
            <div class="card-body text-center p-4">
                <div class="bg-light rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px;">
                    <i class="bi bi-building fs-1 text-primary"></i>
                </div>
                <h4 class="fw-bold mb-1"><?php echo htmlspecialchars($vendor['company_name']); ?></h4>
                <p class="text-muted mb-2"><?php echo htmlspecialchars($vendor['category_name'] ?? 'Uncategorized'); ?></p>
                <span class="badge <?php echo $badgeClass; ?> mb-4"><?php echo htmlspecialchars($vendor['vendor_status']); ?></span>
                
                <hr class="text-muted">
                
                <div class="text-start mt-4">
                    <h6 class="fw-bold text-muted text-uppercase small mb-3">Company Identifiers</h6>
                    <p class="mb-2"><i class="bi bi-upc-scan text-muted me-2"></i> <strong>GST:</strong> <span class="font-monospace"><?php echo htmlspecialchars($vendor['gst_number'] ?? 'N/A'); ?></span></p>
                    <p class="mb-0"><i class="bi bi-calendar-check text-muted me-2"></i> <strong>Registered:</strong> <?php echo date('d M Y', strtotime($vendor['created_at'])); ?></p>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-8">
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-body p-4">
                <h5 class="text-primary mb-4 border-bottom pb-2"><i class="bi bi-person-lines-fill"></i> Contact Information</h5>
                
                <div class="row mb-4">
                    <div class="col-sm-6">
                        <p class="text-muted small text-uppercase fw-bold mb-1">Primary Contact</p>
                        <p class="fw-bold fs-6 mb-3"><?php echo htmlspecialchars($vendor['contact_person']); ?></p>
                        
                        <p class="text-muted small text-uppercase fw-bold mb-1">Email Address</p>
                        <p class="mb-0"><a href="mailto:<?php echo htmlspecialchars($vendor['email']); ?>" class="text-decoration-none"><?php echo htmlspecialchars($vendor['email']); ?></a></p>
                    </div>
                    <div class="col-sm-6 border-start ps-4">
                        <p class="text-muted small text-uppercase fw-bold mb-1">Phone Number</p>
                        <p class="mb-3"><?php echo htmlspecialchars($vendor['phone']); ?></p>
                        
                        <p class="text-muted small text-uppercase fw-bold mb-1">Registered Address</p>
                        <p class="mb-0">
                            <?php 
                                // The ?? '' tells PHP to output a blank string if the DB value is NULL
                                echo htmlspecialchars($vendor['address'] ?? 'Address not provided') . "<br>";
                                
                                $city = htmlspecialchars($vendor['city'] ?? '');
                                $state = htmlspecialchars($vendor['state'] ?? '');
                                
                                // Only print the comma if city or state exists
                                if ($city || $state) {
                                    echo trim($city . ", " . $state, ", ");
                                }
                            ?>
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <div class="card shadow-sm border-0">
            <div class="card-header bg-white py-3 border-bottom d-flex justify-content-between align-items-center">
                <h6 class="mb-0 fw-bold text-primary"><i class="bi bi-receipt-cutoff"></i> Recent Purchase Orders</h6>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>PO Number</th>
                                <th>Date</th>
                                <th>Amount</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (mysqli_num_rows($poResult) > 0) {
                                while ($po = mysqli_fetch_assoc($poResult)) { 
                                    $poBadge = 'bg-secondary';
                                    if (in_array($po['status'], ['Generated', 'Sent'])) $poBadge = 'bg-warning text-dark';
                                    if (in_array($po['status'], ['Accepted', 'Completed'])) $poBadge = 'bg-success';
                                    if ($po['status'] == 'Cancelled') $poBadge = 'bg-danger';
                            ?>
                                <tr>
                                    <td class="fw-bold"><a href="po_details.php?id=<?php echo $po['po_id']; ?>" class="text-decoration-none"><?php echo htmlspecialchars($po['po_number']); ?></a></td>
                                    <td><?php echo date('d M Y', strtotime($po['po_date'])); ?></td>
                                    <td class="fw-bold">₹<?php echo number_format($po['total_amount'], 2); ?></td>
                                    <td><span class="badge <?php echo $poBadge; ?>"><?php echo htmlspecialchars($po['status']); ?></span></td>
                                </tr>
                            <?php } } else { ?>
                                <tr><td colspan="4" class="text-center py-4 text-muted">No purchase orders found for this vendor yet.</td></tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        
    </div>
</div>

<?php include 'footer.php'; ?>
