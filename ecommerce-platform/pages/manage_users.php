<?php
session_start();
include_once('../includes/db.php');
include_once('../includes/header.php');

// Check if user is an admin
if ($_SESSION['role'] !== 'admin') {
    header('Location: home.php');
    exit();
}

// Fetch all users
$result = $conn->query("SELECT id, username, role FROM users");
?>

<div class="container mt-5">
    <h2>Manage Users</h2>
    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>Username</th>
                <th>Role</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($row['username']) ?></td>
                    <td><?= htmlspecialchars($row['role']) ?></td>
                    <td>
                        <?php if ($row['id'] !== $_SESSION['user']['id']): // Prevent self-delete ?>
                            <form action="../actions/delete_user.php" method="POST" onsubmit="return confirm('Are you sure you want to delete this user?');" style="display:inline;">
                                <input type="hidden" name="user_id" value="<?= $row['id'] ?>">
                                <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                            </form>
                        <?php else: ?>
                            <button class="btn btn-danger btn-sm" disabled>Delete</button>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

<?php include_once('../includes/footer.php'); ?>
