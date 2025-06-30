<?php
$host = "sql110.infinityfree.com";
$db   = "if0_39224238_informal_market"; // Replace XXX with your actual DB name
$user = "if0_39224238";     // Your cPanel username
$pass = "Russel829499"; // Use your real password here

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Set charset
$conn->set_charset("utf8mb4");

// Error reporting
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
?>