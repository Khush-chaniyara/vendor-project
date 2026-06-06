<?php
// Include the global header (This starts the session and connects to the DB)
include 'header.php';

// 1. Total Active RFQs (Status = 'Open')
$activeRfqsQuery = mysqli_query($conn, "SELECT COUNT(*) as count FROM rfqs WHERE status = 'Open'");
$activeRfqs = mysqli_fetch_assoc($activeRfqsQuery)['count'] ?? 0;

// 2. Pending RFQs (Status = 'Draft')
$pendingRfqsQuery = mysqli_query($conn, "SELECT COUNT(*) as count FROM rfqs WHERE status = 'Draft'");
$pendingRfqs = mysqli_fetch_assoc($pendingRfqsQuery)['count'] ?? 0;

// 3. Total POs
$totalPosQuery = mysqli_query($conn, "SELECT COUNT(*) as count FROM purchase_orders");
$totalPos = mysqli_fetch_assoc($totalPosQuery)['count'] ?? 0;

// 4. POs This Month
$posThisMonthQuery = mysqli_query($conn, "
    SELECT COUNT(*) as count 
    FROM purchase_orders 
    WHERE MONTH(po_date) = MONTH(CURRENT_DATE()) 
    AND YEAR(po_date) = YEAR(CURRENT_DATE())
");
$posThisMonth = mysqli_fetch_assoc($posThisMonthQuery)['count'] ?? 0;

// 5. Overdue Invoices
$overdueInvoicesQuery = mysqli_query($conn, "
    SELECT COUNT(*) as count 
    FROM invoices 
    WHERE payment_status = 'Overdue' 
    OR (due_date < CURRENT_DATE() AND payment_status != 'Paid')
");
$overdueInvoices = mysqli_fetch_assoc($overdueInvoicesQuery)['count'] ?? 0;

// 6. Recent Purchase Orders
$recentPosQuery = mysqli_query($conn, "
    SELECT po.po_id, po.po_number, v.company_name, po.total_amount, po.po_date, po.status 
    FROM purchase_orders po 
    JOIN vendors v ON po.vendor_id = v.vendor_id 
    ORDER BY po.created_at DESC 
    LIMIT 5
");
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>System Overview</h2>
    <span class="text-muted">As of <?php echo date('d M Y'); ?></span>
</div>

<div class="row mb-4 g-3">
    <div class="col-md">
        <div class="stat-card border-bottom border-primary border-3">
            <h3><?php echo number_format($activeRfqs); ?></h3>
            <p>Active RFQs</p>
        </div>
    </div>
    <div class="col-md">
        <div class="stat-card border-bottom border-warning border-3">
            <h3><?php echo number_format($pendingRfqs); ?></h3>
            <p>Draft RFQs</p>
        </div>
    </div>
    <div class="col-md">
        <div class="stat-card border-bottom border-success border-3">
            <h3><?php echo number_format($totalPos); ?></h3>
            <p>Total POs</p>
        </div>
    </div>
    <div class="col-md">
        <div class="stat-card border-bottom border-info border-3">
            <h3><?php echo number_format($posThisMonth); ?></h3>
            <p>POs This Month</p>
        </div>
    </div>
    <div class="col-md">
        <div class="stat-card border-bottom border-danger border-3">
            <h3><?php echo number_format($overdueInvoices); ?></h3>
            <p>Overdue Invoices</p>
        </div>
    </div>
</div>

<div class="card shadow-sm border-0 p-4 mb-4">
    <h5 class="mb-3">System Overview Analytics</h5>
    <canvas id="adminChart" style="max-height: 300px; width: 100%;"></canvas>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('adminChart').getContext('2d');
    const adminChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: ['Active RFQs', 'Draft RFQs', 'Total POs', 'POs This Month', 'Overdue'],
            datasets: [{
                label: 'Count',
                data: [
                    <?php echo $activeRfqs; ?>, 
                    <?php echo $pendingRfqs; ?>, 
                    <?php echo $totalPos; ?>, 
                    <?php echo $posThisMonth; ?>, 
                    <?php echo $overdueInvoices; ?>
                ],
                backgroundColor: ['#0d6efd', '#ffc107', '#198754', '#0dcaf0', '#dc3545'],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            plugins: { legend: { display: false } },
            scales: { y: { beginAtZero: true } }
        }
    });
</script>

<div class="card border-0 shadow-sm">
    <div class="card-header bg-white border-bottom py-3 d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Recent Purchase Orders</h5>
        <a href="invoices.php" class="btn btn-sm btn-outline-primary">View All</a>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>PO Number</th>
                        <th>Vendor</th>
                        <th>Amount</th>
                        <th>Date</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (mysqli_num_rows($recentPosQuery) > 0) { 
                        while ($row = mysqli_fetch_assoc($recentPosQuery)) { 
                            
                            $badgeClass = 'bg-secondary';
                            if (in_array($row['status'], ['Generated', 'Sent'])) $badgeClass = 'bg-warning text-dark';
                            if (in_array($row['status'], ['Accepted', 'Completed'])) $badgeClass = 'bg-success';
                            if ($row['status'] == 'Cancelled') $badgeClass = 'bg-danger';
                    ?>
                    <tr>
                        <td class="fw-bold text-primary"><?php echo htmlspecialchars($row['po_number']); ?></td>
                        <td><?php echo htmlspecialchars($row['company_name']); ?></td>
                        <td class="fw-bold">₹<?php echo number_format($row['total_amount'], 2); ?></td>
                        <td><?php echo date('d M Y', strtotime($row['po_date'])); ?></td>
                        <td><span class="badge <?php echo $badgeClass; ?>"><?php echo htmlspecialchars($row['status']); ?></span></td>
                        <td>
                        <a href="po_details.php?id=<?php echo $row['po_id']; ?>" class="btn btn-sm btn-light border"> <i class="bi bi-eye"></i> Details</a>
                        </td>
                    </tr>
                    <?php 
                        } 
                    } else { 
                    ?>
                        <tr>
                            <td colspan="6" class="text-center py-4 text-muted">No recent Purchase Orders found.</td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php
include 'footer.php';
?>v