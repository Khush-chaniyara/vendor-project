<?php
include 'header.php';

$query = "SELECT i.*, p.po_number, v.company_name 
          FROM invoices i 
          JOIN purchase_orders p ON i.po_id = p.po_id 
          JOIN vendors v ON i.vendor_id = v.vendor_id 
          WHERE p.generated_by = $user_id 
          ORDER BY i.created_at DESC";
$invoices = mysqli_query($conn, $query);
?>

<div class="mb-4">
    <h2>Supplier Invoices</h2>
    <p class="text-muted">Track billing for your generated Purchase Orders.</p>
</div>

<div class="card shadow-sm border-0">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>Invoice #</th>
                    <th>PO Ref</th>
                    <th>Vendor</th>
                    <th>Amount</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php if (mysqli_num_rows($invoices) > 0) { 
                    while ($inv = mysqli_fetch_assoc($invoices)) { 
                    $badge = ($inv['payment_status'] == 'Paid') ? 'bg-success' : (($inv['payment_status'] == 'Overdue') ? 'bg-danger' : 'bg-warning text-dark');
                ?>
                <tr>
                    <td class="fw-bold text-primary"><i class="bi bi-receipt"></i> <?php echo htmlspecialchars($inv['invoice_number']); ?></td>
                    <td><?php echo htmlspecialchars($inv['po_number']); ?></td>
                    <td class="fw-bold"><?php echo htmlspecialchars($inv['company_name']); ?></td>
                    <td>₹<?php echo number_format($inv['grand_total'], 2); ?></td>
                    <td><span class="badge <?php echo $badge; ?>"><?php echo htmlspecialchars($inv['payment_status']); ?></span></td>
                    <td>
                        <a href="invoice_details.php?id=<?php echo $inv['invoice_id']; ?>" target="_blank" class="btn btn-sm btn-outline-dark" title="View & Download PDF">
                            <i class="bi bi-file-earmark-pdf"></i> View / Print
                        </a>
                    </td>
                </tr>
                <?php } } else { ?>
                    <tr><td colspan="6" class="text-center py-4 text-muted">No invoices received yet.</td></tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</div>

<?php include 'footer.php'; ?>