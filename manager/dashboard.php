<?php 
include 'header.php'; 

// 1. Get counts
$pending_count = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM quotations WHERE status = 'Submitted'"));
$approved_count = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM quotations WHERE status = 'Accepted'"));
$total_pos = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM purchase_orders"));

// 2. Fetch Recent Activities
$recent_quotes = mysqli_query($conn, "SELECT q.*, v.company_name, r.title 
                                     FROM quotations q 
                                     JOIN vendors v ON q.vendor_id = v.vendor_id 
                                     JOIN rfqs r ON q.rfq_id = r.rfq_id 
                                     WHERE q.status = 'Submitted' 
                                     ORDER BY q.submission_date DESC LIMIT 5");
?>

<div class="container-fluid">
    <h2 class="mb-4">Procurement Command Center</h2>

    <div class="row g-4 mb-4">
        <div class="col-md-4">
            <div class="card p-4 shadow-sm border-0 bg-primary text-white">
                <div class="d-flex align-items-center">
                    <i class="bi bi-clock-history fs-1 me-3"></i>
                    <div>
                        <h4 class="mb-0"><?php echo $pending_count; ?></h4>
                        <span>Pending Approvals</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card p-4 shadow-sm border-0 bg-success text-white">
                <div class="d-flex align-items-center">
                    <i class="bi bi-check2-circle fs-1 me-3"></i>
                    <div>
                        <h4 class="mb-0"><?php echo $approved_count; ?></h4>
                        <span>Quotes Approved</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card p-4 shadow-sm border-0 bg-info text-white">
                <div class="d-flex align-items-center">
                    <i class="bi bi-file-earmark-text fs-1 me-3"></i>
                    <div>
                        <h4 class="mb-0"><?php echo $total_pos; ?></h4>
                        <span>Total POs Issued</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-header bg-white py-3">
            <h5 class="mb-0">Needs Immediate Review</h5>
        </div>
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Vendor</th>
                        <th>RFQ Title</th>
                        <th>Amount</th>
                        <th>Submission Date</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(mysqli_num_rows($recent_quotes) > 0) {
                        while($row = mysqli_fetch_assoc($recent_quotes)) { ?>
                        <tr>
                            <td><strong><?php echo htmlspecialchars($row['company_name']); ?></strong></td>
                            <td><?php echo htmlspecialchars($row['title']); ?></td>
                            <td>₹<?php echo number_format($row['total_amount'], 2); ?></td>
                            <td><?php echo date('d M Y', strtotime($row['submission_date'])); ?></td>
                            <td>
                                <a href="pending_quotes.php" class="btn btn-sm btn-outline-primary">Review</a>
                            </td>
                        </tr>
                    <?php } } else { ?>
                        <tr><td colspan="5" class="text-center text-muted">No pending quotes found.</td></tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>