<?php
session_start();

// Redirect logged-in users to their dashboards
if (isset($_SESSION['user_role'])) {
    switch ($_SESSION['user_role']) {
        case 'buyer':
            header("Location: pages/dashboard_user.php");
            exit;
        case 'seller':
            header("Location: pages/dashboard_seller.php");
            exit;
        case 'admin':
            header("Location: pages/dashboard_admin.php");
            exit;
    }
}

// If not logged in, show homepage
header("Location: pages/home.php");
exit;
?>
