<?php

include 'config.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if(!isset($admin_id)){
   header('location:login.php');
};

if(isset($_POST['add_product'])){

   $name = mysqli_real_escape_string($conn, $_POST['name']);
   $price = $_POST['price'];
   $image = $_FILES['image']['name'];
   $image_size = $_FILES['image']['size'];
   $image_tmp_name = $_FILES['image']['tmp_name'];
   $image_folder = 'uploaded_img/'.$image;

   $product_type = $_POST['product_type'];

     $description = $_POST['description'];



   $select_product_name = mysqli_query($conn, "SELECT name FROM `products` WHERE name = '$name'") or die('query failed');

   if(mysqli_num_rows($select_product_name) > 0){
      $message[] = 'product name already added';
   }else{
      $add_product_query = mysqli_query($conn, "INSERT INTO `products`(name, price, image,product_type,Description) VALUES('$name', '$price', '$image',  '$product_type','$description')") or die('query failed');

      if($add_product_query){
         if($image_size > 2000000){
            $message[] = 'image size is too large';
         }else{
            move_uploaded_file($image_tmp_name, $image_folder);
            $message[] = 'product added successfully!';
         }
      }else{
         $message[] = 'product could not be added!';
      }
   }
}

if(isset($_GET['delete'])){
   $delete_id = $_GET['delete'];
   $delete_image_query = mysqli_query($conn, "SELECT image FROM `products` WHERE id = '$delete_id'") or die('query failed');
   $fetch_delete_image = mysqli_fetch_assoc($delete_image_query);
   unlink('uploaded_img/'.$fetch_delete_image['image']);
   mysqli_query($conn, "DELETE FROM `products` WHERE id = '$delete_id'") or die('query failed');
   header('location:admin_products.php');
}

if(isset($_POST['update_product'])){

   $update_p_id = $_POST['update_p_id'];
   $update_name = $_POST['update_name'];
   $update_price = $_POST['update_price'];

   $update_product_type = $_POST['update_product_type'];
   $update_description= $_POST['update_description'];


   mysqli_query($conn, "UPDATE `products` SET name = '$update_name', price = '$update_price', Description='$update_description', product_type='$update_product_type'  WHERE id = '$update_p_id'") or die('query failed');

   $update_image = $_FILES['update_image']['name'];
   $update_image_tmp_name = $_FILES['update_image']['tmp_name'];
   $update_image_size = $_FILES['update_image']['size'];
   $update_folder = 'uploaded_img/'.$update_image;
   $update_old_image = $_POST['update_old_image'];



   if(!empty($update_image)){
      if($update_image_size > 2000000){
         $message[] = 'image file size is too large';
      }else{
         mysqli_query($conn, "UPDATE `products` SET image = '$update_image' WHERE id = '$update_p_id'") or die('query failed');
         move_uploaded_file($update_image_tmp_name, $update_folder);
         unlink('uploaded_img/'.$update_old_image);
      }
   }

   header('location:admin_products.php');

}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>products</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

   <!-- custom admin css file link  -->
   <link rel="stylesheet" href="css/admin_style.css">
   
   
   
<style>

   /* Style the placeholders for text and number input fields */
input[type="text"]::placeholder,
input[type="number"]::placeholder {
    color: white; /* Change the color of the placeholder text */
    font-style: italic; /* Make the placeholder text italic */
    font-size: 2rem;
}

/* Style the placeholders for the file input field */
input[type="file"]::placeholder {
    color: white; /* Change the color of the placeholder text */
    font-style: italic; /* Make the placeholder text italic */
    font-size: 2rem;
}

/* Adjust the font-size of placeholders if needed */
input::placeholder {
    font-size: 2rem; /* Customize the font size */
    color: white; /* Change the color of the placeholder text */
    font-style: italic;
}

select[name="update_product_type"] {
    color: white;
}


</style>

</head>
<body>
   
<?php include 'admin_header.php'; ?>

<!-- product CRUD section starts  -->


