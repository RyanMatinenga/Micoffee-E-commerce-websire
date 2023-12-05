<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Register Admin</title>
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
   <link rel="stylesheet" href="css/style.css">

   <style>
        .popup {
            display: none;
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background-color: #ffffff;
            padding: 20px;
            border: 1px solid #ccc;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            text-align: center;
            font-size: 1.8rem;
        }

        .overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
        }
    </style>
</head>
<body>

<div class="form-container">
   <form action="" method="post">
        <div class="logo-container">
        <img src="images\logo.png" alt="Your Logo">
    </div>
      <h3>Register Admin</h3>
      <input type="text" name="name" placeholder="Enter your name" required class="box">
      <input type="email" name="email" placeholder="Enter your email" required class="box">
      <input type="password" name="admin_code" placeholder="Admin Code" required class="box">
      <input type="submit" name="submit" value="Register Admin" class="btn">
   </form>
</div>

<div class="overlay"></div>
<div class="popup">
    <span id="popup-message"></span>
    <button onclick="hidePopup()">Close</button>
</div>

<script>
    function showPopup(message) {
        document.getElementById('popup-message').textContent = message;
        document.querySelector('.popup').style.display = 'block';
        document.querySelector('.overlay').style.display = 'block';
    }

    function hidePopup() {
        document.querySelector('.popup').style.display = 'none';
        document.querySelector('.overlay').style.display = 'none';
    }
</script>

<?php
include 'config.php';
require_once 'email.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $adminCode = mysqli_real_escape_string($conn, $_POST['admin_code']);

    $correctAdminCode = 'Admin2001';
    $allowedUserType = 'admin';

    if ($adminCode !== $correctAdminCode) {
        echo '<script>showPopup("Invalid admin code. Registration not allowed.");</script>';
    } else {
        $generatedPassword = generatePassword();
        $hashedPassword = hashPassword($generatedPassword);
        $user_type = 'admin';

        $checkUserQuery = "SELECT * FROM `users` WHERE `email` = '$email' AND `user_type` = '$allowedUserType'";
        $checkUserResult = mysqli_query($conn, $checkUserQuery);

        if (mysqli_num_rows($checkUserResult) > 0) {
            echo '<script>showPopup("User with the same email and user_type ' . $allowedUserType . ' already exists. Registration not allowed.");</script>';
        } else {
            $insertUserQuery = "INSERT INTO `users` (name, email, password, user_type) VALUES ('$name', '$email', '$hashedPassword', '$user_type')";

            if (mysqli_query($conn, $insertUserQuery)) {
                echo '<script>showPopup("Your Password Is: ' . $generatedPassword . '");</script>';
            } else {
                echo '<script>showPopup("Error: ' . mysqli_error($conn) . '");</script>';
            }
        }
    }
}

function generatePassword($length = 12) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ!@#$%^&*()-_+=<>?';
    $password = '';
    $charLength = strlen($characters) - 1;
    for ($i = 0; $i < $length; $i++) {
        $password .= $characters[mt_rand(0, $charLength)];
    }
    return $password;
}

function hashPassword($password) {
    return password_hash($password, PASSWORD_DEFAULT);
}
?>
</body>
</html>
