<?php
include 'header.php';

$invoice_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($invoice_id == 0) {
    echo "<div class='alert alert-danger m-4'>Invalid Invoice ID.</div>";
    include 'footer.php'; exit();
}

// 1. Fetch Invoice, PO, and Vendor Details
$invQuery = "
    SELECT i.*, p.po_number, v.company_name, v.address, v.city, v.state, v.gst_number, v.email, v.phone 
    FROM invoices i 
    JOIN purchase_orders p ON i.po_id = p.po_id 
    JOIN vendors v ON i.vendor_id = v.vendor_id 
    WHERE i.invoice_id = $invoice_id
";
$invResult = mysqli_query($conn, $invQuery);

if (mysqli_num_rows($invResult) == 0) {
    echo "<div class='alert alert-warning m-4'>Invoice not found.</div>";
    include 'footer.php'; exit();
}

$inv = mysqli_fetch_assoc($invResult);

// Determine Badge Color
$badgeClass = 'bg-warning text-dark';
if ($inv['payment_status'] == 'Paid') $badgeClass = 'bg-success';
if ($inv['payment_status'] == 'Overdue') $badgeClass = 'bg-danger';

// 2. Fetch Line Items (Joining from PO since this is a 1:1 billing system)
$po_id = $inv['po_id'];
$itemsResult = mysqli_query($conn, "SELECT * FROM purchase_order_items WHERE po_id = $po_id");
?>

<div class="d-flex justify-content-between align-items-center mb-4 no-print">
    <div>
        <a href="invoices.php" class="btn btn-light border shadow-sm me-2"><i class="bi bi-arrow-left"></i> Back</a>
        <h2 class="d-inline-block align-middle mb-0">Tax Invoice</h2>
    </div>
    <div>
        <button onclick="window.print()" class="btn btn-primary shadow-sm"><i class="bi bi-download"></i> Save as PDF</button>
    </div>
</div>

