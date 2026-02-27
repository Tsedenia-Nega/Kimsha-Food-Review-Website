<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: card.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Category</title>
    <link rel="stylesheet" href="second.css">
    <style>
  
.dynamic-content {
    display: flex;
    flex-wrap: wrap; 
    justify-content: flex-start; /* Keeps items aligned to the left */
    gap: 20px; 
    padding: 20px;
    width: 100%;
}

.food-item {
    display: flex;
    flex-direction: column;
    /* This is the magic line: */
    flex: 0 0 calc(33.33% - 20px); 
    
    background: #fff;
    border-radius: 8px;
    border: 1px solid #eee;
    box-shadow: 0 2px 5px rgba(0,0,0,0.05);
    box-sizing: border-box; /* Ensures padding doesn't break the width */
}

.image-container {
    width: 100%;
    height: 200px; /* Fixed height for all images */
    overflow: hidden;
}

.small-image {
    width: 100%;
    height: 100%;
    object-fit: cover; /* Crops image to fit the box without stretching */
}



/* Full-width Yellow Navbar */
.top-nav-actions {
    background-color: #FFAF00; 
    padding: 0 5%; /* Vertical padding handled by height */
    height: 70px;  /* Fixed height for consistency */
    display: flex;
    justify-content: space-between;
    align-items: center;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
}

.nav-links {
    display: flex;
    list-style: none;
    gap: 35px;
    align-items: center; /* This centers text and images vertically */
    margin: 0;
    padding: 0;
}

.nav-links li {
    display: flex;
    align-items: center;
}

.nav-links li a {
    text-decoration: none;
    color: #333;
    font-weight: 600;
    font-size: 1rem;
    transition: 0.3s;
}

/* User Icon Proportions */
.user-avatar-nav img {
    width: 35px; /* Slightly smaller to match text height */
    height: 35px;
    border-radius: 50%;
    border: 2px solid #333;
    object-fit: cover;
    display: block; /* Removes bottom whitespace */
}


/* Category List Styling adjustment to look better under the yellow nav */
.category-list {
    margin-top: 20px;
    text-align: center;
}
.image-container img {
    width: 100%; /* Makes image fill the card width */
    height: 200px; /* Fixed height for consistency */
    object-fit: cover; /* Prevents stretching */
}

.info-container {
    padding: 15px;
}


/* Responsive fix: 2 items on tablets, 1 on phones */
@media (max-width: 900px) {
    .food-item { width: calc(50% - 20px); }
}
@media (max-width: 600px) {
    .food-item { width: 100%; }
}

</style>
<script src="second.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
    <script>
    
 //using Jquery for search  
$(document).ready(function() {
    function fetchFoodData(category) {
        $.ajax({
            url: 'fetch_food.php',
            method: 'POST',
            data: { category: category },
            beforeSend: function() {
                $('.dynamic-content').html('<p>Loading...</p>');
            },
            success: function(response) {
                var foodData = JSON.parse(response);
                var dynamicContent = $('.dynamic-content');
                dynamicContent.empty();

                if (foodData.length > 0) {
                    $.each(foodData, function(index, food) {
                        var foodItem = `
                            <div class="food-item">
                                <div class="image-container">
                                    <img src="${food.Image}" alt="${food.FoodName}" class="small-image">
                                </div>
                                <div class="info-container">
                                    <h3>${food.FoodName}</h3>
                                    <p><strong>Cost:</strong> ${food.Price} br.</p>
                                    <p><strong>Restaurant:</strong> ${food.Restaurant}</p>
                                    <p><a href="#" class="see-more-link" data-foodid="${food.id}">See More</a></p>
                                </div>
                            </div>
                        `;
                        dynamicContent.append(foodItem);
                    });

                    // Attach click event to "See More" links
                    $('.see-more-link').click(function(e) {
                        e.preventDefault();
                        var foodid = $(this).data('foodid');
                        $.post('set_foodid.php', { foodid: foodid }, function(response) {
                            window.location.href = 'firstfood.php';
                        });
                    });
                } else {
                    dynamicContent.html('<p>No food items found.</p>');
                }
            },
            error: function(xhr, status, error) {
                $('.dynamic-content').html('<p>Error loading data.</p>');
                console.error(error);
            }
        });
    }

   
    $('.category-list a').click(function(e) {
        e.preventDefault();
        $('.category-list a').removeClass('active');
        $(this).addClass('active');
        var category = $(this).data('category');
        fetchFoodData(category);
    });

    fetchFoodData('all');
});



</script>
</head>
<body>
    <header class="top-nav-actions">
        <div class="left-nav">
            <a href="first_index.php" class="back-btn">
                <!-- <i class="fa-solid fa-arrow-left"></i> Back -->
            </a>
        </div>

        <ul class="nav-links">
            <li><a href="first_index.php">Home</a></li>
            <li><a href="about.html">About</a></li>
            <li><a href="#services">Services</a></li>
            <li><a href="#contact">Contact</a></li>
            
            <li class="user-avatar-nav">
                <a href="fetchuse.php">
                    <img src="./default-avatar.png" alt="User Avatar">
                </a>
            </li>
        </ul>
    </header>
     
     <div class="category-list">
  <ul>
    <li><a href="#" data-category="all" class="active">All</a></li>
    <li><a href="#" data-category="fasting">Fasting</a></li>
    <li><a href="#" data-category="non-fasting">Non-Fasting</a></li> 
  </ul>
</div>
   <div class="image-description-layouts">
    <div class="dynamic-content"></div>
  
</div>

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
          <a href="./about.html" target="_blank" ><p>About us</p></a>
                <a href="#contact"><p class="footlink contact-link">Contact us</p></a>
                <a href="./privacy.html" target="_blank"><p>Privacy policy</p></a>
        </div>
      </div>
      <div class="secondfooter">
        <div class="col-md-6 text-center text-md-start mb-3 mb-md-0">
          &copy; <a class="border-bottom" href="#">Kimsha Buds</a>, All
          Right Reserved 
      </div>
    </footer>
<script src="second.js"></script>
</body>
</html>