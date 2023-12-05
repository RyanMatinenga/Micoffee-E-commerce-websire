<?php

include 'config.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if(!isset($admin_id)){
   header('location:login.php');
}

if(isset($_GET['delete'])){
   $delete_id = $_GET['delete'];
   mysqli_query($conn, "DELETE FROM `users` WHERE id = '$delete_id'") or die('query failed');
   header('location:admin_users.php');
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>users</title>

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

<section class="users">

<h1 class="title"> user accounts </h1>

<div class="box-container">
<table>
  <thead>
    <tr>
      <th>User ID</th>
      <th>Username</th>
      <th>Email</th>
      <th>User Type</th>
    </tr>
  </thead>
  <tbody>
    <?php
      $select_users = mysqli_query($conn, "SELECT * FROM `users`WHERE user_type= 'user'") or die('query failed');
      while($fetch_users = mysqli_fetch_assoc($select_users)){
    ?>
    <tr>
      <td><?= $fetch_users['id']; ?></td>
      <td><?= $fetch_users['name']; ?></td>
      <td><?= $fetch_users['email']; ?></td>
      <td><span style="color:<?php if($fetch_users['user_type'] == 'admin'){ echo 'var(--orange)'; } ?>"><?php echo $fetch_users['user_type']; ?></span></td>
    </tr>
    <?php
      };
    ?>
  </tbody>
</table>
</div>

</section>

</body>
</html>
