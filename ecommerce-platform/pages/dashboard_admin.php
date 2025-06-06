<?php
// 1. Start session and check login status
require_once('../includes/check_session.php');

// 2. Verify admin role (added security)
if ($_SESSION['role'] !== 'admin') {
    header("Location: ../login.php?error=unauthorized");
    exit();
}

// 3. Include header after session checks
include('header.php');
?>

<div class="container mt-5">
    <h2>Admin Dashboard</h2>
    <div class="alert alert-info">
        Last activity: <?php echo date('Y-m-d H:i:s', $_SESSION['LAST_ACTIVITY']); ?>
    </div>
    
    <div class="row">
        <div class="col-md-4 mb-3">
            <a href="manage_users.php" class="btn btn-primary w-100 py-3">
                <i class="fas fa-users"></i> Manage Users
            </a>
        </div>
        <div class="col-md-4 mb-3">
            <a href="admin_orders.php" class="btn btn-warning w-100 py-3">
                <i class="fas fa-shopping-cart"></i> View Orders
            </a>
        </div>
        <div class="col-md-4 mb-3">
            <a href="reports.php" class="btn btn-success w-100 py-3">
                <i class="fas fa-chart-bar"></i> Generate Reports
            </a>
        </div>
    </div>
</div>

<?php include('footer.php'); ?>