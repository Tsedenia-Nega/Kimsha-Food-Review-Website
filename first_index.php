<?php
session_start(); 

if (!isset($_SESSION['user_id'])) {
    header("Location: card.php");
    exit();
}

require 'db.php'; 

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT * FROM Food ORDER BY average_rating DESC LIMIT 6";
$result = $conn->query($sql);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="./index.css">
    <link rel="stylesheet" href="./popup.css">
    <style>
   /* Full-width Yellow Navbar */
nav {
    display: flex;
    justify-content: space-between;
    align-items: center;
    background-color: #FFAF00; /* Yellow Theme */
    color: #333;
    padding: 0 5%;
    height: 70px; /* Consistent height */
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

/* User Icon Proportions */
.user-avatar {
    position: static; /* Removed absolute positioning to keep it in the row */
    display: flex;
    align-items: center;
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
        <a href="first_index.php" class="back-btn">
            <!-- <i class="fa-solid fa-arrow-left"></i> Back -->
        </a>
    </div>

    <ul class="nav-links">
        <li><a href="main.html">Home</a></li>
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
        ?>
        <div class="food-card">
            <div class="food-image">
                <img src="<?php echo $row['Image']; ?>" alt="<?php echo $row['FoodName']; ?>" />
            </div>
            <div class="food-details">
                <h3 class="food-title"><?php echo $row['FoodName']; ?></h3>
                <div class="food-rating">
                    <span class="rating"><?php echo $row['average_rating']; ?> stars</span>
                </div>
                <p><?php echo $row['Location']; ?></p>
                <p><?php echo $row['Restaurant']; ?> restaurant</p>
                
            </div>
        </div>
        <?php
            } 
        } else {
            echo "No top rated foods found.";
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
                <div>
                    <i class="fa-solid fa-location-dot"></i>Addis Ababa, Ethiopia
                </div>
                <div><i class="fa-solid fa-phone"></i>+2511142390</div>
                <div><i class="fa-solid fa-envelope"></i> kimshabuds23@gmail.com</div>
                <div>
                    <a><i class="fa-brands fa-facebook"></i></a>
                    <a><i class="fa-brands fa-instagram"></i></a>
                    <a><i class="fa-brands fa-twitter"></i></a>
                    <a><i class="fa-brands fa-linkedin"></i></a>
                </div>
            </div>
            <div>
                <h1>Company</h1>
                <a href=""><p>About us</p></a>
                <a href="#contact"><p class="footlink contact-link">Contact us</p></a>
                <a href=""><p>Privacy policy</p></a>
            </div>
        </div>
        <div class="secondfooter">
            <div class="col-md-6 text-center text-md-start mb-3 mb-md-0" style="text-decoration: none;">
                &copy; <a class="border-bottom" href="#">Kimsha Buds</a>, All
                Right Reserved
                <a href="#" id="return-to-top"><i class="icon-chevron-up"></i></a>
            </div>
        </div>
    </footer>
    <div class="popup-container">
        <div class="popup-card">
            <div class="popup-header">
                <h2>Comment</h2>
                <button class="close-btn">X</button>
            </div>
            <div class="popup-content">
                <div class="left-side">
                    <h3>Share your thoughts</h3>
                </div>
                <div class="right-side">
                    <h3>Add comment</h3>
                    <form id="comment-form">
    <textarea id="comment-textarea" name="comment"> </textarea>
    <div class="popup-buttons">
      <button type="submit" class="submit-btn">Submit</button>
      
    </div>
  </form>
                </div>
            </div>
        </div>
    </div>
    <script src="./mainn.js"></script>
    <script>document.getElementById('comment-form').addEventListener('submit', function(event) {
  event.preventDefault();

  const comment = document.getElementById('comment-textarea').value;

  const data = new URLSearchParams();
  data.append('comment', comment);

  fetch('save_comment.php', {
    method: 'POST',
    body: data
  })
    .then(response => response.text())
    .then(data => {
      console.log(data);
      
    })
    .catch(error => {
      console.error('Error storing comment:', error);
    });
});</script>
</body>
</html>
