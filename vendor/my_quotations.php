<?php 
include 'header.php';
$v_id = mysqli_fetch_assoc(mysqli_query($conn, "SELECT vendor_id FROM vendors WHERE email='{$_SESSION['email']}'"))['vendor_id'] ?? 0;

$quotes = mysqli_query($conn, "SELECT q.*, r.title FROM quotations q JOIN rfqs r ON q.rfq_id = r.rfq_id WHERE q.vendor_id = $v_id ORDER BY q.submission_date DESC");
?>

<h2>My Quotations</h2>
<div class="card shadow-sm border-0">
    <table class="table table-hover align-middle mb-0">
        <thead class="table-light"><tr><th>Quote #</th><th>RFQ Title</th><th>Amount</th><th>Status</th></tr></thead>
        <tbody>
            <?php while($q = mysqli_fetch_assoc($quotes)) { ?>
            <tr>
                <td><?php echo htmlspecialchars($q['quotation_number']); ?></td>
                <td><?php echo htmlspecialchars($q['title']); ?></td>
                <td>₹<?php echo number_format($q['total_amount'], 2); ?></td>
                <td><span class="badge <?php echo ($q['status'] == 'Accepted' ? 'bg-success' : 'bg-warning'); ?>"><?php echo $q['status']; ?></span></td>
            </tr>
            <?php } ?>
        </tbody>
    </table>
</div>
<?php include 'footer.php'; ?>