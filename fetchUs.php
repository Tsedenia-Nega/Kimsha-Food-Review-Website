<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    die("User not logged in");
}
require 'db.php';


$user_id = $_SESSION['user_id'];
$sql = "SELECT Username, Email, Address, Avatar FROM Users WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $username = $row["Username"] ?? '';
    $email = $row["Email"] ?? '';
    $address = $row["Address"] ?? '';
    $avatar = $row["Avatar"] ?? './default-avatar.png'; 
} else {
    die("User not found");
}

$stmt->close();
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>User Profile</title>
    <link rel="stylesheet" href="userpfp.css" />
</head>
<body>
    <script>
        function uploadAvatar() {
            document.getElementById("avatar-upload-form").submit();
        }
    </script>
    <header><button id="logout">Logout</button></header>
    <div class="user-profile">
        <div class="user-info">
        <div class="user-avatar">
            <img src="<?php echo htmlspecialchars($avatar); ?>" alt="User Avatar" id="user-avatar" />
            <form id="avatar-upload-form" method="post" enctype="multipart/form-data" action="upload-avatar.php">
                <label for="avatar-input" style="color: #FFAF00">Change Avatar</label>
                <input type="file" id="avatar-input" name="avatar" accept="image/*" style="display: none" onchange="uploadAvatar()" />
                <input type="submit" style="display: none" />
            </form>
        </div>

            <h2><?php echo htmlspecialchars($username); ?></h2>
            <p><?php echo htmlspecialchars($address); ?></p>
            <p><?php echo htmlspecialchars($email); ?></p>
        </div>

        <div class="user-form">
            <h3>Update Your Profile</h3>
            <form method="post" action="update-profile.php">
                <label for="name">Name:</label>
                <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($username); ?>" />

                <label for="address">Address:</label>
                <input type="text" id="address" name="address" value="<?php echo htmlspecialchars($address); ?>" />

                <label for="email">Email:</label>
                <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($email); ?>" />

                <button type="submit" name="save-changes">Save Changes</button>
            </form>
        </div>
    </div>

    <div class="comments-section">
        <h3>Comments</h3>
        <button id="show-comments-btn">View Comments</button>
        <div class="comments-list" style="display: none">
             <?php
    
        require 'db.php';

      $sql = "SELECT * FROM comment";
      $result = $conn->query($sql);

      if ($result->num_rows > 0) {
          while ($row = $result->fetch_assoc()) {
              
              $avatar = $row["avatar"];
              $name = $row["name"];
              $comment = $row["comment"];
            
            $date = $row["timestamp"];

              echo '<div class="comment">';
              echo '<img src="default-avatar.png" alt="Commenter Avatar" />';
              echo '<div class="comment-content">';
              echo '<h4>' . $name . '</h4>';
              echo '<p>' . $comment . '</p>';
            
            echo '<p class="comment-date">' . $date . '</p>'; 
              echo '</div>';
              echo '</div>';
          }
      } else {
          echo "No comments found.";
      }

      
      $conn->close();
      ?>
            </div>
            
            </div>
        </div>
    </div>

    
     <script>

    document.addEventListener("DOMContentLoaded", function() {
  const showCommentsBtn = document.getElementById("show-comments-btn");
  const commentsList = document.querySelector(".comments-list");

  showCommentsBtn.addEventListener("click", function() {
    if (commentsList.style.display === "none") {
      commentsList.style.display = "block";
      showCommentsBtn.textContent = "Hide Comments";
    } else {
      commentsList.style.display = "none";
      showCommentsBtn.textContent = "View Comments";
    }
  });
              document.getElementById('logout').addEventListener('click', function() {
                window.location.href = 'logout.php';
            });
});
  </script>
  </body>
  </html>


