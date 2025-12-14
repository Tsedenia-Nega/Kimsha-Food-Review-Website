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
    <header>
        <form action="logout.php" method="post">
            <button type="submit" id="logout">Logout</button>
        </form>
    </header>
    <script>
        function uploadAvatar() {
            document.getElementById("avatar-upload-form").submit();
        }
    </script>

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
            <form method="post" action="update.php">
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
</body>
</html>
