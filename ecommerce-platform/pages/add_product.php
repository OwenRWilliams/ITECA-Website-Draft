<?php
session_start();
include_once('../includes/db.php');
include_once('../includes/header.php');

// Check if user is a seller
if ($_SESSION['role'] !== 'seller') {
    header('Location: home.php');
    exit();
}
?>

<h2>Add New Product</h2>
<form action="../actions/add_product_action.php" method="POST">
    <label>Product Name:</label><br>
    <input type="text" name="name" required><br>
    <label>Description:</label><br>
    <textarea name="description" required></textarea><br>
    <label>Price:</label><br>
    <input type="number" name="price" step="0.01" required><br>
    <button type="submit">Add Product</button>
</form>

<?php include_once('../includes/footer.php'); ?>
