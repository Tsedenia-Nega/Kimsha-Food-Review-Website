<?php
require 'db.php';

$conn->select_db($dbname);

// SQL to create Users table
$sql_users = "CREATE TABLE IF NOT EXISTS Users (
    id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    Username VARCHAR(50) NOT NULL,
    Email VARCHAR(100) NOT NULL,
    Password VARCHAR(255) NOT NULL,
    Avatar VARCHAR(255) NOT NULL,
    Name VARCHAR(50) NOT NULL
)";

if ($conn->query($sql_users) === TRUE) {
    echo "Table Users created successfully<br>";
} else {
    echo "Error creating Users table: " . $conn->error . "<br>";
}

// SQL query to create the comment table
$sql_comment = "CREATE TABLE comment (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT(6) UNSIGNED,
    comment TEXT,
    timestamp TIMESTAMP,
    avatar VARCHAR(255),
    name VARCHAR(50),
    FOREIGN KEY (user_id) REFERENCES Users(id)
)";

// Execute the query
if ($conn->query($sql_comment) === TRUE) {
    echo "Table 'comment' created successfully";
} else {
    echo "Error creating table: " . $conn->error;
}

// Close the connection
$conn->close();
?>