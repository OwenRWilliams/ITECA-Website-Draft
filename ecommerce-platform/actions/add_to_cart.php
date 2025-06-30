<?php
session_start();
ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once('../includes/db.php');

// ✅ Check if user is logged in
if (!isset($_SESSION['user_id'])) {
  header("Location: /pages/login.php");
  exit();
}

$user_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['product_id'])) {
  $product_id = intval($_POST['product_id']);

  // ✅ Check if product already in cart
  $stmt = $conn->prepare("SELECT id, quantity FROM cart WHERE user_id = ? AND product_id = ?");
  $stmt->bind_param("ii", $user_id, $product_id);
  $stmt->execute();
  $result = $stmt->get_result();

  if ($result->num_rows > 0) {
    // ✅ Update quantity
    $row = $result->fetch_assoc();
    $cart_id = $row['id'];
    $new_qty = $row['quantity'] + 1;

    $stmt = $conn->prepare("UPDATE cart SET quantity = ? WHERE id = ?");
    $stmt->bind_param("ii", $new_qty, $cart_id);
    $stmt->execute();
  } else {
    // ✅ Add new row
    $stmt = $conn->prepare("INSERT INTO cart (user_id, product_id, quantity) VALUES (?, ?, 1)");
    $stmt->bind_param("ii", $user_id, $product_id);
    $stmt->execute();
  }

  $_SESSION['message'] = "Product added to cart!";
}

// ✅ ✅ ✅ FIX: Use absolute path!
header("Location: /pages/cart.php");
exit();
