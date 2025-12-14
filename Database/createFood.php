<?php
$servername = "localhost";
$username = "root";  
$password = "root_password";     
$dbname = "kemesha";


$conn = new mysqli($servername, $username, $password, $dbname);


if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "CREATE TABLE IF NOT EXISTS Food (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    FoodName VARCHAR(20) NOT NULL,
    Image VARCHAR(255),
    Portion VARCHAR(50),
    Restaurant VARCHAR(100),
    Location VARCHAR(100),
    Category VARCHAR(50),
    Price DECIMAL(10, 2),
    AdditionalInfo TEXT,
    average_rating FLOAT DEFAULT 0
)";

if ($conn->query($sql) === TRUE) {
    echo "Table 'Food' created successfully";
} else {
    echo "Error creating table: " . $conn->error;
}


$conn->close();
?>