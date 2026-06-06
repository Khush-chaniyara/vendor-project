<?php
session_start();
require_once "../config.php";

// Security Check
if (!isset($_SESSION['user_id']) || $_SESSION['role_id'] != 1) {
    header("Location: ../login.php"); exit();
}

$quotation_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($quotation_id > 0) {
    // 1. Fetch the Quotation
    $qQuery = mysqli_query($conn, "SELECT * FROM quotations WHERE quotation_id = $quotation_id");
    $quote = mysqli_fetch_assoc($qQuery);

    if ($quote && $quote['status'] != 'Accepted') {
        $rfq_id = $quote['rfq_id'];
        $vendor_id = $quote['vendor_id'];
        $total_amount = $quote['total_amount'];
        $generated_by = $_SESSION['user_id'];
        
        // 2. Generate a random PO Number (e.g., PO-2026-842)
        $po_number = 'PO-' . date('Y') . '-' . str_pad(rand(100, 999), 3, '0', STR_PAD_LEFT);
        $po_date = date('Y-m-d');

        // 3. Insert the new Purchase Order
        $insertPO = "INSERT INTO purchase_orders (quotation_id, vendor_id, generated_by, po_number, po_date, total_amount, status) 
                     VALUES ($quotation_id, $vendor_id, $generated_by, '$po_number', '$po_date', $total_amount, 'Generated')";
        
        if (mysqli_query($conn, $insertPO)) {
            $new_po_id = mysqli_insert_id($conn); // Get the ID of the PO we just made

            // 4. Mark this specific quotation as Accepted
            mysqli_query($conn, "UPDATE quotations SET status = 'Accepted' WHERE quotation_id = $quotation_id");

            // 5. Mark all OTHER quotations for this RFQ as Rejected (Enterprise Logic!)
            mysqli_query($conn, "UPDATE quotations SET status = 'Rejected' WHERE rfq_id = $rfq_id AND quotation_id != $quotation_id");

            // 6. Close the original RFQ
            mysqli_query($conn, "UPDATE rfqs SET status = 'Closed' WHERE rfq_id = $rfq_id");

            // 7. Migrate Line Items (Copies data from Quote to PO)
            $itemsQuery = mysqli_query($conn, "
                SELECT qi.quantity, qi.unit_price, qi.line_total, ri.product_name 
                FROM quotation_items qi 
                JOIN rfq_items ri ON qi.rfq_item_id = ri.rfq_item_id 
                WHERE qi.quotation_id = $quotation_id
            ");

            while ($item = mysqli_fetch_assoc($itemsQuery)) {
                $p_name = mysqli_real_escape_string($conn, $item['product_name']);
                $qty = $item['quantity'];
                $price = $item['unit_price'];
                $total = $item['line_total'];
                
                mysqli_query($conn, "INSERT INTO purchase_order_items (po_id, product_name, quantity, unit_price, total_price) 
                                     VALUES ($new_po_id, '$p_name', $qty, $price, $total)");
            }

            // Set Success Message
            $_SESSION['success'] = "Success! Quotation Accepted. <strong>$po_number</strong> has been officially generated.";
        } else {
            $_SESSION['error'] = "Database Error: " . mysqli_error($conn);
        }
    }
}

// Redirect the user straight to the Invoices/PO screen to see their new document!
header("Location: invoices.php");
exit();
?>