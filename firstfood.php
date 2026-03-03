<?php
session_start();

// 1. Redirect if not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: card.php"); 
    exit();
}

// 2. Check if a food item was selected
if (!isset($_SESSION['foodid'])) {
    die("No food item specified. Please go back and select a dish.");
}

$food_id = $_SESSION['foodid'];
require 'db.php'; // This provides the $conn object

// 3. Fetch food details
// Changed 'Food' to 'food' for Railway/Linux compatibility
$sql = "SELECT * FROM food WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $food_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    // Handling potential case-sensitivity in column names
    $food_name       = $row['FoodName'] ?? $row['foodname'];
    $image           = $row['Image'] ?? $row['image'];
    $restaurant      = $row['Restaurant'] ?? $row['restaurant'];
    $location        = $row['Location'] ?? $row['location'];
    $category        = $row['Category'] ?? $row['category'];
    $price           = $row['Price'] ?? $row['price'];
    $additional_info = $row['AdditionalInfo'] ?? $row['additional_info'];
    $avg_rating      = $row['average_rating']; 
} else {
    die("No food item found in database.");
}
$stmt->close();
// Note: We leave $conn open here because we need it for comments below
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
    /* ... (Your existing styles are great, keeping them) ... */
    body {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        background-color: #f8f9fa;
        color: #333;
        line-height: 1.6;
        margin: 0;
        padding: 40px 10%;
    }
    .food-container {
        background: #fff;
        border-radius: 15px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.08);
        padding: 30px;
        margin-bottom: 40px;
    }
    .food-details { display: flex; gap: 50px; align-items: flex-start; }
    .food-image img {
        width: 450px; height: 450px; object-fit: cover;
        border-radius: 12px; box-shadow: 0 8px 15px rgba(0,0,0,0.1);
    }
    .food-info { flex: 1; }
    .food-info h2 {
        font-size: 2.5rem; margin-top: 0; color: #222;
        border-bottom: 3px solid #FFAF00; display: inline-block;
        padding-bottom: 5px; margin-bottom: 20px;
    }
    .rating-container {
        margin-top: 25px; background: #fff9ed; padding: 20px;
        border-radius: 10px; border-left: 5px solid #FFAF00;
    }
    .rating-stars i { font-size: 24px; margin-right: 5px; cursor: pointer; transition: transform 0.2s; }
    .rating-stars i:hover { transform: scale(1.2); }
    
    button[name="rate-submit"], button[name="comment-submit"] {
        background-color: #FFAF00; color: #fff; border: none;
        padding: 10px 25px; font-weight: bold; border-radius: 5px;
        cursor: pointer; transition: 0.3s;
    }
    .comment {
        background: #fff; padding: 20px; border-radius: 10px;
        margin-bottom: 15px; border-left: 4px solid #ddd;
    }
    .username { font-weight: bold; color: #FFAF00; }
</style>
</head>
<body>

<div class="food-container">
    <div class="food-details">
        <div class="food-image">
            <img src="<?php echo htmlspecialchars($image); ?>" alt="<?php echo htmlspecialchars($food_name); ?>" onerror="this.src='default-food.png'">
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
                    <h4>Current Score: <span style="color:#FFAF00"><?php echo number_format($avg_rating, 1); ?>/5</span></h4>
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
        <textarea name="comment" placeholder="How was your meal? Share your experience..." required style="width: 100%; height: 100px; padding: 10px; border-radius: 8px; border: 1px solid #ddd;"></textarea>
        <button type="submit" name="comment-submit" style="margin-top: 10px;">Post Comment</button>
    </form>

    <div class="comment-list">
        <?php
        // Use the ALREADY OPEN $conn. Don't re-create it.
        // Changed table names to lowercase to match typical Railway setups
        $sql_comments = "SELECT f.comment, u.Username 
                         FROM foodusers f 
                         JOIN users u ON f.user_id = u.id 
                         WHERE f.food_id = ? AND f.comment IS NOT NULL 
                         ORDER BY f.id DESC";
        
        $stmt_c = $conn->prepare($sql_comments);
        $stmt_c->bind_param("i", $food_id);
        $stmt_c->execute();
        $res_comments = $stmt_c->get_result();

        if ($res_comments->num_rows > 0) {
            while ($c_row = $res_comments->fetch_assoc()) {
                $user_display = $c_row['Username'] ?? $c_row['username'];
                echo "<div class='comment'>
                        <p>" . htmlspecialchars($c_row['comment']) . "</p>
                        <p class='username'><i class='fas fa-user-circle'></i> " . htmlspecialchars($user_display) . "</p>
                      </div>";
            }
        } else {
            echo "<p style='color:#888; text-align:center;'>No comments yet. Be the first to review!</p>";
        }
        $stmt_c->close();
        $conn->close();
        ?>
    </div>
</div>

<script>
const stars = document.querySelectorAll('.rating-stars i');
const ratingInput = document.getElementById('rating-value');

stars.forEach(star => {
    star.addEventListener('click', () => {
        const val = star.getAttribute('data-value');
        ratingInput.value = val;
        stars.forEach(s => {
            s.style.color = s.getAttribute('data-value') <= val ? '#FFAF00' : '#ccc';
        });
    });
});
</script>

</body>
</html>