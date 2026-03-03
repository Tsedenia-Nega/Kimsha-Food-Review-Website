<?php
session_start(); 

// 1. Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: card.php");
    exit();
}

// 2. Load the database connection fixed for Railway
require 'db.php'; 

// 3. Use the $conn created in db.php (NO NEW mysqli() HERE)
// Note: Changed 'Food' to 'food' to avoid Linux case-sensitivity issues
$sql = "SELECT * FROM food ORDER BY average_rating DESC LIMIT 6";
$result = $conn->query($sql);

// Handle query errors
if (!$result) {
    die("Query failed: " . $conn->error);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kemesha Buds - Home</title>
    <link rel="stylesheet" href="./index.css">
    <link rel="stylesheet" href="./popup.css">
    <style>
        /* Full-width Yellow Navbar */
        nav {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background-color: #FFAF00;
            color: #333;
            padding: 0 5%;
            height: 70px;
            position: relative;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        .nav-links {
            display: flex;
            list-style: none;
            gap: 35px;
            align-items: center;
            margin: 0;
            padding: 0;
        }

        .nav-links li a {
            text-decoration: none;
            color: #333;
            font-weight: 600;
            font-size: 1rem;
            transition: 0.3s;
        }

        .nav-links li a:hover {
            color: #000;
        }

        .user-avatar img {
            width: 38px;
            height: 38px;
            border-radius: 50%;
            border: 2px solid #333;
            object-fit: cover;
        }

        .back-btn {
            text-decoration: none;
            color: #333;
            font-weight: bold;
            display: flex;
            align-items: center;
            gap: 8px;
        }
    </style>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
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
        });
    </script>
</head>
<body>
  <nav>
    <div class="left-nav">
        <a href="first_index.php" class="back-btn"></a>
    </div>

    <ul class="nav-links">
        <li><a href="index.php">Home</a></li>
        <li><a href="about.html">About</a></li>
        <li><a href="first_index.php">Foods</a></li>
        <li><a href="#services">Services</a></li>
        <li><a href="#contact">Contact</a></li>
        <li class="user-avatar">
            <a href="fetchuse.php">
                <img src="./default-avatar.png" alt="User Avatar">
            </a>
        </li>
    </ul>
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
                    <input type="text" name="search_query" id="search_query" placeholder="Search..." required>
                    <label for="search_type">Type:</label>
                    <select name="search_type" id="search_type">
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
                // Adjusting column names to lowercase if necessary (e.g., image vs Image)
                // If TablePlus shows 'Image', keep 'Image'. If it shows 'image', change it.
                $img = $row['Image'] ?? $row['image'];
                $name = $row['FoodName'] ?? $row['foodname'];
                $rating = $row['average_rating'];
                $loc = $row['Location'] ?? $row['location'];
                $rest = $row['Restaurant'] ?? $row['restaurant'];
        ?>
        <div class="food-card">
            <div class="food-image">
                <img src="<?php echo $img; ?>" alt="<?php echo $name; ?>" />
            </div>
            <div class="food-details">
                <h3 class="food-title"><?php echo $name; ?></h3>
                <div class="food-rating">
                    <span class="rating"><?php echo $rating; ?> stars</span>
                </div>
                <p><?php echo $loc; ?></p>
                <p><?php echo $rest; ?> restaurant</p>
            </div>
        </div>
        <?php
            } 
        } else {
            echo "No top rated foods found.";
        }
        // $conn is closed automatically at the end, but you can leave this:
        $conn->close(); 
        ?>
        
        <a href="./second-index.php" class="btn-circle" target="_blank">
            <span class="see-more-text">See More</span>
            <img src="Frame 11.png" alt="Arrow Icon" class="arrow-icon">
        </a>
    </section>

    <footer class="contact-sec">
        </footer>

    <script src="./mainn.js"></script>
</body>
</html>