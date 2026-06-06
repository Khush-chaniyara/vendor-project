<?php
include 'header.php';

$rfq_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

$query = "SELECT * FROM rfqs WHERE rfq_id = $rfq_id AND created_by = $user_id AND status IN ('Open', 'Draft')";
$result = mysqli_query($conn, $query);

if (mysqli_num_rows($result) == 0) {
    $_SESSION['error'] = "RFQ not found or is locked from editing.";
    echo "<script>window.location.href='rfqs.php';</script>"; exit();
}

$rfq = mysqli_fetch_assoc($result);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $deadline = $_POST['deadline'];
    $status = mysqli_real_escape_string($conn, $_POST['status']);

    $updateSql = "UPDATE rfqs SET title = '$title', description = '$description', deadline = '$deadline', status = '$status' WHERE rfq_id = $rfq_id";
    
    if (mysqli_query($conn, $updateSql)) {
        $_SESSION['success'] = "RFQ <strong>" . $rfq['rfq_number'] . "</strong> has been successfully updated!";
        echo "<script>window.location.href='rfqs.php';</script>"; exit();
    } else {
        $error = "Database Error: " . mysqli_error($conn);
    }
}
?>

<div class="mb-4">
    <a href="rfqs.php" class="btn btn-light border shadow-sm me-2"><i class="bi bi-arrow-left"></i> Back</a>
    <h2 class="d-inline-block align-middle mb-0">Edit RFQ: <?php echo htmlspecialchars($rfq['rfq_number']); ?></h2>
</div>

<?php if(isset($error)): ?>
    <div class="alert alert-danger border-0 shadow-sm"><i class="bi bi-exclamation-triangle"></i> <?php echo $error; ?></div>
<?php endif; ?>

<form method="POST" action="">
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-body p-4">
            <h5 class="text-primary mb-3"><i class="bi bi-pencil-square"></i> Update General Details</h5>
            <div class="row g-3">
                <div class="col-md-8">
                    <label class="form-label fw-bold small text-muted">RFQ Title *</label>
                    <input type="text" name="title" class="form-control" required value="<?php echo htmlspecialchars($rfq['title']); ?>">
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-bold small text-muted">Submission Deadline *</label>
                    <!-- Formatting the datetime string correctly for the input field -->
                    <input type="datetime-local" name="deadline" class="form-control" required value="<?php echo date('Y-m-d\TH:i', strtotime($rfq['deadline'])); ?>">
                </div>
                <div class="col-12">
                    <label class="form-label fw-bold small text-muted">Description / Terms</label>
                    <textarea name="description" class="form-control" rows="3"><?php echo htmlspecialchars($rfq['description']); ?></textarea>
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-bold small text-muted">RFQ Status</label>
                    <select name="status" class="form-select">
                        <option value="Open" <?php echo ($rfq['status'] == 'Open') ? 'selected' : ''; ?>>Open (Visible to vendors)</option>
                        <option value="Draft" <?php echo ($rfq['status'] == 'Draft') ? 'selected' : ''; ?>>Draft (Hidden)</option>
                        <option value="Closed" <?php echo ($rfq['status'] == 'Closed') ? 'selected' : ''; ?>>Closed (Stop receiving bids)</option>
                    </select>
                </div>
            </div>
        </div>
    </div>

    <div class="text-end mb-5">
        <a href="rfqs.php" class="btn btn-light border me-2">Cancel</a>
        <button type="submit" class="btn btn-success shadow-sm" style="background-color: #0c8599; border:none;"><i class="bi bi-save"></i> Save Changes</button>
    </div>
</form>

<?php include 'footer.php'; ?>