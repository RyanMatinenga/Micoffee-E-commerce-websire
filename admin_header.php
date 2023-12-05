<?php
if(isset($message)){
   foreach($message as $message){
      echo '
      <div class="message">
         <span>'.$message.'</span>
         <i class="fas fa-times" onclick="this.parentElement.remove();"></i>
      </div>
      ';
   }
}
?>

<header class="header">
   <div class="flex">
      <img src="images/logo.png" alt="" style="max-width: 20%; height: auto; ">
      <nav class="navbar">
         <a href="admin_page.php" <?php if(basename($_SERVER['PHP_SELF']) == 'admin_page.php'){ echo 'class="active"'; } ?>>Home</a>
         <a href="admin_products.php" <?php if(basename($_SERVER['PHP_SELF']) == 'admin_products.php'){ echo 'class="active"'; } ?>>Products</a>
         <a href="admin_orders.php" <?php if(basename($_SERVER['PHP_SELF']) == 'admin_orders.php'){ echo 'class="active"'; } ?>>Orders</a>
         <a href="admin_users.php" <?php if(basename($_SERVER['PHP_SELF']) == 'admin_users.php'){ echo 'class="active"'; } ?>>Users</a>
         <a href="admin_contacts.php" <?php if(basename($_SERVER['PHP_SELF']) == 'admin_contacts.php'){ echo 'class="active"'; } ?>>Messages</a>
         <a href="promotion.php" <?php if(basename($_SERVER['PHP_SELF']) == 'promotion.php'){ echo 'class="active"'; } ?>>Promotions</a>
      </nav>
      <div class="icons">
         <div id="menu-btn" class="fas fa-bars"></div>
         <div id="user-btn" class="btn">Profile</div>
      </div>
      <div class="account-box">
         <p>Username : <span><?php echo $_SESSION['admin_name']; ?></span></p>
         <p>Email : <span><?php echo $_SESSION['admin_email']; ?></span></p>
         <a href="logout.php" class="delete-btn">Logout</a>
      </div>
   </div>
</header>