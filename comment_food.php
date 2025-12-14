<?php
session_start();
if (!isset($_SESSION['user_id']) || !isset($_SESSION['foodid'])) {
    header("Location: card.php");
    exit();
}

require 'db.php';

$food_id = $_SESSION['foodid'];
$user_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['comment'])) {
    $comment = trim($_POST['comment']);

    if (!empty($comment)) {
        $comment = htmlspecialchars($comment); // sanitize input

        $conn = new mysqli($servername, $username, $password, $dbname);
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // Insert comment
        $stmt = $conn->prepare("INSERT INTO FoodUsers (user_id, food_id, comment) VALUES (?, ?, ?)");
        $stmt->bind_param("iis", $user_id, $food_id, $comment);

        if ($stmt->execute()) {
            $stmt->close();
            $conn->close();
            // Redirect back to firstfood.php
            header("Location: firstfood.php");
            exit();
        } else {
            die("Error inserting comment: " . $stmt->error);
        }
    } else {
        // Empty comment, just redirect back
        header("Location: firstfood.php");
        exit();
    }
} else {
    // If accessed without POST, redirect back
    header("Location: firstfood.php");
    exit();
}
?>
