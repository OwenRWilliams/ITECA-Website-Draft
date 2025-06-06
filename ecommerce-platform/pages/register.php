<?php 
include('../includes/header.php');

// Redirect logged-in users
if (isset($_SESSION['user'])) {
    header("Location: ../pages/dashboard.php");
    exit();
}
?>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">Create Account</h4>
                </div>
                <div class="card-body">
                    <?php if (isset($_SESSION['register_error'])): ?>
                        <div class="alert alert-danger"><?php 
                            echo $_SESSION['register_error']; 
                            unset($_SESSION['register_error']); 
                        ?></div>
                    <?php endif; ?>

                    <form method="POST" action="../actions/register_action.php" id="registerForm">
                        <div class="mb-3">
                            <label class="form-label">Full Name</label>
                            <input type="text" name="name" class="form-control" 
                                   pattern="[A-Za-z ]{3,50}" 
                                   title="3-50 alphabetic characters"
                                   required>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" class="form-control" 
                                   required>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Password</label>
                            <input type="password" name="password" id="password" 
                                   class="form-control" 
                                   minlength="8"
                                   required>
                            <div class="form-text">Minimum 8 characters</div>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Confirm Password</label>
                            <input type="password" name="confirm_password" 
                                   class="form-control" 
                                   data-match="#password"
                                   required>
                        </div>
                        
                        <input type="hidden" name="role" value="buyer">
                        
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