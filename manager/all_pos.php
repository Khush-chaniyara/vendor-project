<?php 
include 'header.php';

// Fetch all POs
$pos = mysqli_query($conn, "SELECT po.*, v.company_name, q.quotation_number 
                           FROM purchase_orders po 
                           JOIN vendors v ON po.vendor_id = v.vendor_id 
                           JOIN quotations q ON po.quotation_id = q.quotation_id 
                           ORDER BY po.po_date DESC");
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>All Purchase Orders</h2>
    <a href="#" class="btn btn-primary" onclick="window.print()"><i class="bi bi-printer"></i> Print Report</a>
</div>

<div class="card shadow-sm border-0">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>PO Number</th>
                    <th>Vendor</th>
                    <th>Date</th>
                    <th>Amount</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php if(mysqli_num_rows($pos) > 0) {
                    while($po = mysqli_fetch_assoc($pos)) { ?>
                    <tr>
                        <td class="fw-bold"><?php echo htmlspecialchars($po['po_number']); ?></td>
                        <td><?php echo htmlspecialchars($po['company_name']); ?></td>
                        <td><?php echo date('d M Y', strtotime($po['po_date'])); ?></td>
                        <td>₹<?php echo number_format($po['total_amount'], 2); ?></td>
                        <td>
                            <?php 
                            $badge = ($po['status'] == 'Confirmed') ? 'bg-success' : 'bg-warning';
                            echo "<span class='badge $badge'>{$po['status']}</span>";
                            ?>
                        </td>
                    </tr>
                <?php } } else { ?>
                    <tr><td colspan="5" class="text-center text-muted">No Purchase Orders issued yet.</td></tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</div>

<?php include 'footer.php'; ?>