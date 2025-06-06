<?php
// Start session first
session_start();
require __DIR__ . '/../includes/db.php';

// Sanitize inputs
$email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
$password = $_POST['password'];

// Validate email
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $_SESSION['error'] = "Invalid email format";
    header("Location: login.php");
    exit();
}

// Check user in database
$stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 1) {
    $user = $result->fetch_assoc();
    
    if (password_verify($password, $user['password'])) {
        // Regenerate session ID
        session_regenerate_id(true);
        
        // Store user data
        $_SESSION['user'] = [
            'id' => $user['id'],
            'email' => $user['email'],
            'role' => $user['role'],
            'name' => $user['name'] ?? ''
        ];
        $_SESSION['loggedin'] = true;
        $_SESSION['LAST_ACTIVITY'] = time();

        // Redirect based on role
        $redirect = match($user['role']) {
            'admin' => 'dashboard_admin.php',
            'buyer' => 'dashboard_user.php',
            'seller' => 'dashboard_seller.php',
            default => 'index.php'
        };
        header("Location: $redirect");
        exit();
    }
}

// Failed login
$_SESSION['error'] = "Invalid email or password";
header("Location: login.php"); // Fixed path
exit();
?>