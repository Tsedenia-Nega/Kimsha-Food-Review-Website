<?php
session_start();

// 1. Basic Security Check
if (!isset($_SESSION['user_id']) || !isset($_SESSION['foodid'])) {
    header("Location: card.php");
    exit();
}

// 2. Load the connection from db.php (This provides the $conn variable)
require 'db.php';

$food_id = $_SESSION['foodid'];
$user_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['comment'])) {
    $comment = trim($_POST['comment']);

    if (!empty($comment)) {
        // Sanitize the comment
        $comment = htmlspecialchars($comment); 

        // 3. Use the $conn provided by db.php. (DO NOT use 'new mysqli' here)
        // Note: Table name changed to lowercase 'foodusers' for Railway/Linux
        $stmt = $conn->prepare("INSERT INTO foodusers (user_id, food_id, comment) VALUES (?, ?, ?)");
        
        if ($stmt) {
            $stmt->bind_param("iis", $user_id, $food_id, $comment);

            if ($stmt->execute()) {
                $stmt->close();
                $conn->close();
                // Redirect back to the food detail page
                header("Location: firstfood.php");
                exit();
            } else {
                die("Error executing query: " . $stmt->error);
            }
        } else {
            die("Error preparing statement: " . $conn->error);
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