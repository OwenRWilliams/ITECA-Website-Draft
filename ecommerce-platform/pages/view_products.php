<?php
include('includes/check_session.php');
include('header.php');
?>

<div class="container mt-5">
    <h2>All Products</h2>
    
    <!-- Search and Filter Bar (Optional) -->
    <div class="row mb-4">
        <div class="col-md-6">
            <form class="d-flex">
                <input class="form-control me-2" type="search" placeholder="Search products..." name="search">
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
        // Build base query
        $query = "SELECT * FROM products WHERE stock > 0";
        
        // Add sorting
        if (isset($_GET['sort'])) {
            switch ($_GET['sort']) {
                case 'price_asc': $query .= " ORDER BY price ASC"; break;
                case 'price_desc': $query .= " ORDER BY price DESC"; break;
                case 'newest': $query .= " ORDER BY created_at DESC"; break;
            }
        } else {
            $query .= " ORDER BY created_at DESC"; // Default sorting
        }

        $stmt = $conn->prepare($query);
        $stmt->execute();
        $products = $stmt->get_result();

        if ($products->num_rows > 0):
            while ($product = $products->fetch_assoc()):
        ?>
            <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
                <div class="card h-100 product-card">
                    <!-- Product Image -->
                    <div class="image-container" style="height: 200px; overflow: hidden;">
                        <img src="assets/products/<?php echo htmlspecialchars($product['image'] ?? 'default.jpg'); ?>" 
                             class="card-img-top p-3" 
                             alt="<?php echo htmlspecialchars($product['name']); ?>"
                             style="width: 100%; height: 100%; object-fit: contain">
                    </div>

                    <!-- Product Body -->
                    <div class="card-body">
                        <h5 class="card-title"><?php echo htmlspecialchars($product['name']); ?></h5>
                        <p class="card-text text-muted small">
                            <?php 
                            $desc = htmlspecialchars($product['description']);
                            echo strlen($desc) > 80 ? substr($desc, 0, 80).'...' : $desc;
                            ?>
                        </p>
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="h5 text-primary">$<?php echo number_format($product['price'], 2); ?></span>
                            <span class="badge <?php echo ($product['stock'] > 5) ? 'bg-success' : 'bg-warning'; ?>">
                                <?php echo ($product['stock'] > 5) ? 'In Stock' : 'Only '.$product['stock'].' left'; ?>
                            </span>
                        </div>
                    </div>

                    <!-- Card Footer -->
                    <div class="card-footer bg-white border-top-0">
                        <div class="d-flex justify-content-between">
                            <a href="product_details.php?id=<?php echo $product['id']; ?>" 
                               class="btn btn-sm btn-outline-primary">
                               <i class="fas fa-info-circle"></i> Details
                            </a>
                            <form action="actions/add_to_cart.php" method="POST" class="d-inline">
                                <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                                <button type="submit" 
                                        class="btn btn-sm btn-success"
                                        <?php echo ($product['stock'] < 1) ? 'disabled' : ''; ?>>
                                    <i class="fas fa-cart-plus"></i> Add
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        <?php 
            endwhile;
        else:
        ?>
            <div class="col-12">
                <div class="alert alert-info">No products found. Please check back later!</div>
            </div>
        <?php endif; ?>
    </div>

    <!-- Pagination (Optional) -->
    <nav class="mt-4">
        <ul class="pagination justify-content-center">
            <li class="page-item disabled">
                <a class="page-link" href="#" tabindex="-1">Previous</a>
            </li>
            <li class="page-item active"><a class="page-link" href="#">1</a></li>
            <li class="page-item"><a class="page-link" href="#">2</a></li>
            <li class="page-item"><a class="page-link" href="#">3</a></li>
            <li class="page-item">
                <a class="page-link" href="#">Next</a>
            </li>
        </ul>
    </nav>
</div>

<?php include('footer.php'); ?>