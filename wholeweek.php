<?php

include 'config.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if (!isset($admin_id)) {
   header('location:login.php');
}

if (isset($_GET['delete'])) {
   $delete_id = $_GET['delete'];
   mysqli_query($conn, "DELETE FROM `orders` WHERE id = '$delete_id'") or die('query failed');
   header('location:admin_orders.php');
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Orders</title>

  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

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
   </style>

</head>
<body>

<?php include 'admin_header.php'; ?>


  <!-- Button to redirect to weekly.php -->
  <button type="button" class="delete-btn" onclick="window.location.href = 'weekly.php'">Go back to weekly analytics </button>
<section class="orders">
  <h1 class="title">Weekly Sales</h1>
  <div class="box-container">
    <table>
      <thead>
        <tr>
          <th>User ID</th>
          <th>Placed on</th>
          <th>Name</th>
          <th>Order Number</th>
          <th>Email</th>
          <th>Total products</th>
          <th>Total price</th>
        </tr>
      </thead>
      <tbody>
        <?php
        $today_date = date("Y-m-d");

        // Find the first and last day of the current week
        $firstDay = date('Y-m-d', strtotime('last Monday', strtotime($today_date)));
        $lastDay = date('Y-m-d', strtotime('next Sunday', strtotime($today_date)));

        $select_orders = mysqli_query($conn, "SELECT * FROM `orders` WHERE DATE(order_date) BETWEEN '$firstDay' AND '$lastDay' ORDER BY id DESC") or die('query failed');

        $totalSalesWeek = 0; // Variable to store the total sales for the week

        if (mysqli_num_rows($select_orders) > 0) {
            while ($fetch_orders = mysqli_fetch_assoc($select_orders)) {
                $user = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM `users` WHERE id = '{$fetch_orders['user_id']}'"));

                $totalSalesWeek += $fetch_orders['total_price']; // Calculate the total sales for the week

                ?>
                <tr>
                    <td><?= $fetch_orders['user_id']; ?></td>
                    <td><?= $fetch_orders['placed_on']; ?></td>
                    <td><?= $user['name']; ?></td>
                    <td><?= $fetch_orders['Order_Number']; ?></td>
                    <td><?= $user['email']; ?></td>
                    <td><?= $fetch_orders['total_products']; ?></td>
                    <td>R<?= $fetch_orders['total_price']; ?></td>
                </tr>
                <?php
            }
        } else {
            echo '<p>No orders placed this week!</p>';
        }
        ?>

        <tr>
          <td colspan="6">Total Sales for the Week:</td>
          <td>R<?= $totalSalesWeek ?></td>
        </tr>
      </tbody>
    </table>
  </div>
</section>

<script src="js/admin_script.js"></script>


</body>
</html>
