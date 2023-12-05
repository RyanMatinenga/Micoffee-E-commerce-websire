<?php

// include 'config.php';
 require_once 'email.php';

include 'config.php';
session_start();

if(isset($_POST['add_to_cart'])){
    // Check if the user is logged in
    if(!isset($_SESSION['user_id'])){
        // User is not logged in, redirect to the login page
        header('location: login.php');
        exit(); // Ensure script execution stops after the redirection
    }

    $user_id = $_SESSION['user_id'];
    $product_name = $_POST['product_name'];
    $product_price = $_POST['product_price'];
    $product_image = $_POST['product_image'];
    $product_quantity = $_POST['product_quantity'];

    $check_cart_numbers = mysqli_query($conn, "SELECT * FROM `cart` WHERE name = '$product_name' AND user_id = '$user_id'") or die('query failed1');

    if(mysqli_num_rows($check_cart_numbers) > 0){
        $message[] = 'Item is already added to cart!';
    } else {
      mysqli_query($conn, "INSERT INTO `cart`(user_id, name, price, quantity, image) VALUES('$user_id', '$product_name', '$product_price', '$product_quantity', '$product_image')") or die('query failed2');
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
   <title>home</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">

</head>
<body>
   
<?php include 'header.php'; ?>

<section class="home">

   <div class="content">
        <p>We’ve got your morning covered with</p>
     
      <h3>Coffee</h3>
      <p>It is best to start your day with a cup of coffee. Discover the best flavours coffee you will ever have. We provide the best for our customers.</p>
      <a href="shop.php" class="white-btn">Go to Menu</a>
   </div>

</section>

<section class="discover">

<div class="box-container">



<div class="box">
   <h3>Discover the best coffee</h3>
   <p>Micofffe is a coffee shop that provides you with quality coffee that helps boost your productivity and helps build your mood. Having a cup of coffee is good, but having a cup of real coffee is greater. There is no doubt that you will enjoy this coffee more than others you have ever tasted.</p>

   <div class="content">

   <a href="about.php" class="learn-btn" style="padding: 1rem 3rem;">Learn more</a>

  </div>
</div>

<div class="box">
<img src="images/cup.png" alt="" style="max-width: 45%; object-fit: cover;">

</div>







</div>

</section>

<section class="different">


       <div class="box-container1">
   <div class="box1">


      <h3>Enjoy a new blend of coffee style</h3>
      <p>Explore all flavours of coffee with us. There is always a new cup worth experiencing</p>

   </div>
   
   </section>
   
   <section class="products">

<div class="products">
  <div class="box-container">
    <?php  
      $select_products = mysqli_query($conn, "SELECT * FROM `products` LIMIT 6") or die('query failed');
      if(mysqli_num_rows($select_products) > 0){
        while($fetch_products = mysqli_fetch_assoc($select_products)){
    ?>
    <div class="product-item">
      <img class="image" src="uploaded_img/<?php echo $fetch_products['image']; ?>" alt="">
      <div class="details">
        <div class="name"><?php echo $fetch_products['name']; ?></div>
        <div class="price">R<?php echo $fetch_products['price']; ?></div>
          <div class="description"><?php echo $fetch_products['Description']; ?></div>
        <form action="" method="post">
          <input type="number" min="1" name="product_quantity" value="1" class="qty">
          <input type="hidden" name="product_name" value="<?php echo $fetch_products['name']; ?>">
          <input type="hidden" name="product_price" value="<?php echo $fetch_products['price']; ?>">
          <input type="hidden" name="product_image" value="<?php echo $fetch_products['image']; ?>">

          <input type="hidden" name="product_description" value="<?php echo $fetch_products['Description']; ?>">
          <input type="submit" value="Add to Cart" name="add_to_cart" class="btn">
        </form>
      </div>
    </div>

    <?php
      }
    } else {
      echo '<p class="empty">No products added yet!</p>';
    }
    ?>
  </div>
</div>

   <div class="load-more" style="margin-top: 2rem; text-align:center">
      <a href="shop.php" class="option-btn">load more</a>
   </div>





</section>


<section class="different">

       <div class="box-container1">
   <div class="box1">

      <h3>Why are we different</h3>
      <p>We don’t just make your coffee, we make your day!</p>
   </div>
</div>





      <div class="box-container">

      <div class="box">
         <img src="images/coffee-beans_1.png" alt="" style="max-width: 45%; object-fit: cover; justify-content: center;">
         <h3>Supreme Beans</h3>
         <P>Beans that provides great taste</P>
      </div>

      <div class="box">
         <img src="images/badge_1.png" alt="" style="max-width: 45%; object-fit: cover;">
        <h3>High Quality</h3>
         <P>We provide the highest quality</P>
      </div>

      <div class="box">

         <img src="images/coffe-cup_1.png" alt="" style="max-width: 45%; object-fit: cover;">
         <h3>Extraordinary</h3>
         
         <P>Coffee like you have never tasted</P>
      </div>

      <div class="box">
         <img src="images/best-price_1.png" alt="" style="max-width: 45%; object-fit: cover;">
         <h3>Affordable Price</h3>
         <P style="text-align: center;">Our Coffee prices are easy to afford</P>
      </div>

   </div>


</section>











<?php include 'footer.php'; ?>

<!-- custom js file link  -->
<script src="js/script.js"></script>

</body>
</html>