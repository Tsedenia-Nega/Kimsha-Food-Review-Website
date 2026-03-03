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
        }

        .nav-links {
            display: flex;
            list-style: none;
            gap: 35px;
            margin: 0;
            padding: 0;
        }

        .nav-links li a {
            text-decoration: none;
            color: #333;
            font-weight: 600;
        }

        .user-avatar-nav img {
            width: 35px;
            height: 35px;
            border-radius: 50%;
            border: 2px solid #333;
        }

        .category-list {
            margin-top: 20px;
            text-align: center;
        }

        .category-list ul {
            list-style: none;
            display: flex;
            justify-content: center;
            gap: 15px;
        }

        .category-list a {
            text-decoration: none;
            color: #333;
            padding: 8px 15px;
            border: 1px solid #FFAF00;
            border-radius: 20px;
        }

        .category-list a.active {
            background-color: #FFAF00;
            color: #fff;
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
                    dataType: 'json', // Tells jQuery to expect JSON and handle parsing
                    beforeSend: function() {
                        $('.dynamic-content').html('<p>Loading...</p>');
                    },
                    success: function(foodData) {
                        var dynamicContent = $('.dynamic-content');
                        dynamicContent.empty();

                        if (foodData && foodData.length > 0) {
                            $.each(foodData, function(index, food) {
                                // Match the exact keys from your log: FoodName, Image, Price, etc.
                                var img   = food.Image;
                                var name  = food.FoodName;
                                var price = food.Price || "N/A";
                                var rest  = food.Restaurant || "Unknown";
                                var id    = food.id;

                                var foodItem = `
                                    <div class="food-item">
                                        <div class="image-container">
                                            <img src="${img}" alt="${name}" class="small-image" onerror="this.src='default-food.png'">
                                        </div>
                                        <div class="info-container">
                                            <h3>${name}</h3>
                                            <p><strong>Cost:</strong> ${price} br.</p>
                                            <p><strong>Restaurant:</strong> ${rest}</p>
                                            <p><a href="#" class="see-more-link" data-foodid="${id}">See More</a></p>
                                        </div>
                                    </div>`;
                                dynamicContent.append(foodItem);
                            });

                            // Handle See More Click
                            $('.see-more-link').click(function(e) {
                                e.preventDefault();
                                var foodid = $(this).data('foodid');
                                $.post('set_foodid.php', { foodid: foodid }, function() {
                                    window.location.href = 'firstfood.php';
                                });
                            });
                        } else {
                            dynamicContent.html('<p>No food items found.</p>');
                        }
                    },
                    error: function(xhr) {
                        $('.dynamic-content').html('<p>Error: Could not load data from server.</p>');
                        console.log(xhr.responseText);
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
            <a href="first_index.php" style="text-decoration:none; color:#333; font-weight:bold;">← Back</a>
        </div>
        <ul class="nav-links">
            <li><a href="first_index.php">Home</a></li>
            <li><a href="about.html">About</a></li>
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

    <div class="dynamic-content"></div>

    <footer style="background:#333; color:#fff; padding:30px; margin-top:50px; text-align:center;">
        <p>&copy; Kimsha Buds, All Rights Reserved</p>
    </footer>
</body>
</html>