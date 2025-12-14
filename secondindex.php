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
  
.food-item {
  /* display: inline-block; */
  display: flex;
  flex-direction: row;
  align-items: center;
  padding-left: 100px;
  gap: 10px;
  width: 33.3%; /* Set the width to 33.33% to display three items in one line */
  





 
}

.food-item {
  /* Styles for each food item */
}

.info-container {
  flex-grow: 2;
  padding-left: 30px;
  padding-top: 0px;
  margin-top: 1px;
  /* margin-top: 5px; Adjust the margin-top value as needed */
}

.small-image{
  width: 300px;
  height: 300px;
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
     <h1 class="categori">Categories</h1>
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