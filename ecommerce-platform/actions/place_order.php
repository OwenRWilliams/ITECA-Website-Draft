<?php
session_start();
include_once('../includes/db.php');

// Check if user is a buyer
if ($_SESSION['role'] !== 'buyer') {
    header('Location: ../pages/home.php');
    exit();
}

$buyer_id = $_SESSION['user_id'];

// Fetch cart items
$stmt = $conn->prepare("SELECT product_id, quantity FROM cart WHERE buyer_id = ?");
$stmt->bind_param("i", $buyer_id);
$stmt->execute();
$result = $stmt->get_result();

// Insert into orders
while ($row = $result->fetch_assoc()) {
    $product_id = $row['product_id'];
    $quantity = $row['quantity'];

    $stmt_order = $conn->prepare("INSERT INTO orders (buyer_id, product_id, quantity) VALUES (?, ?, ?)");
    $stmt_order->bind_param("iii", $buyer_id, $product_id, $quantity);
    $stmt_order->execute();
}

// Clear cart
$stmt = $conn->prepare("DELETE FROM cart WHERE buyer_id = ?");
$stmt->bind_param("i", $buyer_id);
$stmt->execute();

header('Location: ../pages/orders.php');
exit();
?>
