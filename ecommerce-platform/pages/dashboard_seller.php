<?php
// ✅ DEBUG for dev
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// ✅ Session check
include('../includes/session.php');

// ✅ Role check
if (!isset($_SESSION['loggedin']) || $_SESSION['user_role'] !== 'seller') {
  header("Location: /pages/login.php");
  exit();
}

// ✅ DB connect
include_once('../includes/db.php');

// ✅ Handle Add Product form
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_product'])) {
  $seller_id = $_SESSION['user_id'];
  $name = trim($_POST['name']);
  $description = trim($_POST['description']);
  $price = $_POST['price'];

  $stmt = $conn->prepare("INSERT INTO products (seller_id, name, description, price) VALUES (?, ?, ?, ?)");
  $stmt->bind_param("isss", $seller_id, $name, $description, $price);
  $stmt->execute();

  if ($stmt->error) {
    die("DB Error: " . $stmt->error);
  }

  $success = "Product added successfully!";
}

// ✅ Get seller's products
$seller_id = $_SESSION['user_id'];
$stmt = $conn->prepare("SELECT * FROM products WHERE seller_id = ?");
$stmt->bind_param("i", $seller_id);
$stmt->execute();
$products = $stmt->get_result();

// ✅ Get seller's orders + buyer email
$stmt = $conn->prepare("
  SELECT o.id, o.user_id, u.email AS buyer_email, o.product_id, o.quantity, o.total, o.status, o.created_at
  FROM orders o
  JOIN users u ON o.user_id = u.id
  WHERE o.seller_id = ?
  ORDER BY o.created_at DESC
");
$stmt->bind_param("i", $seller_id);
$stmt->execute();
$orders = $stmt->get_result();

// ✅ Page header
include('../includes/header.php');
?>

<style>
  body { background: #f9f9f9; }
  .container {
    background: #fff;
    border-radius: 10px;
    padding: 30px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.05);
    margin-bottom: 40px;
  }
  h2, h4 { font-weight: 600; margin-bottom: 20px; }
  .btn { margin-top: 10px; }
  table { width: 100%; margin-top: 20px; border-collapse: collapse; }
  table, th, td { border: 1px solid #ccc; }
  th, td { padding: 10px; text-align: left; }
</style>

<!-- ✅ Welcome -->
<div class="container mt-5">
  <h2>Welcome, <?php echo htmlspecialchars($_SESSION['name'] ?? 'Seller'); ?>!</h2>

  <!-- ✅ Add Product Form -->
  <h4>Add New Product</h4>
  <?php if (isset($success)): ?>
    <div class="alert alert-success"><?php echo $success; ?></div>
  <?php endif; ?>
  <form method="POST">
    <input type="hidden" name="add_product" value="1">
    <div class="mb-3">
      <label>Name</label>
      <input type="text" name="name" class="form-control" required>
    </div>
    <div class="mb-3">
      <label>Description</label>
      <textarea name="description" class="form-control" required></textarea>
    </div>
    <div class="mb-3">
      <label>Price</label>
      <input type="number" step="0.01" name="price" class="form-control" required>
    </div>
    <button type="submit" class="btn btn-primary">Add Product</button>
  </form>
</div>

<!-- ✅ Manage Products -->
<div class="container">
  <h4>My Products</h4>
  <?php if ($products->num_rows > 0): ?>
    <table>
      <tr><th>ID</th><th>Name</th><th>Price</th><th>Description</th></tr>
      <?php while ($product = $products->fetch_assoc()): ?>
        <tr>
          <td><?php echo $product['id']; ?></td>
          <td><?php echo htmlspecialchars($product['name']); ?></td>
          <td>R<?php echo htmlspecialchars($product['price']); ?></td>
          <td><?php echo htmlspecialchars($product['description']); ?></td>
        </tr>
      <?php endwhile; ?>
    </table>
  <?php else: ?>
    <p>No products found.</p>
  <?php endif; ?>
</div>

<!-- ✅ View Orders -->
<div class="container">
  <h4>My Orders</h4>
  <?php if ($orders->num_rows > 0): ?>
    <table>
      <tr>
        <th>Order ID</th>
        <th>Buyer Email</th>
        <th>Product ID</th>
        <th>Quantity</th>
        <th>Total</th>
        <th>Status</th>
        <th>Date</th>
      </tr>
      <?php while ($order = $orders->fetch_assoc()): ?>
        <tr>
          <td><?php echo $order['id']; ?></td>
          <td><?php echo htmlspecialchars($order['buyer_email']); ?></td>
          <td><?php echo $order['product_id']; ?></td>
          <td><?php echo $order['quantity']; ?></td>
          <td>R<?php echo number_format($order['total'], 2); ?></td>
          <td><?php echo htmlspecialchars($order['status']); ?></td>
          <td><?php echo $order['created_at']; ?></td>
        </tr>
      <?php endwhile; ?>
    </table>
  <?php else: ?>
    <p>No orders found.</p>
  <?php endif; ?>
</div>

<?php include('../includes/footer.php'); ?>
