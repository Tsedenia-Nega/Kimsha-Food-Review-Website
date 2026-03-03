<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Use the exact names Railway provides
$host = getenv('MYSQLHOST');
$user = getenv('MYSQLUSER');
$pass = getenv('MYSQLPASSWORD');
$db   = getenv('MYSQLDATABASE');
$port = getenv('MYSQLPORT') ?: 3306;

// Connection Refused often happens if the host is empty or 'localhost'
// This ensures we have a real address
if (!$host) {
    die("Database host is empty. Check Railway Variables.");
}

// THE CONNECT LINE (Line 25)
$conn = new mysqli($host, $user, $pass, $db, $port);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$conn->set_charset("utf8mb4");
?>