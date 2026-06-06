<?php 
include 'header.php';

// get vendor_id
$email = $_SESSION['email'];
$v_query = mysqli_query($conn, "SELECT vendor_id FROM vendors WHERE email = '$email'");
$vendor = mysqli_fetch_assoc($v_query);

if (!$vendor) {
    echo "<div class='alert alert-danger'>Vendor profile not linked. Please contact Admin.</div>";
    include 'footer.php'; exit();
}
$v_id = $vendor['vendor_id'];

// Get RFQs assigned to this vendor
$query = "SELECT r.* FROM rfqs r 
          JOIN rfq_vendor_assignments rva ON r.rfq_id = rva.rfq_id 
          WHERE rva.vendor_id = $v_id AND r.status = 'Open'";
$rfqs = mysqli_query($conn, $query);
?>

<h2>Open RFQs</h2>
<div class="card shadow-sm border-0">
    <table class="table table-hover align-middle mb-0">
        <thead class="table-light"><tr><th>RFQ #</th><th>Title</th><th>Deadline</th><th>Action</th></tr></thead>
        <tbody>
            <?php while($r = mysqli_fetch_assoc($rfqs)) { ?>
            <tr>
                <td><?php echo htmlspecialchars($r['rfq_number']); ?></td>
                <td><?php echo htmlspecialchars($r['title']); ?></td>
                <td><?php echo date('d M Y', strtotime($r['deadline'])); ?></td>
                <td><a href="rfq_details.php?rfq_id=<?php echo $r['rfq_id']; ?>" class="btn btn-sm btn-outline-primary">View Details</a></td>
            </tr>
            <?php } ?>
        </tbody>
    </table>
</div>
<?php include 'footer.php'; ?>