<?php
session_start();
include_once('../includes/db.php');

// Only admins can delete
if (!isset($_SESSION['loggedin']) || $_SESSION['user_role'] !== 'admin') {
  header("Location: ../pages/login.php");
  exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['product_id'])) {
  $product_id = intval($_POST['product_id']);

  $stmt = $conn->prepare("DELETE FROM products WHERE id = ?");
  $stmt->bind_param("i", $product_id);

  if ($stmt->execute()) {
    header("Location: ../pages/view_products.php?deleted=1");
    exit();
  } else {
    echo "Error deleting product.";
  }
} else {
  header("Location: ../pages/view_products.php");
  exit();
}
