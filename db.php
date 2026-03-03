<?php
// Railway provides these automatically if services are linked
$servername = getenv('MYSQLHOST') ?: '127.0.0.1'; 
$username   = getenv('MYSQLUSER');
$password   = getenv('MYSQLPASSWORD');
$dbname     = getenv('MYSQLDATABASE');
$port       = getenv('MYSQLPORT') ?: 3306;

// THE KEY FIX: If servername is empty or localhost, force it to 127.0.0.1
if ($servername === 'localhost') { $servername = '127.0.0.1'; }

// Connection string
$conn = new mysqli($servername, $username, $password, $dbname, $port);

if ($conn->connect_error) {
    // This will now show a 'Connection Refused' instead of 'No such file'
    die("Connection failed: " . $conn->connect_error);
}
?>