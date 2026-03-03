<?php
// 1. Include the fixed Railway connection
require 'db.php';

// 2. Get category from POST
$category = $_POST['category'] ?? 'all';

// 3. Prepare the query 
// Use lowercase 'food' and 'category' to match the database on Railway
if ($category === 'all') {
    $sql = "SELECT * FROM food";
    $stmt = $conn->prepare($sql);
} else {
    $sql = "SELECT * FROM food WHERE category = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $category);
}

// 4. Execute and fetch
if ($stmt->execute()) {
    $result = $stmt->get_result();
    $foodData = [];

    while ($row = $result->fetch_assoc()) {
        // We push the row into the array
        $foodData[] = $row;
    }

    // 5. Output as JSON for the AJAX call in secondindex.php
    header('Content-Type: application/json');
    echo json_encode($foodData);
} else {
    // If the query fails, send an error so AJAX knows
    http_response_code(500);
    echo json_encode(["error" => "Query failed: " . $conn->error]);
}

// 6. Cleanup
$stmt->close();
$conn->close();
?>