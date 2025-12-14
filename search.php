<?php
require 'db.php';


$resultsPerPage = 10;

$searchQuery = $_POST['search_query'];
$searchType = $_POST['search_type'];

$page = isset($_GET['page']) ? intval($_GET['page']) : 1;

$offset = ($page - 1) * $resultsPerPage;

if ($searchType == 'name') {
  $sql = "SELECT * FROM Food WHERE FoodName LIKE '%$searchQuery%' LIMIT $offset, $resultsPerPage";
} elseif ($searchType == 'category') {
  $sql = "SELECT * FROM Food WHERE Category LIKE '%$searchQuery%' LIMIT $offset, $resultsPerPage";
} elseif ($searchType == 'restaurant') {
  $sql = "SELECT * FROM Food WHERE Restaurant LIKE '%$searchQuery%' LIMIT $offset, $resultsPerPage";
}

$result = $conn->query($sql);

if ($result->num_rows > 0) {

echo "<p style='text-align: center; color: #FFAF00; font-size: 24px;'> Results of " . $searchQuery . ":</p>";

  while ($row = $result->fetch_assoc()) {
    
    echo "<div style='display: flex; align-items: center;'>";
    echo "<img src='" . $row['Image'] . "' alt='Food Image' style='max-width: 200px; max-height: 200px;'>";
    echo "<div style='margin-left: 10px;'>";
    echo "<h3>" . $row['FoodName'] . "</h3>";
    echo "<p>Portion: " . $row['Portion'] . "</p>";
    echo "<p>Restaurant: " . $row['Restaurant'] . "</p>";
    echo "<p>Location: " . $row['Location'] . "</p>";
    echo "<p>Category: " . $row['Category'] . "</p>";
    echo "<p>Price: " . $row['Price'] . "</p>";
    echo "<p>Additional Info: " . $row['AdditionalInfo'] . "</p>";
    echo "</div>";
    echo "</div>";
  }

  $sqlCount = "SELECT COUNT(*) AS count FROM Food WHERE FoodName LIKE '%$searchQuery%'";
  $totalCount = $conn->query($sqlCount)->fetch_assoc()['count'];
  $totalPages = ceil($totalCount / $resultsPerPage);

  if ($page < $totalPages) {

    $nextPage = $page + 1;
    echo "<a href='search.php?page=$nextPage'>Next</a>";
  }
} else {

  echo "<p>No results found.</p>";
}

$conn->close();
?>