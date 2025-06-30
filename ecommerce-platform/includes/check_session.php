<?php
session_start();

// Redirect if not logged in
if (!isset($_SESSION['loggedin'])) {
    header("Location: /pages/login.php");
    exit();
}

// Check for inactivity (30-minute timeout)
$inactive = 1800; // 30 minutes in seconds
if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY'] > $inactive)) {
    session_unset();
    session_destroy();
    header("Location: ../login.php?timeout=1");
    exit();
}
$_SESSION['LAST_ACTIVITY'] = time(); // Update last activity
?>