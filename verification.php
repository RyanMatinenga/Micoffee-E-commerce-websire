<?php
session_start();
include 'config.php';
require_once 'email.php';
require 'vendor/autoload.php';

// Set OTP expiry time to 5 minutes from now
$otpExpiryTime = time() + (5 * 60); // 5 minutes in seconds

// Store the OTP expiry time in the session variable
$_SESSION['otp_expiry_time'] = $otpExpiryTime;

if(isset($_POST['email']) && isset($_POST['otp'])){
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $otp = mysqli_real_escape_string($conn, $_POST['otp']);

    // Check if the email and OTP combination exists in the email_verification table
    $checkOtpQuery = "SELECT * FROM `email_verification` WHERE email='$email' AND otp='$otp'";
    $result = mysqli_query($conn, $checkOtpQuery);

    if(mysqli_num_rows($result) > 0){
        // Get user data from email_verification table
        $userData = mysqli_fetch_assoc($result);

        // Store user data in session variables
        $_SESSION['name'] = $userData['name'];
        $_SESSION['email'] = $userData['email'];
        $_SESSION['password'] = $userData['password'];
        $_SESSION['user_type'] = $userData['user_type'];

        // Insert the verified user into the users table
        $insertUserQuery = "INSERT INTO `users` (name, email, password, user_type) VALUES ('{$_SESSION['name']}', '{$_SESSION['email']}', '{$_SESSION['password']}', '{$_SESSION['user_type']}')";
        if(mysqli_query($conn, $insertUserQuery)){
            // User inserted successfully, you can now send a welcome email
            $emailM = new \SendGrid\Mail\Mail();
            $emailM = new \SendGrid\Mail\Mail();
            $emailM->setFrom("micoffeemail@micoffe.store", "MiCoffee");
            $emailM->setSubject("Welcome to MiCoffee");
            $emailM->addTo($_SESSION['email'], $_SESSION['name']);
            $emailM->setTemplateId("d-30c45eb9df994a9583a5a5d56dbec039"); // Replace with your template ID
            $emailM->addDynamicTemplateData("name", $_SESSION['name']);

            $sendgrid = new \SendGrid(SENDGRID_API_KEY);
            try {
                $response = $sendgrid->send($emailM);
            } catch (Exception $e) {
                echo 'Caught exception: '. $e->getMessage() ."\n";
            }

            // Set success message
            $_SESSION['success_message'] = "You have been successfully registered! Redirecting to login page...";

            // Redirect to login page after 5 seconds
            header('Refresh: 0.5; URL=welcome.php?verified=true');
            exit();
        } else {
            // Set error message for user insertion failure
            $_SESSION['error_message'] = "Error: " . mysqli_error($conn);
            header('location: verification.php'); // Redirect back to verification page
            exit();
        }
    } else {
        // Set error message for invalid OTP or email
        $_SESSION['error_message'] = "Invalid OTP or Email.";
        header('location: verification.php'); // Redirect back to verification page
        exit();
    }
} else {
    // Handle the case when email and OTP are not set
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv of="X-UA-Compatible" content="IE-edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Email Verification</title>

   <!-- Font Awesome CDN link -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

   <!-- Custom CSS file link -->
   <link rel="stylesheet" href="css/style.css">

   <style>
       .error-message {
           background-color: #ff6666;
           color: white;
           padding: 10px;
           text-align: center;
           border-radius: 5px;
           margin-top: 10px;
       }

       .success-message {
           background-color: #4a8522;
           color: white;
           padding: 10px;
           text-align: center;
           border-radius: 5px;
           margin-top: 10px;
       }
   </style>
</head>
<body>
   <div class="form-container">
      <form action="verification.php" method="post">
         <h3>Email Verification</h3>
         <?php
            // Display error message if set in session
            if(isset($_SESSION['error_message'])) {
                echo '<p class="error-message">' . $_SESSION['error_message'] . '</p>';
                unset($_SESSION['error_message']); // Clear the session variable
            }

            // Display success message if set in session
            if(isset($_SESSION['success_message'])) {
                echo '<div class="success-message">' . $_SESSION['success_message'] . '</div>';
                unset($_SESSION['success_message']); // Clear the session variable
            }
         ?>
         <input type="email" name="email" placeholder="Enter your email" required class="box">
         <input type="text" name="otp" placeholder="Enter the OTP" required class="box">
         <input type="submit" name="submit" value="Verify Email" class="btn">

         <!-- Timer to display remaining time -->
         <div id="timer" style="color: white; font-size: 1.8rem;"></div>
      </form>
   </div>

   <!-- JavaScript code to update the timer -->
   <script>
    // Get the OTP expiry time from the server-side session variable
    var otpExpiryTime = <?php echo $_SESSION['otp_expiry_time']; ?>;

    // Function to update the timer every second
    function updateTimer() {
        var currentTime = Math.floor(Date.now() / 1000); // Current time in seconds
        var remainingTime = otpExpiryTime - currentTime; // Remaining time in seconds

        // Calculate minutes and seconds
        var minutes = Math.floor(remainingTime / 60);
        var seconds = remainingTime % 60;

        // Display the timer in the "timer" div
        document.getElementById('timer').innerHTML = 'Time left before OTP expires: ' + minutes + 'm ' + seconds + 's';

        // Check if the OTP has expired
        if (remainingTime <= 0) {
            // Redirect to a page indicating that the OTP has expired
            window.location.href = 'register.php';
        }
    }

    // Call the updateTimer function every second
    setInterval(updateTimer, 1000);
    </script>
</body>
</html>
