<?php
session_start();
require_once '../includes/db.php';

// Check if user is logged in and is admin
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header('Location: /pages/login.php');
    exit;
}

// Check if id is provided and is a number
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header('Location: /pages/dashboard_admin.php?error=InvalidUserID');
    exit;
}

$user_id = (int)$_GET['id'];

// Prevent admin from deleting themselves (optional but recommended)
if ($user_id === $_SESSION['user']['id']) {
    header('Location: /pages/dashboard_admin.php?error=CannotDeleteSelf');
    exit;
}

try {
    $stmt = $pdo->prepare("DELETE FROM users WHERE id = ?");
    $stmt->execute([$user_id]);

    if ($stmt->rowCount()) {
        header('Location: /pages/dashboard_admin.php?success=UserDeleted');
    } else {
        header('Location: /pages/dashboard_admin.php?error=UserNotFound');
    }
} catch (PDOException $e) {
    // Log error or handle as needed
    header('Location: /pages/dashboard_admin.php?error=DatabaseError');
}
exit;
