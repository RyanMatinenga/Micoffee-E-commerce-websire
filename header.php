<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Your head content here -->
    <link rel="stylesheet" href="styles.css"> <!-- Link to your CSS file -->
    
    <style>
      /* Default styles for all screen sizes */
.nuts {
    width: 20%;
    text-align: center;
    margin: auto;
}

.nuts img {
    max-width: 100%;
    height: auto;
}

/* Responsive styles for tablets (e.g., iPad) */
@media screen and (max-width: 1024px) {
    .nuts {
        width: 30%; /* Adjust the width as needed */
    }
}

/* Responsive styles for smartphones (e.g., iPhone) */
@media screen and (max-width: 768px) {
    .nuts {
        width: 50%; /* Adjust the width as needed */
    }
}

    </style>
    
</head>

<body>
    <?php
    include 'config.php';
    require_once 'email.php';

    // Get the current page name
    $current_page = basename($_SERVER['PHP_SELF']);

    // Handle messages
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

    // Check cart count
    if (isset($_SESSION['user_id'])) {
        $user_id = $_SESSION['user_id'];
        $select_cart_number = mysqli_query($conn, "SELECT * FROM `cart` WHERE user_id = '$user_id'") or die('query failed');
        $cart_rows_number = mysqli_num_rows($select_cart_number);
    } else {
        $cart_rows_number = 0;
    }
    ?>

    <header class="header">
        <div class="header-2">
            <div class="flex">
                <div class="nuts" style="width: 20%; text-align: center; margin: auto;">
                   <a href="home.php">
                       <img src="images/logo.png" alt="" style="max-width: 100%; height: auto;">
                   </a>
                </div>
                <nav class="navbar">
                    <a href="home.php" <?php if($current_page == 'home.php') echo 'class="active"'; ?>>Home</a>
                    <a href="about.php" <?php if($current_page == 'about.php') echo 'class="active"'; ?>>About</a>
                    <a href="shop.php" <?php if($current_page == 'shop.php') echo 'class="active"'; ?>>Menu</a>
                    <a href="contact.php" <?php if($current_page == 'contact.php') echo 'class="active"'; ?>>Contact Us</a>
                    <a href="orders.php" <?php if($current_page == 'orders.php') echo 'class="active"'; ?>>Order history</a>
                    <a href="user_promotion.php" <?php if($current_page == 'user_promotion.php') echo 'class="active"'; ?>>Promotions</a>
                </nav>
                <div class="icons">
                    <div id="menu-btn" class="fas fa-bars"></div>
                    <a href="search_page.php" class="fas fa-search"></a>
                    <div id="user-btn" class="btn">Profile</div>
                    <a href="cart.php" class="btn">Cart<span>(<?php echo $cart_rows_number; ?>)</span></a>
                </div>
                <div class="user-box">
                    <?php
                    if(isset($_SESSION['user_id'])){
                        echo '<p>username : <span>' . $_SESSION['user_name'] . '</span></p>';
                        echo '<p>email : <span>' . $_SESSION['user_email'] . '</span></p>';
                        echo '<a href="logout.php" class="delete-btn">logout</a>';
                    }
                    ?>
                </div>
            </div>
        </div>
    </header>

    <!-- Rest of your HTML content -->

</body>

</html>
