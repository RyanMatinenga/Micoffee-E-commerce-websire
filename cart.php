<?php

include 'config.php';

session_start();

$user_id = $_SESSION['user_id'];

if(!isset($user_id)){
   header('location:login.php');
}

if(isset($_POST['update_cart'])){
   $cart_id = $_POST['cart_id'];
   $cart_quantity = $_POST['cart_quantity'];
   mysqli_query($conn, "UPDATE `cart` SET quantity = '$cart_quantity' WHERE id = '$cart_id'") or die('query failed');
   $message[] = 'Cart quantity updated!';
}

if(isset($_GET['delete'])){
   $delete_id = $_GET['delete'];
   mysqli_query($conn, "DELETE FROM `cart` WHERE id = '$delete_id'") or die('query failed');
   header('location:cart.php');
}

if(isset($_GET['delete_all'])){
   mysqli_query($conn, "DELETE FROM `cart` WHERE user_id = '$user_id'") or die('query failed');
   header('location:cart.php');
}



// Generate the random code
$random_number = mt_rand(10000, 99999);
$random_letters = chr(mt_rand(65, 90)) . chr(mt_rand(65, 90)) . chr(mt_rand(65, 90));
$random_code = $random_number . $random_letters;


function generateItemName($cartItems) {
   $itemNames = array();

   foreach ($cartItems as $cartItem) {
       $itemNames[] = $cartItem['name'];
   }

   return implode(', ', $itemNames);
}


// Store it in a session variable
$_SESSION['random_code'] = $random_code;

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>cart</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
   
       <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">


   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">
   
   
      <style type="text/css">
.cart-total {
    border: 1px solid #ddd;
    padding: 20px;
    width: 300px;
    text-align: center;
    border-radius: 8px;
    background-color: #f9f9f9;
    margin: 20px auto;
}

.cart-total p {
    font-size: 18px;
    margin-bottom: 15px;
}

.cart-total button {
    display: block;
    width: 100%;
    padding: 10px;
    margin-bottom: 10px;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    font-size: 16px;
    color: #fff;
}

.cart-total button.btn {
    background-color: #4A8522;
}

.cart-total button.alt {
    background-color: #aaa;
}

.cart-total button.btn:hover,
.cart-total button.alt:hover {
    opacity: 0.8;
}

   </style>

</head>
<body>

  
<?php include 'header.php'; ?>

<div class="heading">
   <h3>shopping cart</h3>
   <p> <a href="home.php">home</a> / cart </p>
</div>



   <h1 class="title">products added</h1>



<div class="products">
  <div class="box-container">
      <?php
         $grand_total = 0;
         $select_cart = mysqli_query($conn, "SELECT * FROM `cart` WHERE user_id = '$user_id'") or die('query failed');
         if(mysqli_num_rows($select_cart) > 0){
            while($fetch_cart = mysqli_fetch_assoc($select_cart)){   
      ?>
    <div class="product-item">

      <a href="cart.php?delete=<?php echo $fetch_cart['id']; ?>" class="fas fa-times" onclick="return confirm('delete this from cart?');"></a>
      <img class="image" src="uploaded_img/<?php echo $fetch_cart['image']; ?>" alt="">
      <div class="details">


        <div class="name"><?php echo $fetch_cart['name']; ?></div>
        <div class="price">R<?php echo $fetch_cart['price']; ?></div>

        <div class="description"><?php echo $fetch_cart['Description']; ?></div>

        <form action="" method="post">
            <input type="hidden" name="cart_id" value="<?php echo $fetch_cart['id']; ?>">
         <input type="number" min="1" name="cart_quantity" value="<?php echo $fetch_cart['quantity']; ?>">
          <input type="hidden" name="product_name" value="<?php echo $fetch_cart['name']; ?>">
          <input type="hidden" name="product_price" value="<?php echo $fetch_cart['price']; ?>">


          <input type="hidden" name="product_image" value="<?php echo $fetch_cart['image']; ?>">
            <input type="submit" name="update_cart" value="update" class="option-btn">

        </form>
         <div class="sub-total"> sub total : <span>R<?php echo $sub_total = ($fetch_cart['quantity'] * $fetch_cart['price']); ?></span> </div>
      </div>
    </div>
      <?php
      $grand_total += $sub_total;
         }
      }else{
         echo '<p class="empty">your cart is empty</p>';
      }
      ?>
  </div>
