<?php
$servername = getenv('DB_SERVER') ?: 'localhost';
$username   = getenv('DB_USER') ?: 'root';
$password   = getenv('DB_PASS') ?: '';
$dbname     = getenv('DB_NAME') ?: 'kemesha';

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    // On Railway, this triggers the 500 error you saw
    die("Connection failed: " . $conn->connect_error);
}
?>