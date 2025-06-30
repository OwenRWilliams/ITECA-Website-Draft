<?php
session_start();
ini_set('display_errors', 1);
error_reporting(E_ALL);

include_once('../includes/db.php');
include_once('../includes/header.php');


// ✅ 1) Check login by user_id only
if (!isset($_SESSION['user_id'])) {
  echo "No user ID. Redirecting to login.";
  header("Location: login.php");
  exit();
}

// ✅ 2) Normalize role
if (strtolower($_SESSION['user_role']) !== 'buyer') {
  echo "Only buyers can access the cart.";
  exit();
}

$user_id = $_SESSION['user_id'];

// ✅ 3) Handle Remove in this same file
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['remove_item'])) {
  $cart_id = intval($_POST['cart_id']);
  $stmt = $conn->prepare("DELETE FROM cart WHERE id = ? AND user_id = ?");
  $stmt->bind_param("ii", $cart_id, $user_id);
  if ($stmt->execute()) {
    $_SESSION['message'] = "Item removed from cart.";
  } else {
    $_SESSION['message'] = "Error removing item.";
  }
  header("Location: cart.php");
  exit();
}

// ✅ 4) Load items
$stmt = $conn->prepare("
  SELECT cart.id, products.name, products.price, cart.quantity
  FROM cart
  JOIN products ON cart.product_id = products.id
  WHERE cart.user_id = ?
");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<div class="container mt-5">
  <h2>Your Cart</h2>

  <?php if (isset($_SESSION['message'])): ?>
    <div class="alert alert-info"><?php echo htmlspecialchars($_SESSION['message']); unset($_SESSION['message']); ?></div>
  <?php endif; ?>

  <?php if ($result->num_rows > 0): ?>
    <?php $total = 0; ?>
    <?php while ($row = $result->fetch_assoc()): ?>
      <div class="mb-3 p-3 border rounded">
        <h5><?= htmlspecialchars($row['name']) ?></h5>
        <p>Price: R<?= number_format($row['price'], 2) ?></p>
        <p>Quantity: <?= $row['quantity'] ?></p>
        <?php $total += $row['price'] * $row['quantity']; ?>

        <!-- ✅ Remove: NO nested forms! -->
        <form method="POST" style="display:inline;">
          <input type="hidden" name="cart_id" value="<?= $row['id'] ?>">
          <button type="submit" name="remove_item" value="1" class="btn btn-danger btn-sm">Remove</button>
        </form>
      </div>
    <?php endwhile; ?>

    <h4>Total: R<?= number_format($total, 2) ?></h4>

    <!-- ✅ Place order is separate -->
    <form action="../backend/place_order.php" method="POST">
      <button type="submit" class="btn btn-success">Place Order</button>
    </form>
  <?php else: ?>
    <div class="alert alert-info">Your cart is empty.</div>
  <?php endif; ?>
</div>

<?php include_once('../includes/footer.php'); ?>
