<?php
include('session.php');
include('header.php');
if ($_SESSION['role'] !== 'seller') {
    header("Location: login.php");
    exit();
}
?>
<div class="container mt-5">
    <h2>Welcome, <?php echo $_SESSION['name']; ?>!</h2>
    <a href="add_product.php" class="btn btn-primary">Add New Product</a>
    <a href="manage_products.php" class="btn btn-warning">Manage My Products</a>
    <a href="orders.php" class="btn btn-success">View Orders</a>
</div>
<?php include('footer.php'); ?>
