<?php 
include_once('dbconnect.php'); 
session_start(); 
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="styleslogin.css">
</head>
<body>
    <div class="header">
        <img src="logopizza1.png" alt="Logo">
        <div class="auth-links">
            <a href="register.php">Register</a>
            <a href="login.php">Login</a>
        </div>
    </div>
    
    <div class="main-content">
        <div class="banner">
            <img src="cover1.jpg" alt="Banner">
            <div class="login-box">
                <div class="login-form">
                    <h2>Login</h2>
                    <form action="" method="post">
                        <p><input type="email" name="email" placeholder="Email" required></p>
                        <p><input type="password" name="pass" placeholder="Password" required></p>
                        <input type="submit" value="Login" name="log">
                        <p class="register-text">Don't have an account? <a href="register.php">Register here</a></p>
                    </form>
                </div>
            </div>
        </div>

        <div class="image-grid">
            <a href="index.php">
                <img src="photo1.png" alt="Image 1">
            </a>
            <a href="index.php">
                <img src="photo2.png" alt="Image 2">
            </a>
        </div>

        <div class="view-deals">
            <a href="index.php">View Deals</a>
        </div>
    </div>


    <?php
    if (isset($_POST['log'])) {
        $email = $_POST['email'];
        $pass_word = $_POST['pass'];

        $query = "SELECT * FROM users WHERE email = ?";
        $stmt = mysqli_prepare($conn, $query);

        if ($stmt) {
            mysqli_stmt_bind_param($stmt, "s", $email);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);

            if (mysqli_num_rows($result) == 1) {
                $user = mysqli_fetch_assoc($result);

                if (password_verify($pass_word, $user['password'])) {
                    $_SESSION['user_id'] = $user['id']; 
                    $_SESSION['role'] = $user['role'];

                    if ($user['role'] == 'manager') {
                        header("Location: index.php");
                    } else {
                        header("Location: index.php");
                    }
                    exit();
                } else {
                    echo "<script>window.alert('Invalid password');</script>";
                }
            } else {
                echo "<script>window.alert('Email not found');</script>";
            }
        } else {
            echo "<script>window.alert('Error in the query');</script>";
        }
    }
    ?>
</body>
</html>
