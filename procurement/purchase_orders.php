<?php
include 'header.php';
$query = "SELECT po.*, v.company_name FROM purchase_orders po 
          JOIN vendors v ON po.vendor_id = v.vendor_id 
          WHERE po.generated_by = $user_id ORDER BY po.po_date DESC";
$pos = mysqli_query($conn, $query);
?>

<div class="mb-4"><h2>My Purchase Orders</h2></div>

<div class="card shadow-sm border-0">
    <table class="table table-hover align-middle mb-0">
        <thead class="table-light"><tr><th>PO Number</th><th>Vendor</th><th>Date</th><th>Amount</th><th>Status</th></tr></thead>
        <tbody>
            <?php while ($po = mysqli_fetch_assoc($pos)) { 
                $badge = in_array($po['status'], ['Generated', 'Sent']) ? 'bg-warning text-dark' : 'bg-success';
            ?>
            <tr>
                <td class="fw-bold text-primary"><?php echo htmlspecialchars($po['po_number']); ?></td>
                <td><?php echo htmlspecialchars($po['company_name']); ?></td>
                <td><?php echo date('d M Y', strtotime($po['po_date'])); ?></td>
                <td class="fw-bold">₹<?php echo number_format($po['total_amount'], 2); ?></td>
                <td><span class="badge <?php echo $badge; ?>"><?php echo htmlspecialchars($po['status']); ?></span></td>
            </tr>
            <?php } ?>
        </tbody>
    </table>
</div>
<?php include 'footer.php'; ?>