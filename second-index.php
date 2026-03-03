<?php
session_start(); 

// 1. Redirect if not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: card.php");
    exit();
}

// 2. Load the Railway connection from db.php
require 'db.php'; 

// 3. DO NOT re-create $conn. Use the one provided by db.php.
// Table name changed to lowercase 'food' for Linux compatibility.
$sql = "SELECT * FROM food ORDER BY average_rating DESC LIMIT 6";
$result = $conn->query($sql);

if (!$result) {
    die("Query failed: " . $conn->error);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kemesha Buds - Most Popular</title>
    <link rel="stylesheet" href="./index.css" />
    <link rel="stylesheet" href="./popup.css"/>
    <style>
      nav {
        display: flex;
        justify-content: space-between;
        align-items: center;
        background-color: #333;
        color: #fff;
        padding: 2.5rem;
        position: relative; 
      }

      .user-avatar {
        display: flex;
        align-items: center;
        position: absolute;
        top: 1rem;
        right: 1rem;
      }

      .user-avatar img {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        border: 2px solid #FFAF00;
      }
    </style>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            // Search AJAX
            $('#search-form').submit(function(event) {
                event.preventDefault();
                var query = $('#search_query').val();
                var type = $('#search_type').val();

                $.ajax({
                    url: 'search.php', 
                    method: 'POST',
                    data: {
                        search_query: query,
                        search_type: type
                    },
                    success: function(response) {
                        $('#search-results').html(response);
                    },
                    error: function(xhr, status, error) {
                        console.log(error);
                    }
                });
            });

            // Scroll to top logic
            $(window).scroll(function() {
                if ($(this).scrollTop() >= 50) {
                    $('#return-to-top').fadeIn(200);
                } else {
                    $('#return-to-top').fadeOut(200);
                }
            });

            $('#return-to-top').click(function() {
                $('body,html').animate({ scrollTop : 0 }, 500);
            });
        });
    </script>
</head>
<body>
  <nav>
    <div class="user-avatar">
      <a href="./fetchUs.php" target="_blank"><img src="./default-avatar.png" alt="User Avatar"></a>
    </div>
  </nav>

    <section class="home">
        <div class="left-content">
            <h1 class="title">
                <span style="color: #FFAF00">Welcome</span> to kemesha Buds
            </h1>
            <p class="notes">
                Here are some of our top picks for our delicious food.
            </p>
            <div class="search-bar"> 
                <form id="search-form">
                    <input type="text" name="search_query" id="search_query" placeholder="Search" required>
                    <label for="search_type">Type:</label>
                    <select name="search_type" id="search_type" style="border-radius: 4px; margin-right: 8px; padding: 10px 20px; border: 1px solid #ccc;">
                        <option value="name">Name</option>
                        <option value="category">Category</option>
                        <option value="restaurant">Restaurant</option>
                    </select>
                    <button type="submit">Search</button>
                </form>
            </div> 
            <div id="search-results"></div>
        </div>
        <div class="right-content">
            <img src="Hero element.png" alt="Delicious Food" />
        </div>
    </section>

    <h1 class="popular">Most Popular</h1>
    <section class="food-list">
        <?php
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                // Using null coalescing to prevent errors if column casing differs
                $foodImage = $row['Image'] ?? $row['image'];
                $foodName = $row['FoodName'] ?? $row['foodname'];
                $rating = $row['average_rating'];
                $location = $row['Location'] ?? $row['location'];
                $restaurant = $row['Restaurant'] ?? $row['restaurant'];
        ?>
        <div class="food-card">
            <div class="food-image">
                <img src="<?php echo htmlspecialchars($foodImage); ?>" alt="<?php echo htmlspecialchars($foodName); ?>" />
            </div>
            <div class="food-details">
                <h3 class="food-title"><?php echo htmlspecialchars($foodName); ?></h3>
                <div class="food-rating">
                    <span class="rating"><?php echo $rating; ?> stars</span>
                </div>
                <p><?php echo htmlspecialchars($location); ?></p>
                <p><?php echo htmlspecialchars($restaurant); ?> restaurant</p>
            </div>
        </div>
        <?php
            } 
        } else {
            echo "<p style='padding: 20px;'>No top rated foods found.</p>";
        }
        $conn->close(); 
        ?>
        
        <a href="./secondindex.php" class="btn-circle" target="_blank">
            <span class="see-more-text">See More</span>
            <img src="Frame 11.png" alt="Arrow Icon" class="arrow-icon">
        </a>
    </section>

    <footer class="contact-sec">
        <div class="footerdiv">
            <div>
                <h1>Contact us</h1>
                <div><i class="fa-solid fa-location-dot"></i> Addis Ababa, Ethiopia</div>
                <div><i class="fa-solid fa-phone"></i> +2511142390</div>
                <div><i class="fa-solid fa-envelope"></i> kimshabuds23@gmail.com</div>
            </div>
            <div>
                <h1>Company</h1>
                <a href="./about.html" target="_blank"><p>About us</p></a>
                <a href="#contact"><p class="footlink contact-link">Contact us</p></a>
                <a href="./privacy.html" target="_blank"><p>Privacy policy</p></a>
            </div>
        </div>
        <div class="secondfooter">
            <div class="col-md-6 text-center text-md-start mb-3 mb-md-0">
                &copy; <a class="border-bottom" href="#">Kimsha Buds</a>, All Rights Reserved 
                <a href="#" id="return-to-top"><i class="icon-chevron-up"></i></a>
            </div>
        </div>
    </footer>

    <div class="contact-btn-container">
        <button class="contact-btn"><a href="form.html" style="text-decoration:none; color:inherit;">Add food</a></button>
    </div>

    <script src="./admin.js"></script>
    <script src="./mainn.js"></script>
</body>
</html>