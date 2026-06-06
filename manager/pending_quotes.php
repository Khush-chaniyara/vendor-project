<?php 
include 'header.php';

// 1. Handle Approval/Rejection 
if (isset($_GET['action']) && isset($_GET['q_id'])) {
    $q_id = intval($_GET['q_id']);
    $action = $_GET['action'];
    
    if ($action == 'accept') {
        mysqli_query($conn, "UPDATE quotations SET status = 'Accepted' WHERE quotation_id = $q_id");
        $q = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM quotations WHERE quotation_id = $q_id"));       
            $po_num = "PO-" . strtoupper(substr(uniqid(), -6));
            $manager_id = $_SESSION['user_id']; 
            mysqli_query($conn, "INSERT INTO purchase_orders (quotation_id, vendor_id, po_number, total_amount, status, po_date, generated_by) 
            VALUES ($q_id, {$q['vendor_id']}, '$po_num', {$q['total_amount']}, 'Sent', CURDATE(), $manager_id)");                            
    } elseif ($action == 'reject') {
        mysqli_query($conn, "UPDATE quotations SET status = 'Rejected' WHERE quotation_id = $q_id");
    }
    
    echo "<script>window.location.href='pending_quotes.php';</script>";
}

$quotes = mysqli_query($conn, "SELECT q.*, v.company_name, r.title, r.rfq_number 
                               FROM quotations q 
                               JOIN vendors v ON q.vendor_id = v.vendor_id 
                               JOIN rfqs r ON q.rfq_id = r.rfq_id 
                               WHERE q.status = 'Submitted' 
                               ORDER BY q.submission_date DESC");
?>

<h2>Pending Approvals</h2>
<p class="text-muted">Review vendor submissions below. Approving a quote will automatically generate a Purchase Order.</p>

<div class="card shadow-sm border-0">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>Vendor</th>
                    <th>RFQ #</th>
                    <th>Title</th>
                    <th>Quoted Amount</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if(mysqli_num_rows($quotes) > 0) {
                    while($q = mysqli_fetch_assoc($quotes)) { ?>
                    <tr>
                        <td><strong><?php echo htmlspecialchars($q['company_name']); ?></strong></td>
                        <td><?php echo htmlspecialchars($q['rfq_number']); ?></td>
                        <td><?php echo htmlspecialchars($q['title']); ?></td>
                        <td>₹<?php echo number_format($q['total_amount'], 2); ?></td>
                        <td>
                            <a href="?action=accept&q_id=<?php echo $q['quotation_id']; ?>" 
                               class="btn btn-sm btn-success" onclick="return confirm('Approve this quote?')">
                               <i class="bi bi-check-lg"></i> Approve
                            </a>
                            <a href="?action=reject&q_id=<?php echo $q['quotation_id']; ?>" 
                               class="btn btn-sm btn-danger" onclick="return confirm('Reject this quote?')">
                               <i class="bi bi-x-lg"></i> Reject
                            </a>
                        </td>
                    </tr>
                <?php } } else { ?>
                    <tr><td colspan="5" class="text-center text-muted">No pending quotes to review.</td></tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</div>

<?php include 'footer.php'; ?>