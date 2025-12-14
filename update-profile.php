<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    die("User not logged in");
}

require 'db.php';

$user_id = $_SESSION['user_id'];
$username = $_POST['name'];
$address = $_POST['address'];
$email = $_POST['email'];

$sql = "UPDATE Users SET Username = ?, Email = ?, Address = ? WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("sssi", $username, $email, $address, $user_id);

if ($stmt->execute()) {

    echo "<script>alert('Profile updated successfully');</script>";
    echo "<script>window.location.href = 'second-index.php';</script>"; 
} else {

    echo "Error updating profile: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>
