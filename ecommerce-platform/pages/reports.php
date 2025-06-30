<?php
session_start();
ini_set('display_errors', 1);
error_reporting(E_ALL);

include_once('../includes/db.php');
include_once('../includes/header.php');

// ✅ Restrict access to admins only
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
  echo "<div class='container mt-5 alert alert-danger'>Access Denied. Admins only.</div>";
  include_once('../includes/footer.php');
  exit;
}

// ✅ Get user stats
$stmt = $conn->prepare("SELECT COUNT(*) as count FROM users");
$stmt->execute();
$result = $stmt->get_result();
$total_users = $result->fetch_assoc()['count'] ?? 0;

$stmt = $conn->prepare("SELECT COUNT(*) as count FROM users WHERE role = 'buyer'");
$stmt->execute();
$result = $stmt->get_result();
$total_buyers = $result->fetch_assoc()['count'] ?? 0;

$stmt = $conn->prepare("SELECT COUNT(*) as count FROM users WHERE role = 'seller'");
$stmt->execute();
$result = $stmt->get_result();
$total_sellers = $result->fetch_assoc()['count'] ?? 0;

// ✅ Product stats
$stmt = $conn->prepare("SELECT COUNT(*) as count FROM products");
$stmt->execute();
$result = $stmt->get_result();
$total_products = $result->fetch_assoc()['count'] ?? 0;

// ✅ Orders stats
$stmt = $conn->prepare("SELECT COUNT(*) as count FROM orders");
$stmt->execute();
$result = $stmt->get_result();
$total_orders = $result->fetch_assoc()['count'] ?? 0;

// ✅ Revenue - FIX: use `total` not `total_price`
$stmt = $conn->prepare("SELECT SUM(total) as revenue FROM orders");
$stmt->execute();
$result = $stmt->get_result();
$total_revenue = $result->fetch_assoc()['revenue'] ?? 0;
?>

<style>
  body { background: #f9f9f9; }
  .container {
    background: #fff;
    border-radius: 10px;
    padding: 30px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.05);
  }
  h2 {
    font-weight: 600;
    margin-bottom: 20px;
  }
  .card {
    border: none;
    border-radius: 10px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.05);
  }
  .card-header {
    background: var(--primary);
    color: #fff;
    font-weight: 600;
  }
  .card-body p {
    margin-bottom: 8px;
  }
</style>

<div class="container mt-5">
  <h2>Platform Reports</h2>

  <div class="row mt-4">
    <div class="col-md-6">
      <div class="card mb-3">
        <div class="card-header">User Statistics</div>
        <div class="card-body">
          <p><strong>Total Users:</strong> <?= $total_users ?></p>
          <p><strong>Buyers:</strong> <?= $total_buyers ?></p>
          <p><strong>Sellers:</strong> <?= $total_sellers ?></p>
        </div>
      </div>
    </div>

    <div class="col-md-6">
      <div class="card mb-3">
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
