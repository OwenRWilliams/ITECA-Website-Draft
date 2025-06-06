<?php
$host = "localhost";
$db   = "informal_market";
$user = "admin"; // Use the dedicated user
$pass = "test1234";

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Set charset
$conn->set_charset("utf8mb4");

// Error reporting
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
?>