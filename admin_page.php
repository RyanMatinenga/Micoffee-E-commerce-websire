<?php
require_once 'email.php';
include 'config.php';

session_start();
 
$admin_id = $_SESSION['admin_id'];
 
if(!isset($admin_id)){
   header('location:login.php');
}
 
// Calculate today's date
$today_date = date("Y-m-d");
 
$total_completed_today = 0;
// Query to select completed orders for today
$select_completed_today = mysqli_query($conn, "SELECT total_price FROM `orders` WHERE payment_status = 'completed' AND DATE(order_date) = '$today_date'") or die('query failed');
if(mysqli_num_rows($select_completed_today) > 0){
    while($fetch_completed_today = mysqli_fetch_assoc($select_completed_today)){
        $total_price_today = $fetch_completed_today['total_price'];
        $total_completed_today += $total_price_today;
    }
}
 
// Calculate start and end dates for this week
$startOfWeek = date('Y-m-d', strtotime('last Sunday', strtotime($today_date)));
$endOfWeek = date('Y-m-d', strtotime('next Saturday', strtotime($today_date)));
 
$total_completed_weekly = 0;
// Query to select completed orders for the current week
$select_completed_weekly = mysqli_query($conn, "SELECT total_price FROM `orders` WHERE payment_status = 'completed' AND DATE(order_date) BETWEEN '$startOfWeek' AND '$endOfWeek'") or die('query failed');
if(mysqli_num_rows($select_completed_weekly) > 0){
    while($fetch_completed_weekly = mysqli_fetch_assoc($select_completed_weekly)){
        $total_price_weekly = $fetch_completed_weekly['total_price'];
        $total_completed_weekly += $total_price_weekly;
    }
}
 
// Calculate start and end dates for this month
$startOfMonth = date('Y-m-01', strtotime($today_date));
$endOfMonth = date('Y-m-t', strtotime($today_date));
 
$total_completed_monthly = 0;
// Query to select completed orders for the current month
$select_completed_monthly = mysqli_query($conn, "SELECT total_price FROM `orders` WHERE payment_status = 'completed' AND DATE(order_date) BETWEEN '$startOfMonth' AND '$endOfMonth'") or die('query failed');
if(mysqli_num_rows($select_completed_monthly) > 0){
    while($fetch_completed_monthly = mysqli_fetch_assoc($select_completed_monthly)){
        $total_price_monthly = $fetch_completed_monthly['total_price'];
        $total_completed_monthly += $total_price_monthly;
    }
}
 
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['special_code'])) {
    $specialCode = mysqli_real_escape_string($conn, $_POST['special_code']);
    $correctCode = "MiCoffeeAdmin007"; // Replace this with your correct special code
 
    // Check if the entered special code is correct
    if ($specialCode === $correctCode) {
        // Fetch analytics data from the database
        $selectCompleted = mysqli_query($conn, "SELECT SUM(total_price) as total_completed FROM `orders` WHERE payment_status = 'completed'");
        $total_completed = mysqli_fetch_assoc($selectCompleted)['total_completed'];
 
        $selectPending = mysqli_query($conn, "SELECT SUM(total_price) as total_pendings FROM `orders` WHERE payment_status = 'pending'");
        $total_pendings = mysqli_fetch_assoc($selectPending)['total_pendings'];
 
        $selectUsers = mysqli_query($conn, "SELECT COUNT(*) as number_of_users FROM `users` WHERE user_type = 'user'");
        $number_of_users = mysqli_fetch_assoc($selectUsers)['number_of_users'];
 
        $selectAdmins = mysqli_query($conn, "SELECT COUNT(*) as number_of_admins FROM `users` WHERE user_type = 'admin'");
        $number_of_admins = mysqli_fetch_assoc($selectAdmins)['number_of_admins'];
 
        $selectOrders = mysqli_query($conn, "SELECT COUNT(*) as number_of_orders FROM `orders`");
        $number_of_orders = mysqli_fetch_assoc($selectOrders)['number_of_orders'];
 
        $emailContent = "Completed Payments: R{$total_completed}\n";
        $emailContent .= "Total Pendings: R{$total_pendings}\n";
        $emailContent .= "Customer Accounts: {$number_of_users}\n";
        $emailContent .= "Admin Accounts: {$number_of_admins}\n";
        $emailContent .= "Order Placed: {$number_of_orders}\n";
        $emailContent .= "Completed Payments Today: R{$total_completed_today}\n";
        $emailContent .= "Completed Payments This Week: R{$total_completed_weekly}\n";
        $emailContent .= "Completed Payments This Month: R{$total_completed_monthly}\n";
 
        $email = new \SendGrid\Mail\Mail();
        $email->setFrom("denzelhadebe7@gmail.com", "MiCoffee");
        $email->setSubject("Analytics Report");
        $email->addTo("daniel.lukayi@gmail.com", "Recipient Name");
        $email->addContent("text/plain", $emailContent);
 
        $sendgrid = new \SendGrid('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'); // Replace with your SendGrid API key
        try {
            $response = $sendgrid->send($email);
            echo '<span style="color: white;">Email sent successful</span>';
        } catch (Exception $e) {
            echo 'Caught exception: ',  $e->getMessage(), "\n";
        }
    } else {
        echo '<span style="color: white;">Invalid code. Try again!</span>';
    }
}
?>
 
 
 
