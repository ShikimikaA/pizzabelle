<?php
include_once('dbconnect.php');
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];

    $query = "SELECT * FROM users WHERE email='$email'";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) == 0) {
        $insertQuery = "INSERT INTO users (email, password, firstname, lastname, role) 
                        VALUES ('$email', '$password', '$firstname', '$lastname', 'customer')";
        if (mysqli_query($conn, $insertQuery)) {
            $_SESSION['user_id'] = mysqli_insert_id($conn);
            header("Location: login.php");
            exit();
        } else {
            $errorMsg = "Error: " . $insertQuery . "<br>" . mysqli_error($conn);
        }
    } else {
        $errorMsg = "This email is already registered.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link rel="stylesheet" href="stylesregister.css">
</head>
<body>

<hr class="new2">
<div class="banner">
    <img src="cover1.jpg" alt="">
    
</div>


<img src="bgremovepizza1.png" alt="">
<hr class="new2">

<div class="pizzabottom">
    <img src="bgremovepizza2.png" alt="">
</div>

    <div class="registration-container">
        <div class="logoimg">
            <center><img src="logopizza1.png" alt="Logo"></center>
        </div>
        <form action="register.php" method="POST" class="registration-form">
            <input type="text" name="firstname" placeholder="First Name" required><br>
            <input type="text" name="lastname" placeholder="Last Name" required><br>
            <input type="email" name="email" placeholder="Email" required><br>
            <input type="password" name="password" placeholder="Password" required><br>
            <input type="submit" value="Sign Up">
        </form>
        <?php if (isset($errorMsg)) { echo "<p class='error-message'>$errorMsg</p>"; } ?>
        <center><p>Already a member? <a href="login.php">Log in here</a></p></center>
    </div>

    <hr class="new2">
</body>
</html>
