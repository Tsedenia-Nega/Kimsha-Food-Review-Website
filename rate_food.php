<?php
session_start();
if (!isset($_SESSION['user_id']) || !isset($_SESSION['foodid'])) {
    header("Location: card.php");
    exit();
}

require 'db.php';

$food_id = $_SESSION['foodid'];
$user_id = $_SESSION['user_id'];

// Check if rating was submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['rating'])) {
    $rating = floatval($_POST['rating']); // sanitize input

    $conn = new mysqli($servername, $username, $password, $dbname);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Check if the user already rated this food
    $stmt = $conn->prepare("SELECT * FROM FoodUsers WHERE user_id = ? AND food_id = ?");
    $stmt->bind_param("ii", $user_id, $food_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Update existing rating
        $stmt = $conn->prepare("UPDATE FoodUsers SET rating = ? WHERE user_id = ? AND food_id = ?");
        $stmt->bind_param("dii", $rating, $user_id, $food_id);
    } else {
        // Insert new rating
        $stmt = $conn->prepare("INSERT INTO FoodUsers (user_id, food_id, rating) VALUES (?, ?, ?)");
        $stmt->bind_param("iid", $user_id, $food_id, $rating);
    }

    $stmt->execute();
    $stmt->close();

    // Update average rating in Food table
    $stmt = $conn->prepare("SELECT AVG(rating) AS avg_rating FROM FoodUsers WHERE food_id = ?");
    $stmt->bind_param("i", $food_id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($row = $result->fetch_assoc()) {
        $avg_rating = round($row['avg_rating'], 1);
        $updateStmt = $conn->prepare("UPDATE Food SET average_rating = ? WHERE id = ?");
        $updateStmt->bind_param("di", $avg_rating, $food_id);
        $updateStmt->execute();
        $updateStmt->close();
    }
    $stmt->close();
    $conn->close();

    // Redirect back to firstfood.php
    header("Location: firstfood.php");
    exit();
} else {
    // If no rating submitted, redirect back
    header("Location: firstfood.php");
    exit();
}
?>
