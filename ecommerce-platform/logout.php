<?php
// Start session with secure parameters
session_start([
    'cookie_lifetime' => 0,
    'cookie_secure'   => true,
    'cookie_httponly' => true,
    'use_strict_mode' => true
]);

// Regenerate session ID before destruction to prevent session fixation
session_regenerate_id(true);

// Unset all session variables
$_SESSION = array();

// Delete session cookie
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(
        session_name(),
        '',
        time() - 42000,
        $params["path"],
        $params["domain"],
        $params["secure"],
        $params["httponly"]
    );
}

// Completely destroy the session
session_destroy();

// Clear client-side storage data (prevents cached data issues)
header("Clear-Site-Data: \"cache\", \"cookies\", \"storage\", \"executionContexts\"");
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Pragma: no-cache");

// Redirect with success message
$_SESSION['logout_success'] = "You have been successfully logged out.";
header("Location: ../login.php");
exit();
?>