<?php
$servername = "localhost";
$username = "root";
$password = "root_password";
$dbname = "kemesha";

$conn = new mysqli($servername, $username, $password);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$conn->select_db($dbname);

$sql_users = "CREATE TABLE IF NOT EXISTS Users (
    id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    Username VARCHAR(50) NOT NULL,
    Email VARCHAR(100) NOT NULL,
    Password VARCHAR(255) NOT NULL
)";

if ($conn->query($sql_users) === TRUE) {
    echo "Table Users created successfully<br>";
} else {
    echo "Error creating Users table: " . $conn->error . "<br>";
}

$sql_food = "CREATE TABLE IF NOT EXISTS Food (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    FoodName VARCHAR(20) NOT NULL,
    Image VARCHAR(255),
    Portion VARCHAR(50),
    Restaurant VARCHAR(100),
    Location VARCHAR(100),
    Category VARCHAR(50),
    Price DECIMAL(10, 2),
    AdditionalInfo TEXT
)";

if ($conn->query($sql_food) === TRUE) {
    echo "Table Food created successfully<br>";
} else {
    echo "Error creating Food table: " . $conn->error . "<br>";
}

$sql_food_users = "CREATE TABLE IF NOT EXISTS FoodUsers (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    user_id INT(6) UNSIGNED NOT NULL,
    food_id INT(11) NOT NULL,
    rating INT(1) DEFAULT NULL,
    comment TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES Users(id) ON DELETE CASCADE,
    FOREIGN KEY (food_id) REFERENCES Food(id) ON DELETE CASCADE
)";

if ($conn->query($sql_food_users) === TRUE) {
    echo "Table FoodUsers created successfully<br>";
} else {
    echo "Error creating FoodUsers table: " . $conn->error . "<br>";
}

$conn->close();
?>