<div class="card shadow-sm border-0" id="print-area">
    <div class="card-body p-5">
        
        <div class="row pb-4 mb-4" style="border-bottom: 2px solid #1971c2;">
            <div class="col-sm-6">
                <h2 class="fw-bold text-primary mb-0"><i class="bi bi-buildings"></i> VendorBridge</h2>
                <p class="text-muted small">Smart Procurement ERP<br>123 Tech Park, Ahmedabad, Gujarat</p>
            </div>
            <div class="col-sm-6 text-end">
                <h1 class="text-uppercase text-muted" style="letter-spacing: 2px;">INVOICE</h1>
                <h4 class="fw-bold text-dark"><?php echo htmlspecialchars($inv['invoice_number']); ?></h4>
                <span class="badge <?php echo $badgeClass; ?> fs-6 mt-1"><?php echo htmlspecialchars($inv['payment_status']); ?></span>
            </div>
        </div>

        <div class="row mb-5">
            <div class="col-sm-6">
                <h6 class="text-muted text-uppercase fw-bold mb-3">Bill To:</h6>
                <h5 class="fw-bold mb-1"><?php echo htmlspecialchars($inv['company_name']); ?></h5>
                <div class="text-muted">
                    <p class="mb-0"><?php echo htmlspecialchars($inv['address'] ?? 'Address not provided'); ?></p>
                    <?php 
                        $city = htmlspecialchars($inv['city'] ?? '');
                        $state = htmlspecialchars($inv['state'] ?? '');
                        if ($city || $state) echo "<p class='mb-0'>" . trim($city . ", " . $state, ", ") . "</p>";
                    ?>
                    <p class="mb-0 mt-1"><strong>GSTIN:</strong> <?php echo htmlspecialchars($inv['gst_number'] ?? 'N/A'); ?></p>
                    <p class="mb-0"><i class="bi bi-envelope"></i> <?php echo htmlspecialchars($inv['email']); ?></p>
                </div>
            </div>
            <div class="col-sm-6 text-end">
                <h6 class="text-muted text-uppercase fw-bold mb-3">Invoice Details:</h6>
                <table class="table table-sm table-borderless text-end mb-0 float-end" style="width: 250px;">
                    <tr><td class="text-muted">Invoice Date:</td><td class="fw-bold"><?php echo date('d M Y', strtotime($inv['invoice_date'])); ?></td></tr>
                    <tr><td class="text-muted">Due Date:</td><td class="fw-bold text-danger"><?php echo date('d M Y', strtotime($inv['due_date'])); ?></td></tr>
                    <tr><td class="text-muted">PO Reference:</td><td class="fw-bold"><?php echo htmlspecialchars($inv['po_number']); ?></td></tr>
                </table>
            </div>
        </div>

        <table class="table table-bordered mb-4">
            <thead style="background-color: #f8f9fa;">
                <tr>
                    <th width="5%">#</th>
                    <th width="50%">Description</th>
                    <th width="15%" class="text-center">Qty</th>
                    <th width="15%" class="text-end">Unit Price (₹)</th>
                    <th width="15%" class="text-end">Total (₹)</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $count = 1;
                if (mysqli_num_rows($itemsResult) > 0) {
                    while ($item = mysqli_fetch_assoc($itemsResult)) { 
                ?>
                    <tr>
                        <td><?php echo $count++; ?></td>
                        <td><?php echo htmlspecialchars($item['product_name']); ?></td>
                        <td class="text-center"><?php echo number_format($item['quantity'], 2); ?></td>
                        <td class="text-end"><?php echo number_format($item['unit_price'], 2); ?></td>
                        <td class="text-end fw-bold"><?php echo number_format($item['total_price'], 2); ?></td>
                    </tr>
                <?php } } else { ?>
                    <tr><td colspan="5" class="text-center py-4 text-muted">No items found for this invoice.</td></tr>
                <?php } ?>
            </tbody>
        </table>

        <div class="row">
            <div class="col-sm-7">
                <div class="p-3 bg-light rounded mt-3">
                    <h6 class="fw-bold text-dark mb-2">Payment Instructions:</h6>
                    <p class="small text-muted mb-0">
                        <strong>Bank:</strong> HDFC Bank Ltd.<br>
                        <strong>Account No:</strong> 01234567891011<br>
                        <strong>IFSC:</strong> HDFC0001234
                    </p>
                </div>
            </div>
            <div class="col-sm-5">
                <table class="table table-sm table-borderless text-end fs-6">
                    <tr>
                        <td class="text-muted">Subtotal:</td>
                        <td class="fw-bold">₹<?php echo number_format($inv['subtotal'], 2); ?></td>
                    </tr>
                    <tr>
                        <td class="text-muted">GST (18%):</td>
                        <td class="fw-bold">₹<?php echo number_format($inv['tax_amount'], 2); ?></td>
                    </tr>
                    <tr class="border-top border-dark border-2 fs-5">
                        <td class="text-dark fw-bold pt-2">Grand Total:</td>
                        <td class="fw-bold text-success pt-2">₹<?php echo number_format($inv['grand_total'], 2); ?></td>
                    </tr>
                </table>
            </div>
        </div>

        <div class="text-center text-muted mt-5 pt-4 border-top small">
            <p>Thank you for your business. This is a computer-generated invoice.</p>
        </div>

    </div>
</div>

<style>
/* PDF / PRINT CSS OVERRIDES */
@media print {
    /* Hide everything except the invoice */
    body * { visibility: hidden; }
    .no-print { display: none !important; }
    
    /* Make the invoice take up the whole page perfectly */
    #print-area, #print-area * { visibility: visible; }
    #print-area { 
        position: absolute; 
        left: 0; 
        top: 0; 
        width: 100%; 
        border: none !important; 
        box-shadow: none !important; 
    }
    
    /* Ensure colors print correctly */
    * { -webkit-print-color-adjust: exact !important; color-adjust: exact !important; }
}
</style>

<?php include 'footer.php'; ?>