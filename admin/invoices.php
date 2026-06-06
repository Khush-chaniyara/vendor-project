<?php
include 'header.php';

// Fetch Invoices
$query = "SELECT i.*, p.po_number, v.company_name 
          FROM invoices i 
          JOIN purchase_orders p ON i.po_id = p.po_id 
          JOIN vendors v ON i.vendor_id = v.vendor_id 
          ORDER BY i.created_at DESC";
$invoices = mysqli_query($conn, $query);
?>

<div class="mb-4">
    <h2>Purchase Orders & Invoices</h2>
    <span class="text-muted">Manage billing, print invoices, and update payment statuses.</span>
</div>

<div class="card shadow-sm border-0">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>Invoice #</th>
                    <th>PO Ref</th>
                    <th>Vendor</th>
                    <th>Due Date</th>
                    <th>Grand Total</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (mysqli_num_rows($invoices) > 0) {
                    while ($inv = mysqli_fetch_assoc($invoices)) { 
                    $statusClass = 'bg-warning text-dark';
                    if ($inv['payment_status'] == 'Paid') $statusClass = 'bg-success';
                    if ($inv['payment_status'] == 'Overdue') $statusClass = 'bg-danger';
                ?>
                <tr>
                    <td class="fw-bold"><i class="bi bi-receipt text-primary"></i> <?php echo htmlspecialchars($inv['invoice_number']); ?></td>
                    <td class="text-muted"><?php echo htmlspecialchars($inv['po_number']); ?></td>
                    <td class="fw-bold"><?php echo htmlspecialchars($inv['company_name']); ?></td>
                    <td><?php echo date('d M Y', strtotime($inv['due_date'])); ?></td>
                    <td class="fw-bold fs-6">₹<?php echo number_format($inv['grand_total'], 2); ?></td>
                    <td><span class="badge <?php echo $statusClass; ?>"><?php echo htmlspecialchars($inv['payment_status']); ?></span></td>
                    <td>
                        <a href="invoice_details.php?id=<?php echo $inv['invoice_id']; ?>" target="_blank" class="btn btn-sm btn-outline-dark me-1" title="View & Download PDF">
                            <i class="bi bi-file-earmark-pdf"></i>
                        </a>
                        
                        <?php if($inv['payment_status'] != 'Paid') { ?>
                            <a href="mark_invoice_paid.php?id=<?php echo $inv['invoice_id']; ?>" class="btn btn-sm btn-success shadow-sm" onclick="return confirm('Are you sure you want to mark this invoice as PAID? This action cannot be undone.');">
                                <i class="bi bi-currency-rupee"></i> Mark Paid
                            </a>
                        <?php } else { ?>
                            <button class="btn btn-sm btn-light text-success border disabled"><i class="bi bi-check-all"></i> Cleared</button>
                        <?php } ?>
                    </td>
                </tr>
                <?php } } else { ?>
                    <tr><td colspan="7" class="text-center py-4 text-muted">No invoices generated yet.</td></tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</div>

<?php include 'footer.php'; ?>