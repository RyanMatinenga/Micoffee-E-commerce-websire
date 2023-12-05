<?php

include 'config.php';

session_start();

// $user_id = $_SESSION['user_id'];

// if(!isset($user_id)){
//    header('location:login.php');
// }

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>about</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">

</head>
<body>
   
<?php include 'header.php'; ?>

<div class="heading">
   <h3>about us</h3>
   <p> <a href="home.php">home</a> / about </p>
</div>

<section class="about">

<div class="box-container">

<div class="box">
   <h3>Our Vision </h3>
   <p> To have as many outlets as possible.To have our own roasting section to support our existing and future outlets.To be part of those on a mission to eradicate poverty in South Africa, by upskilling young people in this field by sharing our expertise and training within the industry of brewing coffee.To reach out to charities and encourage other like-minded entrepreneurs within our coffee family to give back to the communities they are a part of.</p>
</div>

<div class="box">
   <h3>Our Values</h3>
   <p>To treat every customer, client, supplier and visitor with respect, dignity and with utmost professionalism.To be in a position of knowledge so that we can provide answers to questions & educate our customers on all aspects regarding coffee and our products.</p>
</div>

<div class="box">
   <h3>Our Mission</h3>
  <p>To be the most trusted, loved and the most reliable coffee serving business in and outside of South Africa. </p>
 
</div>



</div>





</section>

<section class="about">

   <div class="grid">
  <div class="div-1"><img src="images/image_9.png" alt="" style="max-width: 80%; object-fit: cover; "></div>
  <div class="div-2"><img src="images/image_10.png" alt="" style="max-width: 80%; object-fit: cover; "></div>
  <div class="div-3"><p>We currently have two fixed stores: our pilot kiosk opened its doors in February 2015 sharing premises with Pro-Natural at 152 Main Road, Walmer. After extensive renovations to the centre and a growing clientele, Micoffee moved to its own premises at the entrance to the centre in April 2016. Our latest is a coffee shop situated at 106 Hurd Street, Newton Park which opened its doors in June 2016. Another coffee kiosk is soon to open so watch this space.If you want to be part of this amazing family of coffee enthusiasts contact us.</p></div>
</div>



</section>













<?php include 'footer.php'; ?>

<!-- custom js file link  -->
<script src="js/script.js"></script>

</body>
</html>