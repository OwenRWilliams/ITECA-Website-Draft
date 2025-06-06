<?php
include('../includes/db.php');
session_start();

// Sanitize inputs
$name = filter_var(trim($_POST['name']), FILTER_SANITIZE_STRING);
$email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
$password = $_POST['password'];
$confirm_password = $_POST['confirm_password'];
$role = 'user'; // Default role

// Validate inputs
$errors = [];

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

// Check if email exists
$stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
if ($stmt->get_result()->num_rows > 0) {
    $errors[] = "Email already registered";
}

// Process registration or show errors
if (empty($errors)) {
    $hashed_password = password_hash($password, PASSWORD_BCRYPT);
    
    $stmt = $conn->prepare("INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $name, $email, $hashed_password, $role);
    
    if ($stmt->execute()) {
        $_SESSION['register_success'] = "Registration successful! Please login.";
        header("Location: ../pages/login.php");
        exit();
    } else {
        $_SESSION['register_error'] = "Database error: " . $conn->error;
    }
} else {
    $_SESSION['register_error'] = implode("<br>", $errors);
}

header("Location: ../pages/register.php");
exit();
?>