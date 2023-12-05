<?php


include 'config.php';

session_start();


$user_id = $_SESSION['user_id'];

if(!isset($user_id)){
   header('location:login.php');
}
//  this section is meant to ensure that the return page can only be accessed by creating some form of boolean  that turs return into 1 and only when it's one are allowed to access the return page and it is only turned to 1 when you click the pay now button but if you open a page it is intialized


?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>checkout</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">

</head>
<body>
   
<?php include 'header.php'; ?>

<div class="heading">
   <h3>checkout</h3>
   <p> <a href="home.php">home</a> / checkout </p>
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
   <p> <?php echo $fetch_cart['name']; ?> <span>(<?php echo 'R'.$fetch_cart['price'].'/-'.' x '. $fetch_cart['quantity']; ?>)</span> </p>
   <?php
      }
   }else{
      echo '<p class="empty">your cart is empty</p>';
   }
   ?>
   <div class="grand-total"> grand total : <span>R<?php echo $grand_total; ?>/-</span> </div>
   
   
      <form action="https://www.payfast.co.za/eng/process" method="post">
<input type="hidden" name="merchant_id" value="23102524">
<input type="hidden" name="merchant_key" value="lm9zqzkyz5z3c">
<input type="hidden" name="return_url" value="https://www.example.com/success">
<input type="hidden" name="cancel_url" value="https://www.example.com/cancel">
<input type="hidden" name="notify_url" value="https://www.example.com/notify">

<input type="hidden" name="amount" value="<?php echo $grand_total; ?>">
   <input type="hidden" name="item_name" value="<?php echo $random_code; ?>">
   <input type="submit">
</form> 


</section>








<?php include 'footer.php'; ?>
<!-- custom js file link  -->
<script src="js/script.js"></script>

</body>
</html>