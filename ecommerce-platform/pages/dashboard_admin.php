<?php
// debug
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Session check
include('../includes/session.php');

// Role check
if (!isset($_SESSION['loggedin']) || $_SESSION['user_role'] !== 'admin') {
  header("Location: ../pages/login.php?error=unauthorized");
  exit();
}

include('../includes/header.php');
?>

<style>
  body {
    background: #f9f9f9;
  }
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
  .alert {
    border-radius: 8px;
    padding: 15px 20px;
  }
  .btn {
    transition: all 0.2s ease-in-out;
  }
  .btn:hover {
    transform: translateY(-2px);
  }
</style>

<div class="container mt-5">
  <h2>Welcome Admin</h2>

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
      <a href="view_products.php" class="btn btn-warning w-100 py-3">
        <i class="fas fa-box-open"></i> View Products
      </a>
    </div>
    <div class="col-md-4 mb-3">
      <a href="reports.php" class="btn btn-success w-100 py-3">
        <i class="fas fa-chart-bar"></i> Generate Reports
      </a>
    </div>
  </div>
</div>

<?php include('../includes/footer.php'); ?>
