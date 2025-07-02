<?php
session_start();
include('../includes/db.php');
include('../includes/header.php');

// Check if the user is an admin (optional)
$isAdmin = isset($_SESSION['loggedin']) && $_SESSION['user_role'] === 'admin';
?>

<!-- Call your external stylesheet -->
<link rel="stylesheet" href="../assets/style.css">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

<div class="container mt-5">
  <h2>All Products</h2>

  <!-- Search + Sort -->
  <div class="row mb-4">
    <div class="col-md-6">
      <form class="d-flex" method="GET">
        <input class="form-control me-2" type="search" name="search" placeholder="Search products..." value="<?php echo htmlspecialchars($_GET['search'] ?? ''); ?>">
        <button class="btn btn-outline-primary" type="submit">Search</button>
      </form>
    </div>
    <div class="col-md-6 text-end">
      <div class="dropdown d-inline-block">
        <button class="btn btn-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
          Sort By
        </button>
        <ul class="dropdown-menu">
          <li><a class="dropdown-item" href="?sort=price_asc">Price: Low to High</a></li>
          <li><a class="dropdown-item" href="?sort=price_desc">Price: High to Low</a></li>
          <li><a class="dropdown-item" href="?sort=newest">Newest First</a></li>
        </ul>
      </div>
    </div>
  </div>

  <!-- Product Grid -->
  <div class="row">
    <?php
    $query = "SELECT * FROM products";
    $params = [];
    $types = '';

    if (!empty($_GET['search'])) {
      $query .= " WHERE name LIKE ?";
      $params[] = "%{$_GET['search']}%";
      $types .= 's';
    }

    if (isset($_GET['sort'])) {
      switch ($_GET['sort']) {
        case 'price_asc': $query .= " ORDER BY price ASC"; break;
        case 'price_desc': $query .= " ORDER BY price DESC"; break;
        case 'newest': $query .= " ORDER BY created_at DESC"; break;
      }
    } else {
      $query .= " ORDER BY created_at DESC";
    }

    $stmt = $conn->prepare($query);
    if (!empty($params)) {
      $stmt->bind_param($types, ...$params);
    }
    $stmt->execute();
    $products = $stmt->get_result();

    if ($products->num_rows > 0):
      while ($product = $products->fetch_assoc()):
    ?>
      <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
        <div class="product-card">
          <h5><?php echo htmlspecialchars($product['name']); ?></h5>
          <p class="text-muted small">
            <?php
              $desc = htmlspecialchars($product['description']);
              echo strlen($desc) > 80 ? substr($desc, 0, 80) . '...' : $desc;
            ?>
          </p>
          <p class="h6">R<?php echo number_format($product['price'], 2); ?></p>
          <div class="mt-2">
            <form action="../backend/add_to_cart.php" method="POST" class="d-inline">
              <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
              <button type="submit" class="btn btn-sm btn-success">
                <i class="fas fa-cart-plus"></i> Add
              </button>
            </form>

            <?php if ($isAdmin): ?>
              <!-- Admin Delete -->
              <form action="../backend/delete_product.php" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this product?');">
                <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                <button type="submit" class="btn btn-sm btn-danger">
                  <i class="fas fa-trash"></i> Delete
                </button>
              </form>
            <?php endif; ?>
          </div>
        </div>
      </div>
    <?php endwhile; else: ?>
      <div class="col-12">
        <div class="alert alert-info">No products found. Please check back later!</div>
      </div>
    <?php endif; ?>
  </div>
</div>

<?php include('footer.php'); ?>
