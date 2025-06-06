<?php
session_start();
include_once('../includes/db.php');
include_once('../includes/header.php');

$user_id = $_SESSION['user_id'];
$role = $_SESSION['role'];

if ($role === 'buyer') {
    $stmt = $conn->prepare("SELECT orders.id, products.name, orders.quantity, orders.created_at FROM orders JOIN products ON orders.product_id = products.id WHERE orders.buyer_id = ?");
} elseif ($role === 'seller') {
    $stmt = $conn->prepare("SELECT orders.id, products.name, orders.quantity, orders.created_at FROM orders JOIN products ON orders.product_id = products.id WHERE products.seller_id = ?");
} else {
    header('Location: home.php');
    exit();
}

$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<h2>Your Orders</h2>
<?php while ($row = $result->fetch_assoc()): ?>
    <div>
        <p>Order ID: <?= $row['id'] ?></p>
        <p>Product: <?= htmlspecialchars($row['name']) ?></p>
        <p>Quantity: <?= $row['quantity'] ?></p>
        <p>Date: <?= $row['created_at'] ?></p>
    </div>
<?php endwhile; ?>

<?php include_once('../includes/footer.php'); ?>
