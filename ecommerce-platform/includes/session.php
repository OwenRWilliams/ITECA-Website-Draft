<?php
// ✅ This file ONLY safely starts the session

if (session_status() === PHP_SESSION_NONE) {
  session_start([
    'cookie_lifetime' => 86400,  // 1 day
    'cookie_secure'   => false,  // true only if your site uses HTTPS!
    'cookie_httponly' => true,
    'use_strict_mode' => true
  ]);

  if (!isset($_SESSION['CREATED'])) {
    session_regenerate_id(true);
    $_SESSION['CREATED'] = time();
  }
}
?>
