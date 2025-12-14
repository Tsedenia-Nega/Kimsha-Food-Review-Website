<?php
$servername = "192.168.43.231";
$username = "remoteuser";
$password = "password";

$conn = new mysqli($servername, $username, $password);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "CREATE DATABASE trial";
if ($conn->query($sql) === TRUE) {
    echo "Database 'trial' created successfully";
} else {
    echo "Error creating database: " . $conn->error;
}

$conn->close();
?>

