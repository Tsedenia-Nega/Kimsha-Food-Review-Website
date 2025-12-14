<?php
require 'db.php';

$category = $_POST['category'] ?? 'all';

if ($category === 'all') {
    $sql = "SELECT * FROM Food"; 
} else {
    $sql = "SELECT * FROM Food WHERE Category = ?";
}

$stmt = $conn->prepare($category === 'all' ? $sql : $sql);
if ($category !== 'all') {
    $stmt->bind_param("s", $category);
}
$stmt->execute();
$result = $stmt->get_result();

$foodData = [];
while ($row = $result->fetch_assoc()) {
    $foodData[] = $row;
}

echo json_encode($foodData);
$conn->close();
?>
