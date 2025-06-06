<?php 
include('../../includes/header.php'); 
?>
<div class="container mt-5">
  <h2>Login</h2>
  
  <?php if (isset($_SESSION['login_error'])): ?>
    <div class="alert alert-danger"><?php echo $_SESSION['login_error']; unset($_SESSION['login_error']); ?></div>
  <?php endif; ?>
  
  <?php if (isset($_SESSION['logout_success'])): ?>
    <div class="alert alert-success"><?php 
        echo $_SESSION['logout_success']; 
        unset($_SESSION['logout_success']);
    ?></div>
  <?php endif; ?>

  <form method="POST" action="../../actions/login_action.php">
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