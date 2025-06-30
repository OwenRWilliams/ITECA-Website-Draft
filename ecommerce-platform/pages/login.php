<?php
session_start();
require_once('../includes/db.php');

$error = "";

// Handle POST
if ($_SERVER["REQUEST_METHOD"] === "POST") {
  $email = trim($_POST['email']);
  $password = $_POST['password'];

  if (!empty($email) && !empty($password)) {

    // ✅ Hardcoded admin fallback
    if ($email === 'admin@gmail.com' && $password === 'admin1234') {
      $_SESSION['user_id'] = 0;   // fake ID for admin
      $_SESSION['user_role'] = 'admin';
      $_SESSION['name'] = 'Admin';
      $_SESSION['loggedin'] = true;
      $_SESSION['LAST_ACTIVITY'] = time();

      header("Location: dashboard_admin.php");
      exit();
    }

    // ✅ Normal DB check
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if ($user && password_verify($password, $user['password'])) {
      // ✅ Force role to lowercase
      $role = strtolower($user['role']);

      $_SESSION['user_id'] = $user['id'];
      $_SESSION['user_role'] = $role;
      $_SESSION['name'] = $user['name'] ?? '';
      $_SESSION['loggedin'] = true;
      $_SESSION['LAST_ACTIVITY'] = time();

      // ✅ Redirect based on role
      switch ($role) {
        case 'admin':
          header("Location: dashboard_admin.php");
          break;
        case 'seller':
          header("Location: dashboard_seller.php");
          break;
        default:
          header("Location: dashboard_user.php");
      }
      exit();
    } else {
      $error = "Invalid email or password.";
    }

  } else {
    $error = "Please enter both email and password.";
  }
}
?>

<?php include('../includes/header.php'); ?>

<div class="container mt-5">
  <h2>Login</h2>

  <?php if (!empty($error)): ?>
    <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
  <?php endif; ?>

  <?php if (isset($_SESSION['logout_success'])): ?>
    <div class="alert alert-success">
      <?php echo $_SESSION['logout_success']; unset($_SESSION['logout_success']); ?>
    </div>
  <?php endif; ?>

  <form method="POST" action="">
    <div class="mb-3">
      <label>Email</label>
      <input type="email" name="email" class="form-control" required>
    </div>
    <div class="mb-3">
      <label>Password</label>
      <input type="password" name="password" class="form-control" required>
    </div>
    <button type="submit" class="btn btn-primary">Login</button>
  </form>

  <div class="mt-3">
    <a href="forgot_password.php">Forgot Password?</a>
  </div>
</div>

<?php include('../includes/footer.php'); ?>
