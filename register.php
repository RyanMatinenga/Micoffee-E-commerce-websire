<?php
session_start();
include 'config.php';
require_once 'email.php';
require 'vendor/autoload.php';

// Set your SendGrid API key here
$sendgridApiKey = SENDGRID_API_KEY; // Replace with your actual API key
$sendgridTemplateId = "d-f6fd50cf7c71443b81f78a45b944bfb4"; // Replace with your template ID

$message = array(); // Initialize the message array

if (isset($_POST['submit'])) {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    $cpassword = mysqli_real_escape_string($conn, $_POST['cpassword']);
    $user_type = 'user'; // Set user_type to 'user'

    // Check if the email already exists in the users table
    $checkEmailQuery = "SELECT * FROM `users` WHERE email = '$email'";
    $result = mysqli_query($conn, $checkEmailQuery);

    if (mysqli_num_rows($result) > 0) {
        $message[] = 'Email already registered! Please use a different email address.';
    } else {
        // Email Validation
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $message[] = 'Invalid email format!';
        }

        // Password Validation
        if (strlen($password) < 8 || !preg_match('/[A-Z]/', $password) || !preg_match('/\d/', $password)) {
            $message[] = 'Password should be at least 8 characters, contain at least one uppercase letter, and one number.';
        }

        // Check if the passwords match
        if ($password !== $cpassword) {
            $message[] = 'Confirm password not matched!';
        }

        if (empty($message)) {
            // Hash the password for security
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            // Generate OTP
            $otp = mt_rand(100000, 999999); // 6-digit OTP

            // Get current timestamp
            $otpTimestamp = time();

             // Insert OTP and timestamp into the email_verification table
               $insertOtpQuery = "INSERT INTO `email_verification` (name, password, email, otp, timestamp, user_type) VALUES ('$name', '$hashed_password', '$email', '$otp', '$otpTimestamp', '$user_type')";
                mysqli_query($conn, $insertOtpQuery);

            // Update OTP and timestamp into the email_verification table
            $updateOtpQuery = "UPDATE `email_verification` SET otp='$otp', timestamp='$otpTimestamp' WHERE email='$email'";
            mysqli_query($conn, $updateOtpQuery);


            // Create a SendGrid mail object and set the template ID
            $emailM = new SendGrid\Mail\Mail();
            $emailM->setFrom("micoffeemail@micoffe.store", "MiCoffee");
            $emailM->setTemplateId($sendgridTemplateId);
            $emailM->addTo($email, $name);

            // Replace the {{otp}} placeholder in the template with the actual OTP
            $emailM->addDynamicTemplateData("otp", $otp); // Here, "otp" is the name of the placeholder

            $sendgrid = new SendGrid($sendgridApiKey);

            try {
                $response = $sendgrid->send($emailM);
            } catch (Exception $e) {
                echo 'Caught exception: ' . $e->getMessage() . "\n";
            }

            // Redirect to verification page with email parameter
            header('location: verification.php?email=' . urlencode($email));
            exit();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Register</title>
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
   <link rel="stylesheet" href="css/style2023.css">
</head>
<body>
<div class="notification-container">
<?php
if(isset($message)){
   foreach($message as $msg){
      echo '
      <div class="message">
         <span>'.$msg.'</span>
         <i class="fas fa-times" onclick="this.parentElement.remove();"></i>
      </div>
      ';
   }
}
?>
</div>
<div class="form-container">
   <form action="" method="post">
   <div class="logo-container">
        <img src="images\logo.png" alt="Your Logo">
    </div>
      <h3>Register Now</h3>
       <input type="text" name="name" placeholder="Enter your name" required class="box">
      <input type="email" name="email" placeholder="Enter your email" required class="box">
      <input type="password" name="password" placeholder="Password (min. 8 characters, 1 uppercase, 1 number)" required class="box">
      <input type="password" name="cpassword" placeholder="Confirm your password" required class="box">
      <input type="submit" name="submit" value="Register Now" class="btn">
      <p>Already have an account? <a href="login.php">Login now</a></p>
   </form>
</div>
</body>
</html>
