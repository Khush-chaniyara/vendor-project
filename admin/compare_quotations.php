<?php
include 'header.php';

$rfq_id = isset($_GET['rfq_id']) ? intval($_GET['rfq_id']) : 0;

// Fetch RFQ Details
$rfqResult = mysqli_query($conn, "SELECT * FROM rfqs WHERE rfq_id = $rfq_id");
$rfq = mysqli_fetch_assoc($rfqResult);

// Fetch All Quotes for this RFQ (Ordered by cheapest first!)
$quotesResult = mysqli_query($conn, "
    SELECT q.*, v.company_name, v.vendor_status 
    FROM quotations q 
    JOIN vendors v ON q.vendor_id = v.vendor_id 
    WHERE q.rfq_id = $rfq_id 
    ORDER BY q.total_amount ASC
");
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <a href="quotations.php" class="btn btn-light border shadow-sm me-2"><i class="bi bi-arrow-left"></i> Back</a>
        <h2 class="d-inline-block align-middle mb-0">Quotation Comparison</h2>
    </div>
</div>

<div class="alert alert-primary border-0 shadow-sm mb-4">
    <h5 class="alert-heading fw-bold"><i class="bi bi-tags"></i> <?php echo htmlspecialchars($rfq['rfq_number']); ?> - <?php echo htmlspecialchars($rfq['title']); ?></h5>
    <p class="mb-0">Compare vendor bids side-by-side to find the most cost-effective procurement solution.</p>
</div>

<div class="row row-cols-1 row-cols-md-3 g-4">
    <?php 
    $rank = 1;
    while ($q = mysqli_fetch_assoc($quotesResult)) { 
        // Add a "Best Price" badge to the cheapest quote
        $isBestPrice = ($rank == 1) ? true : false;
    ?>
    <div class="col">
        <div class="card h-100 shadow-sm <?php echo $isBestPrice ? 'border-success border-2' : 'border-0'; ?>">
            <?php if ($isBestPrice): ?>
                <div class="bg-success text-white text-center py-1 fw-bold small text-uppercase">
                    <i class="bi bi-trophy-fill"></i> Lowest Bid
                </div>
            <?php endif; ?>
            
            <div class="card-body text-center p-4">
                <h5 class="fw-bold text-dark mb-1"><?php echo htmlspecialchars($q['company_name']); ?></h5>
                <span class="badge bg-light text-dark border mb-3">Quote: <?php echo htmlspecialchars($q['quotation_number']); ?></span>
                
                <h2 class="fw-bold <?php echo $isBestPrice ? 'text-success' : 'text-primary'; ?> mb-3">
                    ₹<?php echo number_format($q['total_amount'], 2); ?>
                </h2>
                
                <ul class="list-unstyled text-start mb-4 text-muted border-top pt-3">
                    <li class="mb-2"><i class="bi bi-truck me-2"></i> <strong>Delivery:</strong> <?php echo $q['delivery_days']; ?> Days</li>
                    <li class="mb-2"><i class="bi bi-calendar-check me-2"></i> <strong>Submitted:</strong> <?php echo date('d M Y', strtotime($q['submission_date'])); ?></li>
                    <li><i class="bi bi-info-circle me-2"></i> <strong>Status:</strong> <?php echo htmlspecialchars($q['status']); ?></li>
                </ul>
                
                <?php if ($q['status'] != 'Accepted'): ?>
                    <a href="approve_quotation.php?id=<?php echo $q['quotation_id']; ?>" class="btn btn-success w-100 shadow-sm" onclick="return confirm('Are you sure you want to approve this vendor?');">
                        Approve & Select
                    </a>
                <?php else: ?>
                    <button class="btn btn-light text-success border w-100 disabled"><i class="bi bi-check-all"></i> Approved</button>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <?php 
        $rank++; 
    } 
    ?>
</div>

<?php include 'footer.php'; ?>