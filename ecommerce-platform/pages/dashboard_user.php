<?php
// Secure session and role check
require_once('includes/check_session.php');
if ($_SESSION['role'] !== 'user') {
    header("Location: login.php?error=access_denied");
    exit();
}

include('header.php');
?>

<div class="container mt-5">
    <!-- Welcome Section -->
    <div class="alert alert-success">
        <h2>Welcome back, <?php echo htmlspecialchars($_SESSION['name']); ?>!</h2>
        <p class="mb-0">Account since: <?php echo date('F Y', strtotime($_SESSION['created_at'])); ?></p>
    </div>

    <!-- Quick Actions -->
    <div class="row mb-4">
        <div class="col-md-4 mb-3">
            <a href="view_products.php" class="btn btn-primary w-100 py-3">
                <i class="fas fa-store"></i> Browse Products
            </a>
        </div>
        <div class="col-md-4 mb-3">
            <a href="cart.php" class="btn btn-warning w-100 py-3">
                <i class="fas fa-shopping-cart"></i> View Cart 
                <?php if ($_SESSION['cart_count'] > 0): ?>
                    <span class="badge bg-danger"><?php echo $_SESSION['cart_count']; ?></span>
                <?php endif; ?>
            </a>
        </div>
        <div class="col-md-4 mb-3">
            <a href="order_history.php" class="btn btn-info w-100 py-3">
                <i class="fas fa-history"></i> Order History
            </a>
        </div>
    </div>

    <!-- Featured Products (Dynamic) -->
    <h4 class="mb-3">Recommended for You</h4>
    <div class="row">
        <?php
        include('includes/db.php');
        $stmt = $conn->prepare("SELECT * FROM products LIMIT 4");
        $stmt->execute();
        $products = $stmt->get_result();

        while ($product = $products->fetch_assoc()):
        ?>
            <div class="col-md-3 mb-4">
                <div class="card h-100">
                    <img src="assets/products/<?php echo $product['image']; ?>" class="card-img-top" alt="<?php echo $product['name']; ?>">
                    <div class="card-body">
                        <h5 class="card-title"><?php echo $product['name']; ?></h5>
                        <p class="card-text">$<?php echo $product['price']; ?></p>
                        <a href="product.php?id=<?php echo $product['id']; ?>" class="btn btn-sm btn-primary">View</a>
                        <form action="actions/add_to_cart.php" method="POST" class="d-inline">
                            <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                            <button type="submit" class="btn btn-sm btn-success">Add to Cart</button>
                        </form>
                    </div>
                </div>
            </div>
        <?php endwhile; ?>
    </div>
</div>

<?php include('footer.php'); ?>