<?php
require 'db.php';

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $food_name = $_POST['food-name'];
    $restaurant = $_POST['restaurant'];
    $location = $_POST['place'];
    $portion = $_POST['portion-size'];
    $price = $_POST['cost'];
    $category = $_POST['category'];
    $additional_info = $_POST['additional-info'];

    // Handle image upload
    $image = "";
    if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
        $target_dir = "uploads/";
        if (!file_exists($target_dir)) {
            mkdir($target_dir, 0755, true);
        }
        $image = $target_dir . basename($_FILES["image"]["name"]);
        if (!move_uploaded_file($_FILES["image"]["tmp_name"], $image)) {
            die("Error uploading file.");
        }
    }

    // Insert all fields into the database
    $stmt = $conn->prepare("INSERT INTO Food (FoodName, Image, Portion, Restaurant, Location, Category, Price, AdditionalInfo) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    if (!$stmt) {
        die("Prepare failed: " . $conn->error);
    }

    $stmt->bind_param("ssssssds", $food_name, $image, $portion, $restaurant, $location, $category, $price, $additional_info);

    if ($stmt->execute()) {
        echo '<script>alert("New food item added successfully"); window.location.href = "secondindex.php";</script>';
        $stmt->close();
        $conn->close();
        exit;
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>
