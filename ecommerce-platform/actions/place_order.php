<?php
session_start();
ini_set('display_errors', 1);
error_reporting(E_ALL);
require_once('../includes/db.php');

// ✅ Check login & role
if (!isset($_SESSION['user_id']) || strtolower($_SESSION['user_role']) !== 'buyer') {
  header("Location: ../pages/login.php");
  exit();
}

$buyer_id = $_SESSION['user_id'];

// ✅ Get cart items
$stmt = $conn->prepare("
  SELECT cart.id AS cart_id, products.id AS product_id, products.seller_id, products.price, cart.quantity
  FROM cart 
  JOIN products ON cart.product_id = products.id
  WHERE cart.user_id = ?
");
$stmt->bind_param("i", $buyer_id);
$stmt->execute();
$items = $stmt->get_result();

if ($items->num_rows < 1) {
  $_SESSION['message'] = "Your cart is empty.";
  header("Location: ../pages/cart.php");
  exit();
}

// ✅ Start transaction
$conn->begin_transaction();
try {
  while ($row = $items->fetch_assoc()) {
    $product_id = $row['product_id'];
    $seller_id = $row['seller_id'];
    $quantity = $row['quantity'];
    $price = $row['price'];
    $total = $price * $quantity;

    // ✅ Insert order
    $stmt = $conn->prepare("INSERT INTO orders (user_id, product_id, seller_id, quantity, total, status) VALUES (?, ?, ?, ?, ?, 'pending')");
    $stmt->bind_param("iiiid", $buyer_id, $product_id, $seller_id, $quantity, $total);
    $stmt->execute();

    // ✅ Decrease stock
    $stmt = $conn->prepare("UPDATE products SET stock = stock - ? WHERE id = ?");
    $stmt->bind_param("ii", $quantity, $product_id);
    $stmt->execute();
  }

  // ✅ Clear cart
  $stmt = $conn->prepare("DELETE FROM cart WHERE user_id = ?");
  $stmt->bind_param("i", $buyer_id);
  $stmt->execute();

  $conn->commit();
  $_SESSION['message'] = "Order placed successfully!";

} catch (Exception $e) {
  $conn->rollback();
  $_SESSION['message'] = "Error placing order: " . $e->getMessage();
}

header("Location: ../pages/cart.php");
exit();
?>
