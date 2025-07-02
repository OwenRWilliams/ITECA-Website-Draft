<?php
if (session_status() === PHP_SESSION_NONE) {
  session_start([
    'cookie_lifetime' => 86400,
    'cookie_secure'   => true,  // Use HTTPS in production
    'cookie_httponly' => true,
    'use_strict_mode' => true
  ]);

  if (!isset($_SESSION['CREATED'])) {
    session_regenerate_id(true);
    $_SESSION['CREATED'] = time();
  }
}

// If page forgets $level, default it
if (!isset($level)) {
  $level = '';
}

// Figure out dashboard link for logged-in users
if (isset($_SESSION['user_role'])) {
  switch ($_SESSION['user_role']) {
    case 'admin':
      $dashboard_link = "/pages/dashboard_admin.php";
      break;
    case 'seller':
      $dashboard_link = "/pages/dashboard_seller.php";
      break;
    default:
      $dashboard_link = "/pages/dashboard_user.php";
  }
} else {
  $dashboard_link = "/pages/login.php";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Nozama</title>

  <!-- Fonts & Icons -->
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

  <!-- CSS -->
  <link rel="stylesheet" href="<?php echo $level; ?>assets/style.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">

  <!-- Favicon -->
  <link rel="icon" href="<?php echo $level; ?>assets/img/favicon.ico" type="image/x-icon">

  <!-- Scripts -->
  <script src="<?php echo $level; ?>assets/script.js" defer></script>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <div class="container-fluid">
    <a class="navbar-brand" href="/pages/home.php">Nozama</a>
    <div class="collapse navbar-collapse">
      <ul class="navbar-nav ms-auto">
        <?php if (!isset($_SESSION['user_role'])): ?>
          <li class="nav-item">
            <a class="nav-link" href="/pages/login.php">Login</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="/pages/register.php">Register</a>
          </li>
        <?php else: ?>
          <li class="nav-item">
            <a class="nav-link" href="<?php echo $dashboard_link; ?>">Dashboard</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="/logout.php">Logout</a>
          </li>
        <?php endif; ?>
      </ul>
    </div>
  </div>
</nav>
