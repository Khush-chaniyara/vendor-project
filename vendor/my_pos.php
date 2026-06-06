<?php 
include 'header.php';
$v_id = mysqli_fetch_assoc(mysqli_query($conn, "SELECT vendor_id FROM vendors WHERE email='{$_SESSION['email']}'"))['vendor_id'];

// Handle Acknowledgment
if (isset($_POST['acknowledge_po'])) {
    $po_id = intval($_POST['po_id']);
    mysqli_query($conn, "UPDATE purchase_orders SET status = 'Confirmed' WHERE po_id = $po_id AND vendor_id = $v_id");
    echo "<script>alert('PO Confirmed Successfully!'); window.location.href='my_pos.php';</script>";
}

$pos = mysqli_query($conn, "SELECT * FROM purchase_orders WHERE vendor_id = $v_id");
?>

<h2>My Purchase Orders</h2>
<div class="card shadow-sm border-0">
    <table class="table table-hover align-middle mb-0">
        <thead class="table-light"><tr><th>PO Number</th><th>Amount</th><th>Status</th><th>Action</th></tr></thead>
        <tbody>
            <?php while($po = mysqli_fetch_assoc($pos)) { ?>
            <tr>
                <td><?php echo htmlspecialchars($po['po_number']); ?></td>
                <td>₹<?php echo number_format($po['total_amount'], 2); ?></td>
                <td><span class="badge <?php echo ($po['status'] == 'Confirmed' ? 'bg-success' : 'bg-warning'); ?>"><?php echo $po['status']; ?></span></td>
                <td>
                    <?php if($po['status'] == 'Sent'): ?>
                        <form method="POST">
                            <input type="hidden" name="po_id" value="<?php echo $po['po_id']; ?>">
                            <button type="submit" name="acknowledge_po" class="btn btn-sm btn-outline-success">Acknowledge PO</button>
                        </form>
                    <?php else: ?>
                        <span class="text-muted small">Confirmed</span>
                    <?php endif; ?>
                </td>
            </tr>
            <?php } ?>
        </tbody>
    </table>
</div>
<?php include 'footer.php'; ?>