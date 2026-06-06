<?php
include 'header.php';

// Fetch Quotations with RFQ and Vendor data
$query = "SELECT q.*, r.title, r.rfq_number, r.deadline, v.company_name 
          FROM quotations q 
          JOIN rfqs r ON q.rfq_id = r.rfq_id 
          JOIN vendors v ON q.vendor_id = v.vendor_id 
          ORDER BY q.submission_date DESC";
$quotations = mysqli_query($conn, $query);
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2>Submitted Quotations</h2>
        <span class="text-muted">Review, compare, and approve vendor bids</span>
    </div>
</div>

<div class="row g-4">
    <?php if (mysqli_num_rows($quotations) > 0) {
        while ($q = mysqli_fetch_assoc($quotations)) { 
            // Calculate a mock 18% GST for visual breakdown
            $subtotal = $q['total_amount'] / 1.18; 
            $gst = $q['total_amount'] - $subtotal;
    ?>
    <div class="col-lg-6">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-header bg-white d-flex justify-content-between align-items-center border-bottom py-3">
                <h6 class="mb-0 fw-bold text-primary"><i class="bi bi-file-earmark-text"></i> <?php echo htmlspecialchars($q['rfq_number']); ?></h6>
                <span class="badge <?php echo ($q['status'] == 'Submitted') ? 'bg-warning text-dark' : 'bg-success'; ?>">
                    <?php echo htmlspecialchars($q['status']); ?>
                </span>
            </div>
            <div class="card-body">
                <h5 class="card-title fw-bold text-dark"><?php echo htmlspecialchars($q['title']); ?></h5>
                <p class="text-muted small mb-3">
                    <i class="bi bi-building"></i> Vendor: <strong class="text-dark"><?php echo htmlspecialchars($q['company_name']); ?></strong><br>
                    <i class="bi bi-calendar-event"></i> Deadline: <?php echo date('d M Y', strtotime($q['deadline'])); ?>
                </p>
                
                <div class="bg-light p-3 rounded mb-3 border">
                    <div class="d-flex justify-content-between mb-1 text-muted"><span>Subtotal:</span> <span>₹<?php echo number_format($subtotal, 2); ?></span></div>
                    <div class="d-flex justify-content-between mb-1 text-muted"><span>GST (18%):</span> <span>+ ₹<?php echo number_format($gst, 2); ?></span></div>
                    <hr class="my-2">
                    <div class="d-flex justify-content-between fw-bold fs-5 text-success"><span>Grand Total:</span> <span>₹<?php echo number_format($q['total_amount'], 2); ?></span></div>
                </div>
                
                <div class="small text-muted mb-3">
                    <i class="bi bi-truck"></i> Delivery in <?php echo $q['delivery_days']; ?> days &nbsp;|&nbsp; 
                    <i class="bi bi-shield-check"></i> Terms: Net 20 Days
                </div>
            </div>
            <div class="card-footer bg-white border-top text-end py-3">
                <a href="compare_quotations.php?rfq_id=<?php echo $q['rfq_id']; ?>" class="btn btn-outline-secondary btn-sm me-2">
                <i class="bi bi-bar-chart"></i> Compare </a>
                <?php if ($q['status'] != 'Accepted'): ?>
                    <a href="approve_quotation.php?id=<?php echo $q['quotation_id']; ?>" 
                    class="btn btn-success btn-sm shadow-sm" 
                    onclick="return confirm('Are you sure? This will instantly generate a Purchase Order and reject all other bids for this RFQ.');">
                    <i class="bi bi-check-circle"></i> Approve & Create PO
                    </a>
                <?php else: ?>
                    <button class="btn btn-light text-success border btn-sm disabled">
                        <i class="bi bi-check-all"></i> PO Generated
                    </button>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <?php } } else { ?>
        <div class="col-12"><div class="alert alert-info border-0 shadow-sm">No quotations have been submitted yet.</div></div>
    <?php } ?>
</div>

<?php include 'footer.php'; ?>