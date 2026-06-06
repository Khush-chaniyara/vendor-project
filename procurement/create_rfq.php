<?php
include 'header.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $deadline = $_POST['deadline'];
    $rfq_number = 'RFQ-' . date('Y') . '-' . str_pad(rand(100, 999), 3, '0', STR_PAD_LEFT);
    
    // 1. Insert Main RFQ
    $insertRfq = "INSERT INTO rfqs (rfq_number, title, description, created_by, deadline, status) 
                  VALUES ('$rfq_number', '$title', '$description', $user_id, '$deadline', 'Open')";
    
    if (mysqli_query($conn, $insertRfq)) {
        $rfq_id = mysqli_insert_id($conn);
        
        // 2. Insert Multiple Items
        $product_names = $_POST['product_name'];
        $quantities = $_POST['quantity'];
        $units = $_POST['unit'];
        
        for ($i = 0; $i < count($product_names); $i++) {
            $p_name = mysqli_real_escape_string($conn, $product_names[$i]);
            $qty = floatval($quantities[$i]);
            $unit = mysqli_real_escape_string($conn, $units[$i]);
            
            if (!empty($p_name) && $qty > 0) {
                mysqli_query($conn, "INSERT INTO rfq_items (rfq_id, product_name, quantity, unit) VALUES ($rfq_id, '$p_name', $qty, '$unit')");
            }
        }
        $_SESSION['success'] = "RFQ <strong>$rfq_number</strong> has been successfully published!";
        echo "<script>window.location.href='rfqs.php';</script>"; exit();
    }
}
?>

<div class="mb-4">
    <a href="rfqs.php" class="btn btn-light border shadow-sm me-2"><i class="bi bi-arrow-left"></i> Back</a>
    <h2 class="d-inline-block align-middle mb-0">Create Request for Quotation</h2>
</div>

<form method="POST" action="">
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-body p-4">
            <h5 class="text-primary mb-3">General Details</h5>
            <div class="row g-3">
                <div class="col-md-8">
                    <label class="form-label fw-bold small text-muted">RFQ Title *</label>
                    <input type="text" name="title" class="form-control" required placeholder="e.g. Procurement of 50 Laptops">
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-bold small text-muted">Submission Deadline *</label>
                    <input type="datetime-local" name="deadline" class="form-control" required>
                </div>
                <div class="col-12">
                    <label class="form-label fw-bold small text-muted">Description / Terms</label>
                    <textarea name="description" class="form-control" rows="3" placeholder="Additional requirements..."></textarea>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow-sm border-0 mb-4">
        <div class="card-body p-4">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="text-primary mb-0">Required Items</h5>
                <button type="button" class="btn btn-sm btn-outline-dark" onclick="addItem()"><i class="bi bi-plus-lg"></i> Add Item Row</button>
            </div>
            
            <table class="table table-bordered" id="itemsTable">
                <thead class="table-light">
                    <tr><th>Product Name / Description *</th><th width="15%">Quantity *</th><th width="15%">Unit *</th><th width="5%"></th></tr>
                </thead>
                <tbody id="itemBody">
                    <tr>
                        <td><input type="text" name="product_name[]" class="form-control" required></td>
                        <td><input type="number" name="quantity[]" class="form-control" step="0.01" required></td>
                        <td><input type="text" name="unit[]" class="form-control" placeholder="Nos, Kg, Ltr" required></td>
                        <td></td> <!-- First row can't be deleted -->
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    <div class="text-end mb-5">
        <button type="submit" class="btn btn-success shadow-sm btn-lg" style="background-color: #0c8599; border:none;"><i class="bi bi-send"></i> Publish RFQ</button>
    </div>
</form>

<script>
function addItem() {
    let row = `<tr>
        <td><input type="text" name="product_name[]" class="form-control" required></td>
        <td><input type="number" name="quantity[]" class="form-control" step="0.01" required></td>
        <td><input type="text" name="unit[]" class="form-control" placeholder="Nos, Kg, Ltr" required></td>
        <td><button type="button" class="btn btn-sm btn-danger" onclick="this.closest('tr').remove()"><i class="bi bi-trash"></i></button></td>
    </tr>`;
    document.getElementById('itemBody').insertAdjacentHTML('beforeend', row);
}
</script>

<?php include 'footer.php'; ?>