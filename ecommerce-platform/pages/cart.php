<?php
session_start();
include_once('../includes/db.php');
include_once('../includes/header.php');

// Check if user is a buyer
if ($_SESSION['role'] !== 'buyer') {
    header('Location: home.php');
    exit();
}

$buyer_id = $_SESSION['user_id'];
$stmt = $conn->prepare("SELECT cart.id, products.name, products.price, cart.quantity FROM cart JOIN products ON cart.product_id = products.id WHERE cart.buyer_id = ?");
$stmt->bind_param("i", $buyer_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<h2>Your Cart</h2>
<form action="../actions/place_order.php" method="POST">
    <?php $total = 0; ?>
    <?php while ($row = $result->fetch_assoc()): ?>
        <div>
            <h3><?= htmlspecialchars($row['name']) ?></h3>
            <p>Price: R<?= number_format($row['price'], 2) ?></p>
            <p>Quantity: <?= $row['quantity'] ?></p>
            <?php $total += $row['price'] * $row['quantity']; ?>
        </div>
    <?php endwhile; ?>
    <p>Total: R<?= number_format($total, 2) ?></p>
    <button type="submit">Place Order</button>
</form>

<?php include_once('../includes/footer.php'); ?>
