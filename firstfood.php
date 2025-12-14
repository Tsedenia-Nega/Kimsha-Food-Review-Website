<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: card.php"); 
    exit();
}

if (!isset($_SESSION['foodid'])) {
    die("No food item specified.");
}

$food_id = $_SESSION['foodid'];
require 'db.php';

// Fetch food details
$sql = "SELECT * FROM Food WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $food_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $food_name = $row['FoodName'];
    $image = $row['Image'];
    $restaurant = $row['Restaurant'];
    $location = $row['Location'];
    $category = $row['Category'];
    $price = $row['Price'];
    $additional_info = $row['AdditionalInfo'];
    $avg_rating = $row['average_rating']; 
} else {
    die("No food item found");
}
$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?php echo htmlspecialchars($food_name); ?></title>
<link rel="stylesheet" href="./first.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
<style>
.food-details {
    display: flex;
    gap: 40px;
    padding: 20px;
}
.food-image img {
    width: 400px;
    height: 400px;
    object-fit: cover;
    border-radius: 10px;
}
.food-info h2 {
    margin-bottom: 10px;
}
.food-info p {
    margin: 5px 0;
    font-size: 16px;
}
.rating-container {
    margin: 15px 0;
}
.rating-stars i {
    color: gold;
    cursor: pointer;
    font-size: 20px;
}
.comments {
    margin-top: 30px;
}
.comment {
    border-bottom: 1px solid #ccc;
    padding: 10px 0;
}
.comment p {
    margin: 2px 0;
}
</style>
</head>
<body>

<div class="food-details">
    <div class="food-image">
        <img src="<?php echo htmlspecialchars($image); ?>" alt="<?php echo htmlspecialchars($food_name); ?>">
    </div>
    <div class="food-info">
        <h2><?php echo htmlspecialchars($food_name); ?></h2>
        <p><strong>Restaurant:</strong> <?php echo htmlspecialchars($restaurant); ?></p>
        <p><strong>Location:</strong> <?php echo htmlspecialchars($location); ?></p>
        <p><strong>Category:</strong> <?php echo htmlspecialchars($category); ?></p>
        <p><strong>Cost:</strong> <?php echo htmlspecialchars($price); ?> Br.</p>
        <p><strong>Additional Info:</strong> <?php echo htmlspecialchars($additional_info); ?></p>

        <!-- Rating Form -->
        <form id="rate-form" method="POST" action="rate_food.php">
            <div class="rating-container">
                <h4>Current rating: <?php echo $avg_rating; ?> stars</h4>
                <div class="rating-stars">
                    <i class="fas fa-star" data-value="1"></i>
                    <i class="fas fa-star" data-value="2"></i>
                    <i class="fas fa-star" data-value="3"></i>
                    <i class="fas fa-star" data-value="4"></i>
                    <i class="fas fa-star" data-value="5"></i>
                </div>
                <input type="hidden" name="rating" id="rating-value" value="0">
                <button type="submit" name="rate-submit">Rate</button>
            </div>
        </form>
    </div>
</div>

<!-- Comments Section -->
<div class="comments">
    <h3>Comments</h3>
    <form id="comment-form" method="POST" action="comment_food.php">
        <textarea name="comment" placeholder="Write your comment..." required></textarea><br>
        <button type="submit" name="comment-submit">Submit</button>
    </form>

    <?php
    $conn = new mysqli($servername, $username, $password, $dbname);
    $sql = "SELECT f.comment, u.Username FROM FoodUsers f JOIN Users u ON f.user_id = u.id WHERE f.food_id = ? AND f.comment IS NOT NULL ORDER BY f.id DESC";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $food_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "<div class='comment'><p>" . htmlspecialchars($row['comment']) . "</p><p>- " . htmlspecialchars($row['Username']) . "</p></div>";
        }
    } else {
        echo "<p>No comments found.</p>";
    }
    $stmt->close();
    $conn->close();
    ?>
</div>

<script>
const stars = document.querySelectorAll('.rating-stars i');
const ratingInput = document.getElementById('rating-value');

stars.forEach(star => {
    star.addEventListener('click', () => {
        ratingInput.value = star.getAttribute('data-value');
        stars.forEach(s => s.style.color = s.getAttribute('data-value') <= ratingInput.value ? 'gold' : '#ccc');
    });
});
</script>

</body>
</html>
