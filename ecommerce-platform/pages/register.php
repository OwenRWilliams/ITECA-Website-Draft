<?php
session_start();
require_once('../includes/db.php');

// Enable error reporting (for debugging)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$errors = [];
$success = "";

// ✅ Handle POST logic
if ($_SERVER["REQUEST_METHOD"] === "POST") {
  $name = filter_var(trim($_POST['name']), FILTER_SANITIZE_STRING);
  $email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
  $password = $_POST['password'];
  $confirm_password = $_POST['confirm_password'];

  // Validate role: only buyer or seller
  $role = isset($_POST['role']) ? $_POST['role'] : 'buyer';
  if (!in_array($role, ['buyer', 'seller'])) {
    $role = 'buyer';
  }

  // Name validation
  if (!preg_match('/^[A-Za-z ]{3,50}$/', $name)) {
    $errors[] = "Name must be 3-50 alphabetic characters";
  }

  // Email validation
  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = "Invalid email format";
  }

  // Password validation
  if (strlen($password) < 8) {
    $errors[] = "Password must be at least 8 characters";
  } elseif ($password !== $confirm_password) {
    $errors[] = "Passwords do not match";
  }

  // Check if email already exists
  $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
  $stmt->bind_param("s", $email);
  $stmt->execute();
  if ($stmt->get_result()->num_rows > 0) {
    $errors[] = "Email already registered";
  }

  // Process registration or store error
  if (empty($errors)) {
    $hashed_password = password_hash($password, PASSWORD_BCRYPT);
    $stmt = $conn->prepare("INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $name, $email, $hashed_password, $role);

    if ($stmt->execute()) {
      $success = "Registration successful! You can now login.";
      // Optional: redirect right away
      header("Location: login.php");
      exit();
    } else {
      $errors[] = "Database error: " . $conn->error;
    }
  }
}

// ✅ Redirect if logged in already
if (isset($_SESSION['user_id'])) {
  switch ($_SESSION['user_role']) {
    case 'buyer':
      header("Location: dashboard_user.php");
      exit;
    case 'seller':
      header("Location: dashboard_seller.php");
      exit;
    case 'admin':
      header("Location: dashboard_admin.php");
      exit;
  }
}

include('../includes/header.php');
?>

<div class="container mt-5">
  <div class="row justify-content-center">
    <div class="col-md-6">
      <div class="card shadow">
        <div class="card-header bg-primary text-white">
          <h4 class="mb-0">Create Account</h4>
        </div>
        <div class="card-body">
          <?php if (!empty($errors)): ?>
            <div class="alert alert-danger">
              <?php echo implode("<br>", $errors); ?>
            </div>
          <?php endif; ?>

          <?php if (!empty($success)): ?>
            <div class="alert alert-success">
              <?php echo htmlspecialchars($success); ?>
            </div>
          <?php endif; ?>

          <form method="POST" id="registerForm">
            <div class="mb-3">
              <label class="form-label">Full Name</label>
              <input type="text" name="name" class="form-control"
                     pattern="[A-Za-z ]{3,50}"
                     title="3-50 alphabetic characters"
                     required>
            </div>

            <div class="mb-3">
              <label class="form-label">Email</label>
              <input type="email" name="email" class="form-control" required>
            </div>

            <div class="mb-3">
              <label class="form-label">Password</label>
              <input type="password" name="password" id="password"
                     class="form-control" minlength="8" required>
              <div class="form-text">Minimum 8 characters</div>
            </div>

            <div class="mb-3">
              <label class="form-label">Confirm Password</label>
              <input type="password" name="confirm_password"
                     class="form-control" data-match="#password" required>
            </div>

            <!-- ✅ Buyer/Seller radio -->
            <div class="mb-3">
              <label class="form-label">Account Type</label>
              <div class="form-check">
                <input class="form-check-input" type="radio" name="role" id="buyer" value="buyer" checked>
                <label class="form-check-label" for="buyer">Buyer</label>
              </div>
              <div class="form-check">
                <input class="form-check-input" type="radio" name="role" id="seller" value="seller">
                <label class="form-check-label" for="seller">Seller</label>
              </div>
            </div>

            <div class="d-grid">
              <button type="submit" class="btn btn-primary">
                <i class="fas fa-user-plus"></i> Register
              </button>
            </div>
          </form>

          <div class="mt-3 text-center">
            Already have an account? <a href="login.php">Login here</a>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
// Client-side password match validation
document.getElementById('registerForm').addEventListener('submit', function(e) {
  const password = document.getElementById('password');
  const confirm = document.querySelector('[name="confirm_password"]');

  if (password.value !== confirm.value) {
    e.preventDefault();
    alert('Passwords do not match!');
    confirm.focus();
  }
});
</script>

<?php include('../includes/footer.php'); ?>