</div>


 <form action="" method="post" class="box">
           
         <input type="text" name="entered_promo_code" placeholder="Enter Promo Code">
         <input type="submit" value="Apply Promo" name="apply_promo" class="btn">
      </form>

      <?php
     if (isset($_POST['apply_promo'])) {
    $entered_promo_code = mysqli_real_escape_string($conn, $_POST['entered_promo_code']);
    $select_promo_query = "SELECT discount_percentage FROM `promo_codes` WHERE code = '$entered_promo_code'";
    $result = mysqli_query($conn, $select_promo_query);

    if ($row = mysqli_fetch_assoc($result)) {
        $discount_percentage = $row['discount_percentage'];

        // Check if the total is greater than or equal to 130
        if ($grand_total >= 130) {
            $discounted_total = $grand_total * (1 - ($discount_percentage / 100));
            $message[] = "Promo code applied successfully! You received a {$discount_percentage}% discount.";
        } else {
            $message[] = "Promo code is not applicable. Total must be greater than or equal to 130.";
        }
    } else {
        $message[] = 'Invalid promo code!';
    }
}
      ?>

       
      


     <form action="https://sandbox.payfast.co.za/eng/process" method="post">
    <input type="hidden" name="merchant_id" value="10000100">
    <input type="hidden" name="merchant_key" value="46f0cd694581a">
    <input type="hidden" name="return_url" value="http://localhost:3000/notify.php">
    <input type="hidden" name="cancel_url" value="https://www.example.com/cancel">
    <input type="hidden" name="notify_url" value="http://localhost:3000/home.php">
    
    <!-- Check if discount is applied and grand total is greater than 130 -->
    <?php if (isset($discounted_total) && $grand_total >= 130) : ?>
        <input type="hidden" name="amount" value="<?php echo $discounted_total; ?>">
        <p>Total: Discounted Total: R<?php echo number_format($discounted_total, 2); ?></p>
    <?php else : ?>
        <input type="hidden" name="amount" value="<?php echo $grand_total; ?>">
        <p>Total: R<?php echo number_format($grand_total, 2); ?></p>
    <?php endif; ?>

    <input type="hidden" name="item_name" value="<?php echo $random_code; ?>">
    <input type="hidden" name="custom_str1" value="<?php echo $ITN_Payload['m_payment_id']; ?>">
    <input type="hidden" name="custom_str2" value="<?php echo $ITN_Payload['pf_payment_id']; ?>">
    <input type="hidden" name="custom_str3" value="<?php echo $ITN_Payload['payment_status']; ?>">
    <input type="hidden" name="custom_str4" value="<?php echo $ITN_Payload['item_name']; ?>">
    <input type="hidden" name="custom_str5" value="<?php echo $ITN_Payload['item_description']; ?>">

    <div class="cart-total">
        <p>Total : <span>R<?php echo number_format($grand_total, 2); ?></span></p>
        <?php if (isset($discounted_total)) : ?>
            <p>Discounted Total: R<?php echo number_format($discounted_total, 2); ?></p>
        <?php endif; ?>

        <div style="margin-top: 2rem; text-align:center;">
            <a href="cart.php?delete_all" class="delete-btn <?php echo ($grand_total > 1) ? '' : 'disabled'; ?>"
                onclick="return confirm('delete all from cart?');">delete all</a>
            <input type="submit" value="Proceed to payment">
        </div>
    </div>
</form>
</div>






<?php

// Get the ITN payload data from the POST request
$ITN_Payload = $_POST;

// Verify the authenticity of the ITN payload
// ...

// Check if the custom_str3 key exists
if (array_key_exists('custom_str3', $ITN_Payload)) {
  // The custom_str3 key exists
  $payment_status = $ITN_Payload['custom_str3'];
}

// Check if the payment was successful
if ($payment_status === 'PAID') {
 
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

    $order_query = mysqli_query($conn, "SELECT * FROM `orders` WHERE total_products = '$total_products' AND total_price = '$cart_total'") or die('query failed');

    if ($cart_total == 0) {
        $message[] = 'your cart is empty';
    } else {
        if (mysqli_num_rows($order_query) > 0) {
            $message[] = 'order already placed!';
        } else {
            mysqli_query($conn, "INSERT INTO `orders`(user_id, name, total_products, total_price, placed_on) VALUES('$user_id', '$random_code ','$total_products', '$cart_total', '$placed_on')") or die('query failed');
            $message[] = 'order placed successfully!';
            mysqli_query($conn, "DELETE FROM `cart` WHERE user_id = '$user_id'") or die('query failed');
        }
    };
} else {
  // The payment was not successful
}

?>

















<?php include 'footer.php'; ?>

<!-- custom js file link  -->
<script src="js/script.js"></script>

</body>
</html>










<?php include 'footer.php'; ?>

<!-- custom js file link  -->
<script src="js/script.js"></script>

</body>
</html>