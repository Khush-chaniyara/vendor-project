<?php include 'header.php';
$rfq_id = intval($_GET['rfq_id']);
$v_id = mysqli_fetch_assoc(mysqli_query($conn, "SELECT vendor_id FROM vendors WHERE email='{$_SESSION['email']}'"))['vendor_id'];

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $price = floatval($_POST['total_amount']);
    $days = intval($_POST['delivery_days']);
    $q_num = 'QT-' . rand(1000, 9999);
    
    $sql = "INSERT INTO quotations (rfq_id, vendor_id, quotation_number, total_amount, delivery_days, status) 
            VALUES ($rfq_id, $v_id, '$q_num', $price, $days, 'Submitted')";
    
    if(mysqli_query($conn, $sql)) {
        echo "<script>alert('Quotation Submitted!'); window.location.href='my_quotations.php';</script>";
    }
}
?>
<form method="POST" class="card p-4 shadow-sm">
    <h3>Submit Quotation</h3>
    <div class="mb-3"><label>Total Amount (₹)</label><input type="number" name="total_amount" class="form-control" required></div>
    <div class="mb-3"><label>Delivery Days</label><input type="number" name="delivery_days" class="form-control" required></div>
    <button type="submit" class="btn btn-success">Submit Quote</button>
</form>
<?php include 'footer.php'; ?>