<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>admin panel</title>
 
   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
 
   <!-- custom admin css file link  -->
   <link rel="stylesheet" href="css/admin_style.css">
 
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
 

        form {
            display: flex;
            flex-direction: column;
            max-width: 300px;
            margin: 0 auto;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            background-color: #4a8522;
        }

        label {
            margin-bottom: 10px;
            font-weight: bold;
        }

        input[type="text"] {
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
        }

        button[type="submit"] {
            background-color: #4caf50;
            color: white;
            border: none;
            border-radius: 5px;
            padding: 15px 20px;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s;
        }

        button[type="submit"]:hover {
            background-color: #45a049;
        }
  
 
 
 /* Style the reset button */
      #resetButton {
         background-color: #ff5555;
         color: #ffffff;
         padding: 20px 80px; /* Add more padding for spacing */
         border: none;
         cursor: pointer;
         font-size: 16px;
         border-radius: 5px;
         
      }
      #resetButton:hover {
         background-color: #ff0000;
      }
 
 
  </style>
 
</head>
<body>
   
<?php include 'admin_header.php'; ?>
 
   <h1 class="title">Analytics</h1>
 
<!-- admin dashboard section starts  -->
 
 
<!-- admin dashboard section ends -->
        <main>
 
            <!-- Analyses -->
            <div class="analyse">
 
 
 
                <div class="sales">
                    <div class="status">
                        <div class="info">
                        <img src="images/logo.png" alt="Description of the image">
         <?php
            $total_completed = 0;
            $select_completed = mysqli_query($conn, "SELECT total_price FROM `orders` WHERE payment_status = 'completed'") or die('query failed');
            if(mysqli_num_rows($select_completed) > 0){
               while($fetch_completed = mysqli_fetch_assoc($select_completed)){
                  $total_price = $fetch_completed['total_price'];
                  $total_completed += $total_price;
               };
            };
         ?>        
         <h3>Completed Payments</h3>
         <h1>R<?php echo $total_completed; ?></h1>
 
                        </div>
 
                    </div>
                </div>
 
 
 
 
                <div class="sales">
                    <div class="status">
                        <div class="info">
                        <img src="images/logo.png" alt="Description of the image">
         <?php
            $total_pendings = 0;
            $select_pending = mysqli_query($conn, "SELECT total_price FROM `orders` WHERE payment_status = 'pending'") or die('query failed');
            if(mysqli_num_rows($select_pending) > 0){
               while($fetch_pendings = mysqli_fetch_assoc($select_pending)){
                  $total_price = $fetch_pendings['total_price'];
                  $total_pendings += $total_price;
               };
            };
         ?>  
         <h3>Total Pendings</h3>
         <h1>R<?php echo $total_pendings; ?></h1>
                        </div>
                       
                    </div>
                </div>
 
 
 
 
