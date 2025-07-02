<?php
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include('../includes/header.php');
include '../includes/db.php';

// Get user data safely
$userId = $_SESSION['user_id'] ?? null;

if ($userId) {
  $stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
  $stmt->bind_param("i", $userId);
  $stmt->execute();
  $result = $stmt->get_result();
  $row = $result->fetch_assoc();
} else {
  $row = null;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>User Dashboard</title>
  <!-- Link your custom CSS -->
  <link rel="stylesheet" href="../assets/style.css">
  <!-- Bootstrap CDN -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container mt-5">
  <!-- Welcome Section -->
  <div class="alert alert-success">
    <h2>Welcome back, <?php echo htmlspecialchars($row['name'] ?? 'Guest'); ?>!</h2>
    <p class="mb-0">
      Account since:
      <?php
      if (!empty($row['created_at'])) {
        echo date('F Y', strtotime($row['created_at']));
      } else {
        echo 'N/A';
      }
      ?>
    </p>
  </div>

  <!-- Quick Actions: Browse + Cart -->
  <div class="row mb-4">
    <div class="col-md-6 mb-3">
      <a href="view_products.php" class="btn btn-primary w-100 py-3">
        <i class="fas fa-store"></i> Browse Products
      </a>
    </div>
    <div class="col-md-6 mb-3">
      <a href="cart.php" class="btn btn-warning w-100 py-3">
        <i class="fas fa-shopping-cart"></i> View Cart
      </a>
    </div>
  </div>

  <!-- Featured Products -->
  <h4 class="mb-3">Recommended for You</h4>
  <div class="row">
    <?php
    $stmt = $conn->prepare("SELECT * FROM products LIMIT 4");
    $stmt->execute();
    $products = $stmt->get_result();

    while ($product = $products->fetch_assoc()):
      $productId = $product['id'];
    ?>
      <div class="col-md-3 mb-4">
        <div class="card h-100 p-3">
          <h5 class="card-title"><?php echo htmlspecialchars($product['name']); ?></h5>
          <p class="card-text">R<?php echo number_format($product['price'], 2); ?></p>

          <!-- Expand description toggle -->
          <button class="btn btn-sm btn-primary mb-2" type="button" data-bs-toggle="collapse" data-bs-target="#desc-<?php echo $productId; ?>">
            View Description
          </button>

          <div class="collapse" id="desc-<?php echo $productId; ?>">
            <p class="mt-2"><?php echo htmlspecialchars($product['description']); ?></p>
          </div>

          <form action="../backend/add_to_cart.php" method="POST" class="d-inline">
            <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
            <button type="submit" class="btn btn-sm btn-success">
              <i class="fas fa-cart-plus"></i> Add to Cart
            </button>
          </form>
        </div>
      </div>
    <?php endwhile; ?>
  </div>
</div>

<?php include '../includes/footer.php'; ?>

<!-- Bootstrap JS for toggles -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
