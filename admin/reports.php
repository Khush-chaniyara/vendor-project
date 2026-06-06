<?php
include 'header.php';

// 1. Total Spend (Paid Invoices)
$spendQuery = mysqli_fetch_assoc(mysqli_query($conn, "SELECT SUM(grand_total) as total FROM invoices WHERE payment_status='Paid'"));
$totalSpend = $spendQuery['total'] ?? 0;

// 2. Overdue Amount
$overdueQuery = mysqli_fetch_assoc(mysqli_query($conn, "SELECT SUM(grand_total) as total FROM invoices WHERE payment_status='Overdue'"));
$totalOverdue = $overdueQuery['total'] ?? 0;

// 3. PO Fulfillment Rate
$totalPos = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as c FROM purchase_orders"))['c'];
$completedPos = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as c FROM purchase_orders WHERE status='Completed'"))['c'];
$fulfillmentRate = ($totalPos > 0) ? round(($completedPos / $totalPos) * 100) : 0;

// 4. Top Vendors by Spend
$topVendors = mysqli_query($conn, "
    SELECT v.company_name, COUNT(i.invoice_id) as po_count, SUM(i.grand_total) as total_spend 
    FROM invoices i 
    JOIN vendors v ON i.vendor_id = v.vendor_id 
    WHERE i.payment_status = 'Paid' 
    GROUP BY v.vendor_id 
    ORDER BY total_spend DESC LIMIT 5
");
?>

<div class="mb-4">
    <h2>Procurement Insights</h2>
    <span class="fs-6 text-muted"><i class="bi bi-calendar3"></i> Data as of <?php echo date('F Y'); ?></span>
</div>

<div class="row mb-4 g-3">
    <div class="col-md-4">
        <div class="card bg-primary text-white border-0 shadow-sm h-100 p-2">
            <div class="card-body">
                <h6 class="text-uppercase mb-2 text-light"><i class="bi bi-wallet2"></i> Total Paid Spend</h6>
                <h2 class="fw-bold mb-0">₹<?php echo number_format($totalSpend, 2); ?></h2>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card bg-danger text-white border-0 shadow-sm h-100 p-2">
            <div class="card-body">
                <h6 class="text-uppercase mb-2 text-light"><i class="bi bi-exclamation-triangle"></i> Total Overdue Value</h6>
                <h2 class="fw-bold mb-0">₹<?php echo number_format($totalOverdue, 2); ?></h2>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card bg-success text-white border-0 shadow-sm h-100 p-2">
            <div class="card-body">
                <h6 class="text-uppercase mb-2 text-light"><i class="bi bi-truck"></i> PO Fulfillment Rate</h6>
                <h2 class="fw-bold mb-2"><?php echo $fulfillmentRate; ?>%</h2>
                <div class="progress" style="height: 5px;">
                    <div class="progress-bar bg-white" role="progressbar" style="width: <?php echo $fulfillmentRate; ?>%"></div>
                </div>
                <small class="mt-2 d-block text-light"><?php echo $completedPos; ?> out of <?php echo $totalPos; ?> POs completed</small>
            </div>
        </div>
    </div>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-header bg-white py-3 border-bottom">
        <h5 class="mb-0 fw-bold"><i class="bi bi-trophy text-warning"></i> Top Vendors by Spend</h5>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Rank</th>
                        <th>Vendor Name</th>
                        <th>Total Paid Invoices</th>
                        <th>Total Spend Value</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $rank = 1;
                    if (mysqli_num_rows($topVendors) > 0) {
                        while ($tv = mysqli_fetch_assoc($topVendors)) { 
                    ?>
                    <tr>
                        <td class="fw-bold text-muted ps-4">#<?php echo $rank++; ?></td>
                        <td class="fw-bold text-primary"><?php echo htmlspecialchars($tv['company_name']); ?></td>
                        <td><span class="badge bg-light text-dark border"><?php echo $tv['po_count']; ?> Invoices</span></td>
                        <td class="fw-bold text-success">₹<?php echo number_format($tv['total_spend'], 2); ?></td>
                    </tr>
                    <?php } } else { ?>
                        <tr><td colspan="4" class="text-center py-4 text-muted">No paid invoice data available to rank vendors.</td></tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>