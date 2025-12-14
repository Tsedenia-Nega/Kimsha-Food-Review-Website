<?php


$servername = "localhost"; 
$username = "root"; 
$password = "";
$dbname = "kemesha";git push -u origin main
$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

?>