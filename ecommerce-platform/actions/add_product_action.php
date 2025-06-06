<?php
session_start();
include_once('../includes/db.php');

// Check if user is a seller
if ($_SESSION['role'] !== 'seller') {
    header('Location: ../pages/home.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $seller_id = $_SESSION['user_id'];
    $name = $_POST['name'];
    $description = $_POST['description'];
    $price = $_POST['price'];

    $stmt = $conn->prepare("INSERT INTO products (seller_id, name, description, price) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("isss", $seller_id, $name, $description, $price);
    $stmt->execute();

    header('Location: ../pages/dashboard_seller.php');
    exit();
}
?>
