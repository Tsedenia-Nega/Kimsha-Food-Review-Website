<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    die("User not logged in");
}

require 'db.php';


if (isset($_FILES["avatar"]) && $_FILES["avatar"]["error"] == UPLOAD_ERR_OK) {
    $user_id = $_SESSION['user_id'];
    
    $file_name = $_FILES["avatar"]["name"];
    $file_tmp = $_FILES["avatar"]["tmp_name"];
    $file_size = $_FILES["avatar"]["size"];
    $file_type = $_FILES["avatar"]["type"];

    $allowed_types = array("image/jpeg", "image/png", "image/gif");
    if (!in_array($file_type, $allowed_types)) {
        die("Error: Only JPG, PNG, and GIF files are allowed.");
    }

    $upload_dir = "avatars/";
    $avatar_path = $upload_dir . $file_name;
    if (move_uploaded_file($file_tmp, $avatar_path)) {

        $conn = new mysqli($servername, $username, $password, $dbname);
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        $sql = "UPDATE Users SET Avatar = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("si", $avatar_path, $user_id);

        if ($stmt->execute()) {

            header("Location: first_index.php");
            exit();
        } else {
            echo "Error updating avatar: " . $stmt->error;
        }

        $stmt->close();
        $conn->close();
    } else {
        echo "Error uploading file.";
    }
} else {
    echo "Error: File not uploaded or an error occurred.";
}
?>
