<?php

include 'config.php';

session_start();

$user_id = $_SESSION['user_id'];

if(!isset($user_id)){
   header('location:login.php');
}


if(isset($_POST['add_to_cart'])){

   $product_name = $_POST['product_name'];
   $product_price = $_POST['product_price'];
   $product_image = $_POST['product_image'];
   $product_quantity = $_POST['product_quantity'];

   $check_cart_numbers = mysqli_query($conn, "SELECT * FROM `cart` WHERE name = '$product_name' AND user_id = '$user_id'") or die('query failed');

   if(mysqli_num_rows($check_cart_numbers) > 0){
      $message[] = 'already added to cart!';
   }else{
      mysqli_query($conn, "INSERT INTO `cart`(user_id, name, price, quantity, image) VALUES('$user_id', '$product_name', '$product_price', '$product_quantity', '$product_image')") or die('query failed');
      $message[] = 'product added to cart!';
   }

}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>shop</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style2023.css">
  <style>
      .promo-code-box {
         background-color: #f2f2f2; /* Background color for the promo code boxes */
         border: 1px solid #ccc; /* Border for the boxes */
         padding: 10px; /* Padding inside the boxes */
         margin-bottom: 10px; /* Spacing between boxes */
         font-weight: bold; /* Make the text bold */
      }
   </style>
</head>
<body>
   
<?php include 'header.php'; ?>

<div class="heading">
   <h3>Promotions</h3>
   <p> <a href="home.php">home</a> / Promotions </p>
</div>

<section class="products">


         <div class="box-container1">
   <div class="box1">

      <h3>Online Discounts  </h3>
      <p>Find yourself products and combos that are on promotion and get them a really low prices</p>
<h2>NOTE PROMO CODES ARE ONLY VALID WHEN YOU SPEND MORE THAN R130 ONLINE</h2>
   </div>
</div>


   <div class="box-container">
   <?php
    $select_promo_codes = mysqli_query($conn, "SELECT * FROM `promo_codes`") or die('query failed');
    if (mysqli_num_rows($select_promo_codes) > 0) {
        while ($fetch_promo_code = mysqli_fetch_assoc($select_promo_codes)) {
            
            //   <div class="box1">

    //   <h3>Promotions Page </h3>
    //   <p>Find yourself products and combos that are on promotion and get them a really low prices</p>
      
    //   </div>

//   </div>
            echo '<div class="promo-code-box">';
            echo 'Promo Code: ' . $fetch_promo_code['code'];
            echo  '    
                Discount: ' . $fetch_promo_code['discount_percentage'] . '%';
            echo '</div>';
        }
    } else {
        echo '<p class="empty">No promo codes available yet!</p>';
    }
    ?>
    

   </div>

</section>


<section class="products">


         <div class="box-container1">
   <div class="box1">

      <h3>Micoffe store Promotions  </h3>
    <h2>NOTE THE BELOW PROMOTIONS ARE ONLY AVAILABLE AT THE MICOFFEE SHOP ONLY </h2>

   </div>
</div>


   <div class="box-container">

    
      <?php  
         $select_products = mysqli_query($conn, "SELECT * FROM `promotions`") or die('query failed');
         if(mysqli_num_rows($select_products) > 0){
            while($fetch_products = mysqli_fetch_assoc($select_products)){
      ?>
     <form action="" method="post" class="box">
      <img class="image" src="uploaded_img/<?php echo $fetch_products['image']; ?>" alt="">
      <div class="name"><?php echo $fetch_products['name']; ?></div>
      <!-- <div class="price"> $<?php echo $fetch_products['price']; ?>/-</div> -->
      <!-- <input type="number" min="1" name="product_quantity" value="1" class="qty"> -->
      <input type="hidden" name="product_name" value="<?php echo $fetch_products['name']; ?>">
      <!-- <input type="hidden" name="product_price" value="<?php echo $fetch_products['price']; ?>"> -->
      <input type="hidden" name="product_image" value="<?php echo $fetch_products['image']; ?>">
      <!-- <input type="submit" value="add to cart" name="add_to_cart" class="btn"> -->
     </form>
      <?php
         }
      }else{
         echo '<p class="empty">no products added yet!</p>';
      }
      ?>
   </div>

</section>








<?php include 'footer.php'; ?>

<!-- custom js file link  -->
<script src="js/script.js"></script>

</body>
</html>