<div class="status" onclick="location.href='customer.php';" style="cursor: pointer;">
                    <div class="status">
                        <div class="info">
                        <img src="images/logo.png" alt="Description of the image">
                           
         <?php
            $select_users = mysqli_query($conn, "SELECT * FROM `users` WHERE user_type = 'user'") or die('query failed');
            $number_of_users = mysqli_num_rows($select_users);
         ?>  
         <h3>Customers Accounts</h3>
         <h1><?php echo $number_of_users; ?></h1>
 
                        </div>
                       
                    </div>
                </div>
 
 
                                <div class="sales">
                    <div class="status">
                        <div class="info">
                        <img src="images/logo.png" alt="Description of the image">
         <?php
            $select_users = mysqli_query($conn, "SELECT * FROM `users` WHERE user_type = 'user'") or die('query failed');
            $number_of_users = mysqli_num_rows($select_users);
         ?>
 
         <h3>Customer Accounts</h3>
         <h1><?php echo $number_of_users; ?></h1>
         
 
                        </div>
                       
                    </div>
                </div>
 
 
<div class="status" onclick="location.href='adminusers.php';" style="cursor: pointer;">
                    <div class="status">
                        <div class="info">
                        <img src="images/logo.png" alt="Description of the image">
         <?php
            $select_admins = mysqli_query($conn, "SELECT * FROM `users` WHERE user_type = 'admin'") or die('query failed');
            $number_of_admins = mysqli_num_rows($select_admins);
         ?>  
 
         <h3>Admin Accounts</h3>
         <h1><?php echo $number_of_admins; ?></h1>
 
 
                        </div>
                       
                    </div>
                </div>
 
 
 
 
 
 
 
 
 
                 <div class="sales">
                    <div class="status">
                        <div class="info">
                        <img src="images/logo.png" alt="Description of the image">
         <?php
            $select_orders = mysqli_query($conn, "SELECT * FROM `orders`") or die('query failed');
            $number_of_orders = mysqli_num_rows($select_orders);
           
         ?>
         
         <h3>Order Placed</h3>
         <h1><?php echo $number_of_orders; ?></h1>
 
         
 
 
                        </div>
                       
                    </div>
                </div>
                
                
                
               
<div class="status" onclick="location.href='Today.php';" style="cursor: pointer;">
                    <div class="status">
                        <div class="info">
                        <img src="images/logo.png" alt="Description of the image">
         <?php
            $select_orders = mysqli_query($conn, "SELECT * FROM `orders`") or die('query failed');
            $number_of_orders = mysqli_num_rows($select_orders);
           
         ?>
         
       
            <h3>Completed Payments Today</h3>
            <h1>R<?php echo $total_completed_today; ?></h1>
       
 
         
 
 
                        </div>
                       
                    </div>
                </div>
               
               
               
               
                                                <div class="sales">
<div class="status" onclick="location.href='weekly.php';" style="cursor: pointer;">
                        <div class="info">
                        <img src="images/logo.png" alt="Description of the image">
         <?php
            $select_orders = mysqli_query($conn, "SELECT * FROM `orders`") or die('query failed');
            $number_of_orders = mysqli_num_rows($select_orders);
           
         ?>
         
            <h3>Completed Payments This Week</h3>
            <h1>R<?php echo $total_completed_weekly; ?></h1>
       
 
         
 
 
                        </div>
                       
                    </div>
                </div>
               
               
                                                               <div class="sales">
<div class="status" onclick="location.href='wholemonth.php';" style="cursor: pointer;">
                        <div class="info">
                        <img src="images/logo.png" alt="Description of the image">
         <?php
            $select_orders = mysqli_query($conn, "SELECT * FROM `orders`") or die('query failed');
            $number_of_orders = mysqli_num_rows($select_orders);
           
         ?>
            <h3>Completed Payments This Month</h3>
            <h1>R<?php echo $total_completed_monthly; ?></h1>
       
 
         
 
 
                        </div>
                       
                    </div>
                </div>
               
               
               
            </div>
 
         </main>
 
 
 
</section>
 
 
 
<form method="post">
    <label for="special_code">Enter code for the analysis report to be sent to the email:</label>
    <input type="text" id="special_code" name="special_code" required>
    <button type="submit" name="submit">Submit</button>
</form>
 
 
 
 
 
 
<!-- custom admin js file link  -->
<script src="js/admin_script.js"></script>
 
 
 
 
 
 
 
</body>
</html>