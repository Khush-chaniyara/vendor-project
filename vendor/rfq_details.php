<?php 
include 'header.php';

// 1. Get the ID from URL Via GET
$rfq_id = isset($_GET['rfq_id']) ? intval($_GET['rfq_id']) : 0;

// 2. Fetch RFQ details
$rfq_data = mysqli_query($conn, "SELECT * FROM rfqs WHERE rfq_id = $rfq_id");
$rfq = mysqli_fetch_assoc($rfq_data);

// 3. Fetch Items - Define this BEFORE the HTML
$items_query = mysqli_query($conn, "SELECT * FROM rfq_items WHERE rfq_id = $rfq_id");

// Check if items were found (Optional Debug)
if (!$items_query) {
    die("Query Failed: " . mysqli_error($conn));
}
?>

<div class="mb-4">
    <a href="open_rfqs.php" class="btn btn-light border"><i class="bi bi-arrow-left"></i> Back</a>
</div>

<div class="card shadow-sm border-0 mb-4">
    <div class="card-body">
        <h3><?php echo htmlspecialchars($rfq['title'] ?? 'RFQ Details'); ?></h3>
        <p class="text-muted"><?php echo htmlspecialchars($rfq['description'] ?? 'No description available.'); ?></p>
        <hr>
        <h5>Requested Items</h5>
        <table class="table mt-3">
            <thead class="table-light">
                <tr><th>Item Name</th><th>Quantity</th><th>Unit</th></tr>
            </thead>
            <tbody>
                <?php while($item = mysqli_fetch_assoc($items_query)) { 
                    $name = $item['product_name'] ?? 'N/A'; 
                    $qty  = $item['quantity'] ?? 0;
                    $unit = $item['unit'] ?? 'N/A';
                ?>
                <tr>
                    <td><?php echo htmlspecialchars($name); ?></td>
                    <td><?php echo htmlspecialchars($qty); ?></td>
                    <td><?php echo htmlspecialchars($unit); ?></td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
        <a href="submit_quote.php?rfq_id=<?php echo $rfq_id; ?>" class="btn btn-primary">Submit Quote for this RFQ</a>
    </div>
</div>

<?php include 'footer.php'; ?>