<?php
use SendGrid\Mail\Mail;

include 'config.php';
require_once 'email.php';
require 'vendor/autoload.php';

session_start();

$user_id = $_SESSION['user_id'];
$random_code = $_SESSION['random_code'];

if (!isset($user_id)) {
    header('location: login.php');
}

if (isset($_POST['order_btn'])) {
    $name = mysqli_real_escape_string($conn, $_POST['name']); // Get the user's name from the form
    $placed_on = date('d-M-Y');

    $cart_total = 0;
    $cart_products = [];

    $cart_query = mysqli_query($conn, "SELECT * FROM `cart` WHERE user_id = '$user_id'") or die('query failed');
    if (mysqli_num_rows($cart_query) > 0) {
        while ($cart_item = mysqli_fetch_assoc($cart_query)) {
            $cart_products[] = $cart_item['name'] . ' (' . $cart_item['quantity'] . ') ';
            $sub_total = ($cart_item['price'] * $cart_item['quantity']);
            $cart_total += $sub_total;
        }
    }

    $total_products = implode(', ', $cart_products);

    $order_query = mysqli_query($conn, "SELECT * FROM `orders` WHERE name = '$total_products' AND total_price = '$cart_total'") or die('query failed');

    if ($cart_total == 0) {
        $message[] = 'your cart is empty';
    } else {
        if (mysqli_num_rows($order_query) > 0) {
            $message[] = 'order already placed!';
        } else {
            // Fetch user's email address from the database
            $user_query = mysqli_query($conn, "SELECT email FROM `users` WHERE id = '$user_id'") or die('query failed');
            $user_data = mysqli_fetch_assoc($user_query);
            $user_email = $user_data['email'];

            // Insert order into the database
            mysqli_query($conn, "INSERT INTO `orders`(user_id, name, total_products, total_price, placed_on, order_number) VALUES('$user_id', '$name', '$total_products', '$cart_total', '$placed_on', '$random_code')") or die('query failed');

            // Send email using SendGrid
            $email = new \SendGrid\Mail\Mail();
            $email->setFrom("micoffeemail@micoffe.store", "MiCoffee"); // Replace with sender's email and name
            $email->setTemplateId("d-b27a68d98be44d3f9d421d5d5b96345b"); // Replace with your receipt template ID
            $email->addTo($user_email, $name); // Use the fetched email address

            // Replace the placeholders in the template with the actual values
            $email->addDynamicTemplateData("placed_on", $placed_on);
            $email->addDynamicTemplateData("name", $name);
            $email->addDynamicTemplateData("user_email", $user_email);
            $email->addDynamicTemplateData("total_products", $total_products);
            $email->addDynamicTemplateData("cart_total", $cart_total);
            $email->addDynamicTemplateData("order_number", $random_code);

            $sendgrid = new \SendGrid(SENDGRID_API_KEY); // Replace SENDGRID_API_KEY with your actual SendGrid API key

            try {
                $response = $sendgrid->send($email);
            } catch (Exception $e) {
                echo 'Caught exception: '. $e->getMessage() . "\n";
            }

            $message[] = 'order placed successfully!';
            mysqli_query($conn, "DELETE FROM `cart` WHERE user_id = '$user_id'") or die('query failed');
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
   <title>Checkout</title>

   <!-- Font Awesome CDN link -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

   <!-- Custom CSS file link -->
   <link rel="stylesheet" href="css/style.css">
</head>
<body>
   
<?php include 'header.php'; ?>

<div class="heading">
   <h3>Checkout</h3>
   <p> <a href="home.php">Home</a> / Checkout </p>
</div>

<section class="display-order">
   <?php  
      $grand_total = 0;
      $select_cart = mysqli_query($conn, "SELECT * FROM `cart` WHERE user_id = '$user_id'") or die('query failed');
      if(mysqli_num_rows($select_cart) > 0){
         while($fetch_cart = mysqli_fetch_assoc($select_cart)){
            $total_price = ($fetch_cart['price'] * $fetch_cart['quantity']);
            $grand_total += $total_price;
   ?>
   <p> <?php echo $fetch_cart['name']; ?> <span>(<?php echo 'R'.$fetch_cart['price'].''.' x '. $fetch_cart['quantity']; ?>)</span> </p>
   <?php
      }
   }else{
      echo '<p class="empty">Your cart is empty</p>';
   }
   ?>
   <div class="grand-total">Grand Total: <span>R<?php echo $grand_total; ?></span></div>
</section>

<section class="checkout">
   <form action="" method="post">
      <h3>Order Successfully - Please Get Receipt</h3>
      <div class="flex">
      <input type="submit" value="Get Receipt" class="btn" name="order_btn" style="background-color: #4A8522; color: black; width: 138.21px; height: 49.18px; top: 637px; left: 626.61px; border-radius: 24px;">
      </div>
   </form>
</section>

<?php include 'footer.php'; ?>

<!-- Custom JS file link -->
<script src="js/script.js"></script>

</body>
</html>
