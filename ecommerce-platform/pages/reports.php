<?php
session_start();
include_once('../includes/db.php');
include_once('../includes/header.php');

// Restrict access to admins only
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    echo "<div class='container mt-5 alert alert-danger'>Access Denied. Admins only.</div>";
    include_once('../includes/footer.php');
    exit;
}

// Query for report data
$total_users = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as count FROM users"))['count'];
$total_buyers = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as count FROM users WHERE role='buyer'"))['count'];
$total_sellers = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as count FROM users WHERE role='seller'"))['count'];
$total_products = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as count FROM products"))['count'];
$total_orders = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as count FROM orders"))['count'];
$total_revenue = mysqli_fetch_assoc(mysqli_query($conn, "SELECT SUM(total_price) as revenue FROM orders"))['revenue'];
?>

<div class="container mt-5">
    <h2>Platform Reports</h2>
    <div class="row mt-4">
        <div class="col-md-6">
            <div class="card bg-light mb-3">
                <div class="card-header">User Statistics</div>
                <div class="card-body">
                    <p><strong>Total Users:</strong> <?= $total_users ?></p>
                    <p><strong>Buyers:</strong> <?= $total_buyers ?></p>
                    <p><strong>Sellers:</strong> <?= $total_sellers ?></p>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card bg-light mb-3">
                <div class="card-header">E-commerce Stats</div>
                <div class="card-body">
                    <p><strong>Total Products:</strong> <?= $total_products ?></p>
                    <p><strong>Total Orders:</strong> <?= $total_orders ?></p>
                    <p><strong>Total Revenue:</strong> R<?= number_format($total_revenue ?? 0, 2) ?></p>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include_once('../includes/footer.php'); ?>
