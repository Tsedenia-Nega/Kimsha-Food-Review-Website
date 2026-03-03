<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: card.php");
    exit();
}
// Note: We don't need require 'db.php' here because the data is loaded via AJAX from fetch_food.php
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Food Categories - Kimsha Buds</title>
    <link rel="stylesheet" href="second.css">
    <style>
        .dynamic-content {
            display: flex;
            flex-wrap: wrap; 
            justify-content: flex-start;
            gap: 20px; 
            padding: 20px;
            width: 100%;
        }

        .food-item {
            display: flex;
            flex-direction: column;
            flex: 0 0 calc(33.33% - 20px); 
            background: #fff;
            border-radius: 8px;
            border: 1px solid #eee;
            box-shadow: 0 2px 5px rgba(0,0,0,0.05);
            box-sizing: border-box;
            overflow: hidden;
            transition: transform 0.2s;
        }

        .food-item:hover {
            transform: translateY(-5px);
        }

        .image-container {
            width: 100%;
            height: 200px;
            overflow: hidden;
        }

        .small-image {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .top-nav-actions {
            background-color: #FFAF00; 
            padding: 0 5%;
            height: 70px;
            display: flex;
            justify-content: space-between;
            align-items: center;
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
        }

        .user-avatar-nav img {
            width: 35px;
            height: 35px;
            border-radius: 50%;
            border: 2px solid #333;
            object-fit: cover;
        }

        .category-list {
            margin-top: 20px;
            text-align: center;
        }

        .category-list ul {
            list-style: none;
            display: flex;
            justify-content: center;
            gap: 20px;
            padding: 0;
        }

        .category-list a {
            text-decoration: none;
            color: #333;
            padding: 8px 16px;
            border-radius: 20px;
            border: 1px solid #FFAF00;
            font-weight: bold;
        }

        .category-list a.active {
            background-color: #FFAF00;
            color: white;
        }

        .info-container { padding: 15px; }

        @media (max-width: 900px) { .food-item { flex: 0 0 calc(50% - 20px); } }
        @media (max-width: 600px) { .food-item { flex: 0 0 100%; } }
    </style>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            function fetchFoodData(category) {
                $.ajax({
                    url: 'fetch_food.php',
                    method: 'POST',
                    data: { category: category },
                    beforeSend: function() {
                        $('.dynamic-content').html('<p>Loading delicious food...</p>');
                    },
                    success: function(response) {
                        try {
                            var foodData = JSON.parse(response);
                            var dynamicContent = $('.dynamic-content');
                            dynamicContent.empty();

                            if (foodData.length > 0) {
                                $.each(foodData, function(index, food) {
                                    // Use fallbacks for column names in case they are lowercase in DB
                                    var img = food.Image || food.image;
                                    var name = food.FoodName || food.foodname;
                                    var price = food.Price || food.price;
                                    var rest = food.Restaurant || food.restaurant;

                                    var foodItem = `
                                        <div class="food-item">
                                            <div class="image-container">
                                                <img src="${img}" alt="${name}" class="small-image">
                                            </div>
                                            <div class="info-container">
                                                <h3>${name}</h3>
                                                <p><strong>Cost:</strong> ${price} br.</p>
                                                <p><strong>Restaurant:</strong> ${rest}</p>
                                                <p><a href="#" class="see-more-link" data-foodid="${food.id}">See More</a></p>
                                            </div>
                                        </div>`;
                                    dynamicContent.append(foodItem);
                                });

                                $('.see-more-link').click(function(e) {
                                    e.preventDefault();
                                    var foodid = $(this).data('foodid');
                                    $.post('set_foodid.php', { foodid: foodid }, function() {
                                        window.location.href = 'firstfood.php';
                                    });
                                });
                            } else {
                                dynamicContent.html('<p>No food items found in this category.</p>');
                            }
                        } catch (e) {
                            console.error("Error parsing JSON:", response);
                            $('.dynamic-content').html('<p>Error loading food items.</p>');
                        }
                    }
                });
            }

            $('.category-list a').click(function(e) {
                e.preventDefault();
                $('.category-list a').removeClass('active');
                $(this).addClass('active');
                fetchFoodData($(this).data('category'));
            });

            fetchFoodData('all');
        });
    </script>
</head>
<body>
    <header class="top-nav-actions">
        <div class="left-nav">
            <a href="first_index.php" class="back-btn" style="color:#333; text-decoration:none; font-weight:bold;">← Back</a>
        </div>
        <ul class="nav-links">
            <li><a href="first_index.php">Home</a></li>
            <li><a href="about.html">About</a></li>
            <li><a href="#services">Services</a></li>
            <li><a href="#contact">Contact</a></li>
            <li class="user-avatar-nav">
                <a href="fetchuse.php"><img src="./default-avatar.png" alt="User Avatar"></a>
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

    <footer class="contact-sec" style="margin-top: 50px; background: #333; color: white; padding: 40px 5%;">
        <div class="footerdiv" style="display: flex; justify-content: space-between;">
            <div>
                <h1>Contact us</h1>
                <p>Addis Ababa, Ethiopia</p>
                <p>+2511142390</p>
                <p>kimshabuds23@gmail.com</p>
            </div>
            <div>
                <h1>Company</h1>
                <p><a href="about.html" style="color:white;">About us</a></p>
                <p><a href="privacy.html" style="color:white;">Privacy policy</a></p>
            </div>
        </div>
        <div style="text-align: center; margin-top: 20px; border-top: 1px solid #555; padding-top: 20px;">
            &copy; Kimsha Buds, All Rights Reserved
        </div>
    </footer>
</body>
</html>