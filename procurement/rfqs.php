<?php
include 'header.php';
$query = "SELECT r.*, (SELECT COUNT(*) FROM quotations q WHERE q.rfq_id = r.rfq_id) as bid_count 
          FROM rfqs r WHERE r.created_by = $user_id ORDER BY r.created_at DESC";
$rfqs = mysqli_query($conn, $query);
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Manage RFQs</h2>
    <a href="create_rfq.php" class="btn btn-primary shadow-sm" style="background-color: #0c8599; border:none;"><i class="bi bi-plus-circle"></i> Create New RFQ</a>
</div>

<?php if(isset($_SESSION['success'])): ?>
    <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm">
        <i class="bi bi-check-circle-fill me-2"></i> <?php echo $_SESSION['success']; unset($_SESSION['success']); ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<?php if(isset($_SESSION['error'])): ?>
    <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm">
        <i class="bi bi-exclamation-triangle-fill me-2"></i> <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<div class="card shadow-sm border-0">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>RFQ Number</th>
                    <th>Title</th>
                    <th>Deadline</th>
                    <th>Bids Received</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($r = mysqli_fetch_assoc($rfqs)) { 
                    $badge = ($r['status'] == 'Open') ? 'bg-primary' : (($r['status'] == 'Draft') ? 'bg-warning text-dark' : 'bg-dark');
                ?>
                <tr>
                    <td class="fw-bold text-primary"><i class="bi bi-tag text-muted"></i> <?php echo htmlspecialchars($r['rfq_number']); ?></td>
                    <td><?php echo htmlspecialchars($r['title']); ?></td>
                    <td><?php echo date('d M Y, h:i A', strtotime($r['deadline'])); ?></td>
                    <td><span class="badge bg-light text-dark border"><?php echo $r['bid_count']; ?> Bids</span></td>
                    <td><span class="badge <?php echo $badge; ?>"><?php echo htmlspecialchars($r['status']); ?></span></td>
                    <td>
                        <!-- Show buttons ONLY if Open or Draft -->
                        <?php if(in_array($r['status'], ['Open', 'Draft'])): ?>
                            <a href="edit_rfq.php?id=<?php echo $r['rfq_id']; ?>" class="btn btn-sm btn-outline-dark" title="Edit RFQ">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <a href="delete_rfq.php?id=<?php echo $r['rfq_id']; ?>" class="btn btn-sm btn-outline-danger" title="Delete RFQ" onclick="return confirm('Are you sure you want to completely delete this RFQ? This action cannot be undone.');">
                                <i class="bi bi-trash"></i>
                            </a>
                        <?php else: ?>
                            <button class="btn btn-sm btn-light text-muted border disabled"><i class="bi bi-lock"></i> Locked</button>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</div>
<?php include 'footer.php'; ?>