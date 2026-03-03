<?php
// 1. Force error reporting so we can see what's happening
error_reporting(E_ALL);
ini_set('display_errors', 1);

// 2. Fetch variables
$host = getenv('MYSQLHOST');
$user = getenv('MYSQLUSER');
$pass = getenv('MYSQLPASSWORD');
$db   = getenv('MYSQLDATABASE');
$port = getenv('MYSQLPORT');

// 3. DEBUGGER: If it fails, this will tell us WHICH ONE is empty
if (!$host || !$user || !$db) {
    echo "<h3>Environment Variable Check:</h3>";
    echo "Host: " . ($host ? "✅" : "❌ MISSING") . "<br>";
    echo "User: " . ($user ? "✅" : "❌ MISSING") . "<br>";
    echo "Pass: " . ($pass ? "✅" : "❌ (Hidden for security)") . "<br>";
    echo "DB:   " . ($db   ? "✅" : "❌ MISSING") . "<br>";
    echo "Port: " . ($port ? "✅ ($port)" : "❌ MISSING (Defaulting to 3306)") . "<br>";
    die("<hr>Please ensure these names match your Railway Variables tab exactly.");
}

// 4. Connect
$conn = new mysqli($host, $user, $pass, $db, $port ?: 3306);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// 5. Success
$conn->set_charset("utf8mb4");
?>