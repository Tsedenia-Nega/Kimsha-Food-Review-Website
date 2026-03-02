<?php
session_start();

require 'db.php';


$username = $_POST['login-username'];
$password = $_POST['login-password'];


$sql = "SELECT * FROM Users WHERE Username = '$username' AND Password = '$password'";
$result = $conn->query($sql);

if ($result->num_rows == 1) {
    
    $_SESSION['username'] = $username;  
    header("Location: index.php");  
} else {
    echo "Invalid username or password";
}

$conn->close();
?>
