<?php
// Use Railway Environment Variables, fall back to localhost for your computer
$servername = getenv('MYSQLHOST') ?: getenv('DB_SERVER') ?: 'localhost';
$username   = getenv('MYSQLUSER') ?: getenv('DB_USER') ?: 'root';
$password   = getenv('MYSQLPASSWORD') ?: getenv('DB_PASS') ?: '';
$dbname     = getenv('MYSQLDATABASE') ?: getenv('DB_NAME') ?: 'railway';
$port       = getenv('MYSQLPORT') ?: getenv('DB_PORT') ?: 3306;

// Create connection including the PORT
$conn = new mysqli($servername, $username, $password, $dbname, $port);

// Check connection
if ($conn->connect_error) {
    // This will show the specific error if the connection fails
    die("Connection failed: " . $conn->connect_error);
}

// Set charset to utf8mb4 for better compatibility
$conn->set_charset("utf8mb4");
?>