<section class="add-products">

   <h1 class="title">shop products</h1>

   <form action="" method="post" enctype="multipart/form-data">
      <h3>add product</h3>
      <input type="text" name="name" class="box" placeholder="enter product name" required>
      <input type="number" min="0" name="price" class="box" placeholder="enter product price" required>

            <input type="text" min="0" name="description" class="box" placeholder="enter product description" required>
      <input type="file" name="image" accept="image/jpg, image/jpeg, image/png" class="box" required>



 <select name="product_type" class="box" required>
 <option value="" disabled selected>Select product type</option>
    <option value="Coffee">Coffee</option>
    <option value="Tea">Tea</option>
    <option value="Speciality Drinks">Speciality Drinks</option>
    <option value="Pastries/Snacks">Pastries/Snacks</option>
    <option value="Cold Drinks">Cold Drinks</option>
    <option value="Desserts">Desserts</option>
    <option value="Breakfast">Breakfast</option>
</select>
      <input type="submit" value="add product" name="add_product" class="btn">
   </form>

</section>


<div class="products">

   <div class="box-container">

      <?php
         $select_products = mysqli_query($conn, "SELECT * FROM `products`") or die('query failed');
         if(mysqli_num_rows($select_products) > 0){
            while($fetch_products = mysqli_fetch_assoc($select_products)){
      ?>
    <div class="product-item">
         <img class="image" src="uploaded_img/<?php echo $fetch_products['image']; ?>" alt="">
      <div class="details">

         <div class="name"><?php echo $fetch_products['name']; ?></div>
         <div class="price">R<?php echo $fetch_products['price']; ?></div>
          <div class="description"><?php echo $fetch_products['Description']; ?></div>
         <a href="admin_products.php?update=<?php echo $fetch_products['id']; ?>" class="option-btn">update</a>
         <a href="admin_products.php?delete=<?php echo $fetch_products['id']; ?>" class="delete-btn" onclick="return confirm('delete this product?');">delete</a>
      </div>
   </div>
      <?php
         }
      }else{
         echo '<p class="empty">no products added yet!</p>';
      }
      ?>
   </div>

</div>

<section class="edit-product-form">

   <?php
      if(isset($_GET['update'])){
         $update_id = $_GET['update'];
         $update_query = mysqli_query($conn, "SELECT * FROM `products` WHERE id = '$update_id'") or die('query failed');
         if(mysqli_num_rows($update_query) > 0){
            while($fetch_update = mysqli_fetch_assoc($update_query)){
   ?>
   <form action="" method="post" enctype="multipart/form-data">
      <input type="hidden" name="update_p_id" value="<?php echo $fetch_update['id']; ?>">
      <input type="hidden" name="update_old_image" value="<?php echo $fetch_update['image']; ?>">
      <img src="uploaded_img/<?php echo $fetch_update['image']; ?>" alt="">
      <input type="text" name="update_name" value="<?php echo $fetch_update['name']; ?>" class="box" required placeholder="enter product name">
      <input type="number" name="update_price" value="<?php echo $fetch_update['price']; ?>" min="0" class="box" required placeholder="enter product price">

      <input type="text" name="update_description" value="<?php echo $fetch_update['Description']; ?>" class="box" required placeholder="enter product description">

      <select name="update_product_type" class="box" required>
    <option value="Coffee">Coffee</option>
    <option value="Tea">Tea</option>
    <option value="Speciality Drinks">Speciality Drinks</option>
    <option value="Pastries/Snacks">Pastries/Snacks</option>
    <option value="Cold Drinks">Cold Drinks</option>
    <option value="Desserts">Desserts</option>
    <option value="Breakfast">Breakfast</option>
</select>

      <input type="file" class="box" name="update_image" accept="image/jpg, image/jpeg, image/png">
      <input type="submit" value="update" name="update_product" class="btn">
      <input type="reset" value="cancel" id="close-update" class="option-btn">
   </form>
   <?php
         }
      }
      }else{
         echo '<script>document.querySelector(".edit-product-form").style.display = "none";</script>';
      }
   ?>

</section>






<!-- custom admin js file link  -->
<script src="js/admin_script.js"></script>

</body>
</html>