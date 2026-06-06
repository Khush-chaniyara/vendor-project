<?php
include 'header.php';

// Handle Search and Filter inputs safely
$search = isset($_GET['search']) ? mysqli_real_escape_string($conn, $_GET['search']) : '';
$status = isset($_GET['status']) ? mysqli_real_escape_string($conn, $_GET['status']) : 'All';

// Build the dynamic WHERE clause
$whereClause = "WHERE 1=1";
if ($search) {
    $whereClause .= " AND (v.company_name LIKE '%$search%' OR v.gst_number LIKE '%$search%' OR v.email LIKE '%$search%')";
}
if ($status !== 'All') {
    $whereClause .= " AND v.vendor_status = '$status'";
}

// Fetch Vendors
$query = "SELECT v.*, c.category_name 
          FROM vendors v 
          LEFT JOIN vendor_categories c ON v.category_id = c.category_id 
          $whereClause 
          ORDER BY v.created_at DESC";
$vendors = mysqli_query($conn, $query);
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2>Vendor Directory</h2>
        <span class="text-muted">Manage your procurement network</span>
    </div>
    <a href="add_vendor.php" class="btn btn-primary shadow-sm"><i class="bi bi-person-plus"></i> Add New Vendor</a>
</div>

<div class="card shadow-sm border-0 mb-4 p-3 bg-white">
    <form method="GET" class="row g-2 align-items-center">
        <div class="col-md-5">
            <div class="input-group">
                <span class="input-group-text bg-light border-end-0"><i class="bi bi-search"></i></span>
                <input type="text" name="search" class="form-control border-start-0" placeholder="Search by Name, GST, or Email..." value="<?php echo htmlspecialchars($search); ?>">
            </div>
        </div>
        <div class="col-md-5">
            <div class="btn-group w-100 shadow-sm" role="group">
                <button type="submit" name="status" value="All" class="btn <?php echo $status=='All'?'btn-dark':'btn-outline-dark'; ?>">All</button>
                <button type="submit" name="status" value="Active" class="btn <?php echo $status=='Active'?'btn-success':'btn-outline-success'; ?>">Active</button>
                <button type="submit" name="status" value="Pending" class="btn <?php echo $status=='Pending'?'btn-warning':'btn-outline-warning'; ?>">Pending</button>
                <button type="submit" name="status" value="Blacklisted" class="btn <?php echo $status=='Blacklisted'?'btn-danger':'btn-outline-danger'; ?>">Blocked</button>
            </div>
        </div>
        <div class="col-md-2">
            <a href="vendors.php" class="btn btn-light w-100 border text-muted">Clear Filters</a>
        </div>
    </form>
</div>

<div class="card shadow-sm border-0">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>Company Name</th>
                    <th>Category</th>
                    <th>Contact Details</th>
                    <th>GST Number</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php if (mysqli_num_rows($vendors) > 0) { 
                    while ($row = mysqli_fetch_assoc($vendors)) { 
                        $badge = 'bg-secondary';
                        if($row['vendor_status'] == 'Active') $badge = 'bg-success';
                        if($row['vendor_status'] == 'Pending') $badge = 'bg-warning text-dark';
                        if($row['vendor_status'] == 'Blacklisted') $badge = 'bg-danger';
                ?>
                <tr>
                    <td class="fw-bold text-primary"><?php echo htmlspecialchars($row['company_name']); ?></td>
                    <td><span class="badge bg-light text-dark border"><?php echo htmlspecialchars($row['category_name'] ?? 'Uncategorized'); ?></span></td>
                    <td>
                        <div class="small fw-bold"><i class="bi bi-person"></i> <?php echo htmlspecialchars($row['contact_person']); ?></div>
                        <div class="small text-muted"><i class="bi bi-envelope"></i> <?php echo htmlspecialchars($row['email']); ?></div>
                    </td>
                    <td class="font-monospace text-muted"><?php echo htmlspecialchars($row['gst_number']); ?></td>
                    <td><span class="badge <?php echo $badge; ?>"><?php echo htmlspecialchars($row['vendor_status']); ?></span></td>
                    <td>
                        <a href="vendor_details.php?id=<?php echo $row['vendor_id']; ?>" class="btn btn-sm btn-outline-primary shadow-sm"> <i class="bi bi-eye"></i> View Profile </a>
                    </td>
                </tr>
                <?php } } else { ?>
                    <tr><td colspan="6" class="text-center py-4 text-muted">No vendors found matching your search.</td></tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</div>

<?php include 'footer.php'; ?>