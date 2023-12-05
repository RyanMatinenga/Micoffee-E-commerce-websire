

<?php
include 'config.php';

// Check if the admin is logged in and authorized to reset data
session_start();
$admin_id = $_SESSION['admin_id'];

if (!isset($admin_id)) {
   // Redirect to login page if not logged in
   header('location:login.php');
   exit();
}

// Add code to reset your data here, e.g., truncate tables, delete records, etc.
// Example: mysqli_query($conn, "TRUNCATE TABLE orders");

mysqli_query($conn, "TRUNCATE TABLE orders");


// Provide a success message
echo "Data reset successfully.";

// Close the database connection and perform any other necessary cleanup
mysqli_close($conn);
?>
