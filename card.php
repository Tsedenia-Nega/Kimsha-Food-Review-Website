<?php
// 1. FORCING ERRORS TO SHOW (Fixes the blank 500 error screen)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start(); 

// 2. LOAD DATABASE CONNECTION
// Ensure your db.php uses getenv() for Railway variables!
require 'db.php'; 

// HANDLE SIGNUP
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['signup-submit'])) {
    $user = $_POST['signup-username'];
    $email = $_POST['signup-email'];
    $pass = $_POST['signup-password'];
    $confirm_pass = $_POST['confirm-password'];

    if ($pass !== $confirm_pass) {
        echo '<script>alert("Passwords do not match.");</script>';
    } else {
        // NOTE: Table name changed to lowercase 'users' to match your Railway database
        // Also ensure column names (Username, Email, etc.) match your TablePlus casing exactly
        $stmt = $conn->prepare("INSERT INTO users (Username, Email, Password, Address, Avatar) VALUES (?, ?, ?, NULL, NULL)");
        
        if ($stmt === false) {
            die("Prepare failed: " . $conn->error);
        }

        $stmt->bind_param("sss", $user, $email, $pass);

        if ($stmt->execute()) {
            header("Location: first_index.php");
            exit();
        } else {
            echo '<script>alert("Error: ' . $stmt->error . '");</script>';
        }
        $stmt->close();
    }
}

// HANDLE LOGIN 
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['login-submit'])) {
    $login_user = $_POST['login-username'];
    $login_pass = $_POST['login-password'];

    // NOTE: Table name changed to lowercase 'users'
    $stmt = $conn->prepare("SELECT id, Username FROM users WHERE Username = ? AND Password = ?");
    
    if ($stmt === false) {
        die("Prepare failed: " . $conn->error);
    }

    $stmt->bind_param("ss", $login_user, $login_pass);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        $_SESSION['user_id'] = $row['id'];
        $_SESSION['username'] = $row['Username'];

        // Redirect based on user type
        if ($login_user === 'admin') {
            header("Location: second-index.php");
        } else {
            header("Location: first_index.php");
        }
        exit();
    } else {
        echo '<script>alert("Login failed. Invalid username or password.");</script>';
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html>
  <head>
    <title>Login Form</title>
    <style>
      body {
        background-image: url(./img/burger.jpg);
        background-size: cover;
        background-position: center;
        font-family: Arial, sans-serif;
        margin: 0;
        padding: 0;
      }

      .container {
        display: flex;
        align-items: center;
        justify-content: center;
        height: 100vh;
      }

      .glassmorphic-card {
        display: flex;
        align-items: center;
        width: 500px;
        padding: 40px;
        background: rgba(0, 0, 0, 0.5);
        border-radius: 20px;
        border: 2px solid rgba(255, 255, 255, 0.2);
        backdrop-filter: blur(10px);
        box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
      }

      .glassmorphic-card .image-container {
        flex: 1;
        text-align: center;
      }

      .glassmorphic-card .image-container img {
        max-width: 100%;
        height: auto;
        border-radius: 5px;
      }

      .glassmorphic-card .form-container {
        flex: 1;
        padding-left: 40px;
      }

      .glassmorphic-card h2 {
        text-align: center;
        color: #fff;
        margin-bottom: 20px;
      }

      .glassmorphic-card form {
        margin-top: 20px;
      }

      .glassmorphic-card input[type="text"],
      .glassmorphic-card input[type="password"],
      .glassmorphic-card input[type="email"] {
        width: 100%;
        padding: 10px;
        margin-bottom: 10px;
        border: none;
        border-radius: 5px;
        background: rgba(255, 255, 255, 0.3);
        color: #fff;
      }

      .glassmorphic-card input[type="submit"] {
        width: 100%;
        padding: 10px;
        border: none;
        border-radius: 5px;
        background: #000;
        color: #ffaf00;
        cursor: pointer;
      }

      .glassmorphic-card input[type="submit"]:hover {
        background: #111;
      }

      .glassmorphic-card .create-account,
      .glassmorphic-card .login-link {
        display: block;
        text-align: right;
        color: #fff;
        font-size: 14px;
        text-decoration: none;
        margin-top: 10px;
      }
    </style>
  </head>
  <body>
    <div class="container">
      <div class="glassmorphic-card" id="login-card">
        <div class="image-container">
          <img src="burger_sandwich_PNG4135 2.png" alt="Image" />
        </div>
        <div class="form-container">
          <h2>Login <span style="color: #ffaf00">Now</span></h2>

          <form id="login-form" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <input type="text" placeholder="Username" name="login-username" required />
            <input type="password" placeholder="Password" name="login-password" required />
            <input type="submit" value="Login" name="login-submit" />
            <a href="#" class="create-account">Create a New Account</a>
          </form>
        </div>
      </div>

      <div class="glassmorphic-card" id="signup-card" style="display: none">
        <div class="image-container">
          <img src="burger_sandwich_PNG4135 2.png" alt="Image" />
        </div>
        <div class="form-container">
          <h2>Sign <span style="color: #ffaf00">Up</span></h2>

          <form id="signup-form" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <input type="text" placeholder="Username" name="signup-username" required />
            <input type="email" placeholder="Email" name="signup-email" required />
            <input type="password" placeholder="Password" name="signup-password" required />
            <input type="password" placeholder="Confirm Password" name="confirm-password" required />
            <input type="submit" value="Sign Up" name="signup-submit" />
            <a href="#" class="login-link">Already have an account? Login</a>
          </form>
        </div>
      </div>
    </div>

    <script>
      const loginCard = document.getElementById("login-card");
      const signupCard = document.getElementById("signup-card");
      const createAccountLink = document.querySelector(".create-account");
      const loginLink = document.querySelector(".login-link");

      createAccountLink.addEventListener("click", (e) => {
        e.preventDefault();
        loginCard.style.display = "none";
        signupCard.style.display = "flex";
      });

      loginLink.addEventListener("click", (e) => {
        e.preventDefault();
        signupCard.style.display = "none";
        loginCard.style.display = "flex";
      });
    </script>
  </body>
</html>