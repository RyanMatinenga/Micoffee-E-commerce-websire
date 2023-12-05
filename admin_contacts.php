<?php
require_once 'email.php';
include 'config.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if(!isset($admin_id)){
   header('location:login.php');
};

if(isset($_GET['delete'])){
   $delete_id = $_GET['delete'];
   mysqli_query($conn, "DELETE FROM `message` WHERE id = '$delete_id'") or die('query failed');
   header('location:admin_contacts.php');
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Messages</title>

  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

  <link rel="stylesheet" href="css/admin_style.css">
<!--  -->


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




/* Common styles */
.messages {
  margin: 20px;
}

.title {
  font-size: 2rem;
  text-align: center;
}

.box-container {
  overflow-x: auto; /* Enable horizontal scrolling for small screens */
}

table {
  width: 100%;
  border-collapse: collapse;
}

table, th, td {
  border: 1px solid #ccc;
  padding: 10px;
}

/* Desktop styles */
@media (min-width: 768px) {
  .messages {
    margin: 20px 50px;
  }

  .title {
    font-size: 2.5rem;
  }

  .box-container {
    overflow-x: visible;
  }

  table {
    width: auto;
  }
}

/* Smartphone and tablet styles */
@media (max-width: 767px) {
  .messages {
    margin: 20px 10px;
  }

  .title {
    font-size: 2rem;
  }

  th, td {
    padding: 5px;
  }
}


  </style>


</head>
<body>

<?php include 'admin_header.php'; ?>

<section class="messages">
<h1 class="title"> messages </h1>

<div class="box-container">
<table>
  <thead>
    <tr>
      <th>User ID</th>
      <th>Name</th>
      <th>Email</th>
      <th>Message</th>
    </tr>
  </thead>
  <tbody>
    <?php
      $select_message = mysqli_query($conn, "SELECT * FROM `message`") or die('query failed');
      if(mysqli_num_rows($select_message) > 0){
        while($fetch_message = mysqli_fetch_assoc($select_message)){

          $user = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM `users` WHERE id = '{$fetch_message['user_id']}'"));

    ?>
    <tr>
      <td><?= $fetch_message['user_id']; ?></td>
      <td><?= $user['name']; ?></td>
      <td><?= $user['email']; ?></td>
      <td><?= $fetch_message['message']; ?></td>
      
    </tr>
    <?php
        }
      }else{
        echo '<tr><td colspan="4">No messages yet!</td></tr>';
      }
    ?>
  </tbody>
</table>
</div>

</section>









<script src="js/admin_script.js"></script>

</body>
</html>
