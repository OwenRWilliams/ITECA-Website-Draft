<?php
session_start();
ini_set('display_errors', 1);
error_reporting(E_ALL);

include_once('../includes/db.php');
include_once('../includes/header.php');

// ✅ Role check
if (!isset($_SESSION['loggedin']) || $_SESSION['user_role'] !== 'admin') {
  header('Location: ../pages/login.php');
  exit();
}

// ✅ Fetch all users
$result = $conn->query("SELECT id, name, email, role, created_at FROM users ORDER BY created_at DESC");
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
  h2 { font-weight: 600; margin-bottom: 20px; }
  table { width: 100%; border-collapse: collapse; }
  table, th, td { border: 1px solid #ccc; }
  th, td { padding: 12px; text-align: left; }
  .btn { transition: all 0.2s ease-in-out; }
  .btn:hover { transform: translateY(-2px); }
</style>

<div class="container mt-5">
  <h2>Manage Users</h2>

  <?php if ($result->num_rows > 0): ?>
    <table class="table table-bordered table-striped">
      <thead class="table-dark">
        <tr>
          <th>ID</th>
          <th>Name</th>
          <th>Email</th>
          <th>Role</th>
          <th>Joined</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody>
        <?php while ($row = $result->fetch_assoc()): ?>
          <tr>
            <td><?= htmlspecialchars($row['id']) ?></td>
            <td><?= htmlspecialchars($row['name']) ?></td>
            <td><?= htmlspecialchars($row['email']) ?></td>
            <td><?= htmlspecialchars($row['role']) ?></td>
            <td><?= date('Y-m-d', strtotime($row['created_at'])) ?></td>
            <td>
              <?php if ($row['id'] != $_SESSION['user_id']): ?>
                <form action="../actions/delete_user.php" method="POST" onsubmit="return confirm('Are you sure you want to delete this user?');" style="display:inline;">
                  <input type="hidden" name="user_id" value="<?= $row['id'] ?>">
                  <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                </form>
              <?php else: ?>
                <button class="btn btn-secondary btn-sm" disabled>Cannot Delete Self</button>
              <?php endif; ?>
            </td>
          </tr>
        <?php endwhile; ?>
      </tbody>
    </table>
  <?php else: ?>
    <p>No users found.</p>
  <?php endif; ?>
</div>

<?php include_once('../includes/footer.php'); ?>
