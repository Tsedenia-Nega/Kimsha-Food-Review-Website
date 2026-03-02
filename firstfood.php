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
    /* Global Reset & Body */
    body {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        background-color: #f8f9fa;
        color: #333;
        line-height: 1.6;
        margin: 0;
        padding: 40px 10%;
    }

    /* Main Container Card */
    .food-container {
        background: #fff;
        border-radius: 15px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.08);
        padding: 30px;
        margin-bottom: 40px;
    }

    .food-details {
        display: flex;
        gap: 50px;
        align-items: flex-start;
    }

    /* Image Styling */
    .food-image img {
        width: 450px;
        height: 450px;
        object-fit: cover;
        border-radius: 12px;
        box-shadow: 0 8px 15px rgba(0,0,0,0.1);
    }

    /* Info Content */
    .food-info {
        flex: 1;
    }

    .food-info h2 {
        font-size: 2.5rem;
        margin-top: 0;
        color: #222;
        border-bottom: 3px solid #FFAF00;
        display: inline-block;
        padding-bottom: 5px;
        margin-bottom: 20px;
    }

    .food-info p {
        font-size: 1.1rem;
        margin: 12px 0;
        color: #555;
    }

    .food-info strong {
        color: #333;
        width: 140px;
        display: inline-block;
    }

    /* Rating Section */
    .rating-container {
        margin-top: 25px;
        background: #fff9ed;
        padding: 20px;
        border-radius: 10px;
        border-left: 5px solid #FFAF00;
    }

    .rating-stars {
        margin: 10px 0;
    }

    .rating-stars i {
        font-size: 24px;
        margin-right: 5px;
        transition: transform 0.2s;
    }

    .rating-stars i:hover {
        transform: scale(1.2);
    }

    button[name="rate-submit"], button[name="comment-submit"] {
        background-color: #FFAF00;
        color: #fff;
        border: none;
        padding: 10px 25px;
        font-weight: bold;
        border-radius: 5px;
        cursor: pointer;
        transition: background 0.3s;
    }

    button:hover {
        background-color: #e69e00;
    }

    /* Comment Section Styling */
    .comments {
        max-width: 800px;
        margin: 0 auto;
    }

    .comments h3 {
        font-size: 1.8rem;
        margin-bottom: 20px;
        color: #222;
    }

    #comment-form textarea {
        width: 100%;
        height: 120px;
        padding: 15px;
        border: 1px solid #ddd;
        border-radius: 10px;
        font-family: inherit;
        font-size: 1rem;
        margin-bottom: 15px;
        resize: vertical;
        box-sizing: border-box;
    }

    .comment-list {
        margin-top: 30px;
    }

    .comment {
        background: #fff;
        padding: 20px;
        border-radius: 10px;
        margin-bottom: 15px;
        border-left: 4px solid #ddd;
        box-shadow: 0 2px 5px rgba(0,0,0,0.03);
    }

    .comment p:first-child {
        font-size: 1.05rem;
        color: #444;
        margin-bottom: 8px;
    }

    .comment .username {
        font-weight: bold;
        font-size: 0.9rem;
        color: #FFAF00;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    /* Responsive */
    @media (max-width: 992px) {
        .food-details { flex-direction: column; align-items: center; }
        .food-image img { width: 100%; height: auto; }
        body { padding: 20px; }
    }
</style>
</head>
<body>

<div class="food-container">
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
            <p><strong>Info:</strong> <?php echo htmlspecialchars($additional_info); ?></p>

            <form id="rate-form" method="POST" action="rate_food.php">
                <div class="rating-container">
                    <h4>Current Score: <span style="color:#FFAF00"><?php echo $avg_rating; ?>/5</span></h4>
                    <div class="rating-stars">
                        <i class="fas fa-star" data-value="1" style="color:#ccc"></i>
                        <i class="fas fa-star" data-value="2" style="color:#ccc"></i>
                        <i class="fas fa-star" data-value="3" style="color:#ccc"></i>
                        <i class="fas fa-star" data-value="4" style="color:#ccc"></i>
                        <i class="fas fa-star" data-value="5" style="color:#ccc"></i>
                    </div>
                    <input type="hidden" name="rating" id="rating-value" value="0">
                    <button type="submit" name="rate-submit">Submit Rating</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="comments">
    <h3>Community Feedback</h3>
    <form id="comment-form" method="POST" action="comment_food.php">
        <textarea name="comment" placeholder="How was your meal? Share your experience..." required></textarea>
        <button type="submit" name="comment-submit">Post Comment</button>
    </form>

    <div class="comment-list">
        <?php
        $conn = new mysqli($servername, $username, $password, $dbname);
        $sql = "SELECT f.comment, u.Username FROM FoodUsers f JOIN Users u ON f.user_id = u.id WHERE f.food_id = ? AND f.comment IS NOT NULL ORDER BY f.id DESC";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $food_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<div class='comment'>
                        <p>" . htmlspecialchars($row['comment']) . "</p>
                        <p class='username'><i class='fas fa-user-circle'></i> " . htmlspecialchars($row['Username']) . "</p>
                      </div>";
            }
        } else {
            echo "<p style='color:#888; text-align:center;'>No comments yet. Be the first to review!</p>";
        }
        $stmt->close();
        $conn->close();
        ?>
    </div>
</div>

<script>
const stars = document.querySelectorAll('.rating-stars i');
const ratingInput = document.getElementById('rating-value');

stars.forEach(star => {
    star.addEventListener('click', () => {
        ratingInput.value = star.getAttribute('data-value');
        stars.forEach(s => s.style.color = s.getAttribute('data-value') <= ratingInput.value ? '#FFAF00' : '#ccc');
    });
});
</script>

</body>
</html>
