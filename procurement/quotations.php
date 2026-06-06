<?php
include 'header.php';
$query = "SELECT q.*, r.title, r.rfq_number, v.company_name FROM quotations q 
          JOIN rfqs r ON q.rfq_id = r.rfq_id 
          JOIN vendors v ON q.vendor_id = v.vendor_id 
          WHERE r.created_by = $user_id ORDER BY q.submission_date DESC";
$quotations = mysqli_query($conn, $query);
?>

<div class="mb-4"><h2>Evaluate Bids</h2><p class="text-muted">Review vendor quotes submitted against your RFQs.</p></div>

<div class="card shadow-sm border-0">
    <table class="table table-hover align-middle mb-0">
        <thead class="table-light"><tr><th>RFQ Ref</th><th>Vendor</th><th>Total Amount</th><th>Delivery</th><th>Status</th></tr></thead>
        <tbody>
            <?php while ($q = mysqli_fetch_assoc($quotations)) { 
                $statusClass = ($q['status'] == 'Submitted') ? 'bg-warning text-dark' : (($q['status'] == 'Accepted') ? 'bg-success' : 'bg-danger');
            ?>
            <tr>
                <td class="fw-bold text-primary"><?php echo htmlspecialchars($q['rfq_number']); ?></td>
                <td><?php echo htmlspecialchars($q['company_name']); ?></td>
                <td class="fw-bold">₹<?php echo number_format($q['total_amount'], 2); ?></td>
                <td><?php echo $q['delivery_days']; ?> Days</td>
                <td><span class="badge <?php echo $statusClass; ?>"><?php echo htmlspecialchars($q['status']); ?></span></td>
            </tr>
            <?php } ?>
        </tbody>
    </table>
</div>
<?php include 'footer.php'; ?>