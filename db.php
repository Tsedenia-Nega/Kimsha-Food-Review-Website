<?php
// Force Railway to use Network addresses, not local sockets
$servername = getenv('MYSQLHOST');
$username   = getenv('MYSQLUSER');
$password   = getenv('MYSQLPASSWORD');
$dbname     = getenv('MYSQLDATABASE');
$port       = getenv('MYSQLPORT') ?: 3306;

// If these are empty, Railway isn't passing the variables to your app
if (!$servername) {
    die("Error: Railway Environment Variables are missing. Check your Variables tab!");
}

// THE FIX: The 5th parameter (port) is MANDATORY on Railway
$conn = new mysqli($servername, $username, $password, $dbname, $port);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$conn->set_charset("utf8mb4");
?>