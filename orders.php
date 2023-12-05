<?php

include 'config.php';

session_start();

// $user_id = $_SESSION['user_id'];

// if(!isset($user_id)){
//    header('location:login.php');
// }
$user_id = null; // Initialize $user_id as null

if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id']; // Set $user_id if the user is logged in
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>orders</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">

      <style>
table {
  background-color: var(--color-white);
  width: 100%;
  padding: var(--card-padding);
  text-align: center;
  box-shadow: var(--box-shadow);
  border-radius: var(--card-border-radius);
  transition: all 0.3s ease;
}

table:hover {
  box-shadow: none;
}

table tbody td {
  height: 2.8rem;
  border-bottom: 1px solid var(--color-light);
  color: var(--color-dark-variant);
}

table tbody tr:last-child td {
  border: none;
}




  </style>


</head>
<body>

   <?php include 'header.php'; ?>
   
<section class="placed-orders">

<h1 class="title" style="color: green">Order history </h1>


<div class="box-container">

<table>
  <thead>
    <tr>
      <th>Placed On</th>
      <th>Name</th>
      
       <th>Order Number</th>
      <th>Email</th>
      <th>Total Products</th>
      <th>Total Price</th>
      <th>Payment Status</th>
    </tr>
  </thead>
  <tbody>
    <?php
$order_query = mysqli_query($conn, "SELECT * FROM `orders` WHERE user_id = '$user_id' ORDER BY id DESC") or die('query failed');


      if (mysqli_num_rows($order_query) > 0) {
        while ($fetch_orders = mysqli_fetch_assoc($order_query)) {

          $user_query = mysqli_query($conn, "SELECT * FROM `users` WHERE id = '$user_id'") or die('query failed');
          if (mysqli_num_rows($user_query) > 0) {
            while ($fetch_users = mysqli_fetch_assoc($user_query)) {
    ?>
    <tr>
      <td><?= $fetch_orders['placed_on']; ?></td>
      <td><?= $fetch_users['name']; ?></td>
      
       <td><?= $fetch_orders['Order_Number']; ?></td>
      <td><?= $fetch_users['email']; ?></td>
      <td><?= $fetch_orders['total_products']; ?></td>
      <td>R<?= $fetch_orders['total_price']; ?></td>
      <td><span style="color:<?php if ($fetch_orders['payment_status'] == 'pending') {
          echo 'red';
        } else {
          echo 'green';
        } ?>;"><?php echo $fetch_orders['payment_status']; ?></span></td>
    </tr>
    <?php
            }
          } else {
            echo '<tr><td colspan="6">no orders placed yet!</td></tr>';
          }
        }
      } else {
        echo '<tr><td colspan="6">no orders placed yet!</td></tr>';
      }
    ?>
  </tbody>
</table>

</div>

</section>


</section>








<?php include 'footer.php'; ?>

<!-- custom js file link  -->
<script src="js/script.js"></script>

</body>
</html>