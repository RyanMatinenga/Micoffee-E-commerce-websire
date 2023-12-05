<?php

include 'config.php';

session_start();

$user_id = $_SESSION['user_id'];



if(isset($_POST['add_to_cart'])){

   if(!isset($user_id)){
   header('location:login.php');
}

   $product_name = $_POST['product_name'];
   $product_price = $_POST['product_price'];
   $product_image = $_POST['product_image'];
   $product_quantity = $_POST['product_quantity'];
   $product_description = $_POST['product_description'];

   $check_cart_numbers = mysqli_query($conn, "SELECT * FROM `cart` WHERE name = '$product_name' AND user_id = '$user_id'") or die('query failed');

   if(mysqli_num_rows($check_cart_numbers) > 0){
      $message[] = 'already added to cart!';
   }else{
      mysqli_query($conn, "INSERT INTO `cart`(user_id, name, price, quantity, image, Description ) VALUES('$user_id', '$product_name', '$product_price', '$product_quantity', '$product_image','$product_description')") or die('query failed');
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
   <link rel="stylesheet" href="css/style.css">

</head>
<body>
   
<?php include 'header.php'; ?>

<div class="heading">
   <h3>menu</h3>
   <p> <a href="home.php">home</a> / shop </p>
</div>

<section class="product">

         <div class="box-container1">
   <div class="box1">

      <h3>Why are we different?</h3>
      <p> We don’t just make your coffee, we make your day!</p>
     <h3>Tea</h3>

   </div>
</div>


 



<div class="products">
  <div class="box-container">

      <?php  

 
         $select_products = mysqli_query($conn, "SELECT * FROM `products` WHERE product_type = 'Tea'") or die('query failed');
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

</section>


<section class="product">

         <div class="box-container1">
   <div class="box1">

      <h3>Coffee</h3>


   </div>
</div>




<div class="products">
  <div class="box-container">

      <?php  

 
         $select_products = mysqli_query($conn, "SELECT * FROM `products` WHERE product_type = 'Coffee'") or die('query failed');
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

</section>



<section class="product">

         <div class="box-container1">
   <div class="box1">

      <h3>Specialty Drinks</h3>


   </div>
</div>




<div class="products">
  <div class="box-container">

      <?php  

 
         $select_products = mysqli_query($conn, "SELECT * FROM `products` WHERE product_type = 'Specialty Drinks'") or die('query failed');
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

</section>


<section class="product">

         <div class="box-container1">
   <div class="box1">

      <h3>Pastries/Snacks</h3>


   </div>
</div>




<div class="products">
  <div class="box-container">

      <?php  

 
         $select_products = mysqli_query($conn, "SELECT * FROM `products` WHERE product_type = 'Pastries/Snacks'") or die('query failed');
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

</section>


<section class="product">

         <div class="box-container1">
   <div class="box1">

      <h3>Cold Drinks</h3>


   </div>
</div>




<div class="products">
  <div class="box-container">

      <?php  

 
         $select_products = mysqli_query($conn, "SELECT * FROM `products` WHERE product_type = 'Cold Drinks'") or die('query failed');
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

</section>



<section class="product">

         <div class="box-container1">
   <div class="box1">

      <h3>Desserts</h3>


   </div>
</div>




<div class="products">
  <div class="box-container">

      <?php  

 
         $select_products = mysqli_query($conn, "SELECT * FROM `products` WHERE product_type = 'Desserts'") or die('query failed');
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

</section>



<section class="product">

         <div class="box-container1">
   <div class="box1">

      <h3>Breakfast</h3>


   </div>
</div>




<div class="products">
  <div class="box-container">

      <?php  

 
         $select_products = mysqli_query($conn, "SELECT * FROM `products` WHERE product_type = 'Breakfast'") or die('query failed');
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

</section>









<?php include 'footer.php'; ?>

<!-- custom js file link  -->
<script src="js/script.js"></script>

</body>
</html>