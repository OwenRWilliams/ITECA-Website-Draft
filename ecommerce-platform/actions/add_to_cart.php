<?php
session_start();
include('../includes/db.php');

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

$product_id = intval($_POST['product_id']);
$user_id = $_SESSION['user_id'];

// Check if item already exists in cart
$stmt = $conn->prepare("SELECT * FROM cart WHERE user_id = ? AND product_id = ?");
$stmt->bind_param("ii", $user_id, $product_id);
$stmt->execute();
$exists = $stmt->get_result()->num_rows > 0;

if ($exists) {
    // Update quantity
    $conn->query("UPDATE cart SET quantity = quantity + 1 WHERE user_id = $user_id AND product_id = $product_id");
} else {
    // Add new item
    $stmt = $conn->prepare("INSERT INTO cart (user_id, product_id) VALUES (?, ?)");
    $stmt->bind_param("ii", $user_id, $product_id);
    $stmt->execute();
}

// Update cart count in session
$_SESSION['cart_count'] = $conn->query("SELECT COUNT(*) FROM cart WHERE user_id = $user_id")->fetch_row()[0];

header("Location: ../view_products.php?added=1");
exit();
?>