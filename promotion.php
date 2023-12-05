<?php
include 'config.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if (!isset($admin_id)) {
    header('location:login.php');
    exit; // Exit after redirect
}

// Handle promo code generation
if (isset($_POST['generate_promo'])) {
    // Generate a random promo code
    $random_number = mt_rand(100, 999);
    $random_letters = chr(mt_rand(65, 90)) . chr(mt_rand(65, 90));
    $promo_code = $random_number . $random_letters;

    // Store the promo code in the database with an associated discount percentage
    $discount_percentage = mysqli_real_escape_string($conn, $_POST['discount_percentage']);

    // Use prepared statement to insert data
    $insert_promo_query = $conn->prepare("INSERT INTO promo_codes (code, discount_percentage) VALUES (?, ?)");
    $insert_promo_query->bind_param("si", $promo_code, $discount_percentage);
    if ($insert_promo_query->execute()) {
        $message[] = 'Promo code added successfully!';
    } else {
        $message[] = 'Failed to add promo code!';
    }
}

if (isset($_POST['add_product'])) {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $image = $_FILES['image']['name'];
    $image_size = $_FILES['image']['size'];
    $image_tmp_name = $_FILES['image']['tmp_name'];
    $image_folder = 'uploaded_img/' . $image;

    // Check if the product name already exists
    $select_product_name = mysqli_query($conn, "SELECT name FROM promotions WHERE name = '$name'") or die('query failed');
    if (mysqli_num_rows($select_product_name) > 0) {
        $message[] = 'Product name already added';
    } else {
        $add_product_query = mysqli_query($conn, "INSERT INTO promotions (name, image) VALUES ('$name', '$image')") or die('query failed');

        if ($add_product_query) {
            if ($image_size > 2000000) {
                $message[] = 'Image size is too large';
            } else {
                move_uploaded_file($image_tmp_name, $image_folder);
                $message[] = 'Product added successfully!';
            }
        } else {
            $message[] = 'Failed to add product!';
        }
    }
}

if (isset($_GET['delete'])) {
    $delete_id = $_GET['delete'];
    $delete_image_query = mysqli_query($conn, "SELECT image FROM promotions WHERE id = '$delete_id'") or die('query failed');
    $fetch_delete_image = mysqli_fetch_assoc($delete_image_query);
    unlink('uploaded_img/' . $fetch_delete_image['image']);
    mysqli_query($conn, "DELETE FROM promotions WHERE id = '$delete_id'") or die('query failed');
    header('location:promotion.php');
    exit; // Exit after redirect
}

if (isset($_GET['delete_promo_code'])) {
    $delete_id = $_GET['delete_promo_code'];
    $delete_promo_code_query = $conn->prepare("DELETE FROM promo_codes WHERE id = ?");
    $delete_promo_code_query->bind_param("i", $delete_id);
    if ($delete_promo_code_query->execute()) {
        $message[] = 'Promo code deleted successfully!';
    } else {
        $message[] = 'Failed to delete promo code!';
    }
    header('location:promotion.php');
    exit; // Exit after redirect
}

if (isset($_POST['update_product'])) {
    $update_p_id = $_POST['update_p_id'];
    $update_name = mysqli_real_escape_string($conn, $_POST['update_name']);

    // Use prepared statement to update the product name
    $update_product_query = $conn->prepare("UPDATE promotions SET name = ? WHERE id = ?");
    $update_product_query->bind_param("si", $update_name, $update_p_id);

    if ($update_product_query->execute()) {
        $message[] = 'Product updated successfully!';
    } else {
        $message[] = 'Failed to update product!';
    }

    $update_image = $_FILES['update_image']['name'];
    $update_image_tmp_name = $_FILES['update_image']['tmp_name'];
    $update_image_size = $_FILES['update_image']['size'];
    $update_folder = 'uploaded_img/' . $update_image;
    $update_old_image = $_POST['update_old_image'];

    if (!empty($update_image)) {
        if ($update_image_size > 2000000) {
            $message[] = 'Image file size is too large';
        } else {
            $update_image_query = $conn->prepare("UPDATE promotions SET image = ? WHERE id = ?");
            $update_image_query->bind_param("si", $update_image, $update_p_id);
            if ($update_image_query->execute()) {
                move_uploaded_file($update_image_tmp_name, $update_folder);
                unlink('uploaded_img/' . $update_old_image);
                $message[] = 'Image updated successfully!';
            } else {
                $message[] = 'Failed to update image!';
            }
        }
    }

    header('location:promotion.php');
    exit; // Exit after redirect
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equivX-Content-Type:IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>promotions</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="css/admin_style.css">
    
    
    <style>
   input[type="text"]::placeholder{
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
</style>
    
</head>
<body>
<?php include 'admin_header.php'; ?>

<section class="add-products">
    <div class="box-container1">
        <div class="box1">
            <h3>Enjoy a new blend of coffee style</h3>
            <p>Explore all flavors of coffee with us. There is always a new cup worth experiencing</p>
        </div>
    </div>
    <div class="pros">
        <span>
            <form action="" method="post" enctype="multipart/form-data">
                <h3>Add Promotion</h3>
                <input type="text" name="name" class="box" placeholder="enter promotion Description" required>
                <input type="file" name="image" accept="image/jpg, image/jpeg, image/png" class="box" required>
                
                
                <input type="submit" value="add product" name="add_product" class="btn">
            </form>
        </span>
        <span>
            <form action="" method="post" enctype="multipart/form-data">
                <h3>Generate Promo Code</h3>
                <input type="number" min="0" name="discount_percentage" class="box"
                       placeholder="enter discount percentage" required>
                <button type="submit" name="generate_promo" class="btn">Generate Promo Code</button>
            </form>
        </span>
    </div>
</section>

<section class="show-products">
    <div class="box-container">
        <?php
        $select_products = mysqli_query($conn, "SELECT * FROM `promotions`") or die('query failed');
        if (mysqli_num_rows($select_products) > 0) {
            while ($fetch_products = mysqli_fetch_assoc($select_products)) {
                ?>
                <div class="box">
                    <img src="uploaded_img/<?php echo $fetch_products['image']; ?>" alt="">
                    <div class="name"><?php echo $fetch_products['name']; ?></div>
                    <a href="promotion.php?update=<?php echo $fetch_products['id']; ?>" class="option-btn">update</a>
                    <a href="promotion.php?delete=<?php echo $fetch_products['id']; ?>" class="delete-btn"
                       onclick="return confirm('delete this product?');">delete</a>
                </div>
                <?php
            }
        } else {
            echo '<p class="empty">no promotions added yet!</p>';
        }
        $select_promo_codes = mysqli_query($conn, "SELECT * FROM `promo_codes`") or die('query failed');
        if (mysqli_num_rows($select_promo_codes) > 0) {
            while ($promo_code = mysqli_fetch_assoc($select_promo_codes)) {
                ?>
                <div class="box">
                    <div class="name">Promo Code: <?php echo $promo_code['code']; ?></div>
                    <div class="price">Discount Percentage: <?php echo $promo_code['discount_percentage']; ?>%</div>
                    
                    <a href="promotion.php?delete_promo_code=<?php echo $promo_code['id']; ?>" class="delete-btn" 
                    onclick="return confirm('delete this promo code?');">delete</a>
                </div>
                <?php
            }
        }
        ?>
    </div>
</section>

<section class="edit-product-form">
    <?php
    if (isset($_GET['update'])) {
        $update_id = $_GET['update'];
        $update_query = mysqli_query($conn, "SELECT * FROM `promotions` WHERE id = '$update_id'") or die('query failed');
        if (mysqli_num_rows($update_query) > 0) {
            while ($fetch_update = mysqli_fetch_assoc($update_query)) {
                ?>
                <form action="" method="post" enctype="multipart/form-data">
                    <input type="hidden" name="update_p_id" value="<?php echo $fetch_update['id']; ?>">
                    <input type="hidden" name="update_old_image" value="<?php echo $fetch_update['image']; ?>">
                    <img src="uploaded_img/<?php echo $fetch_update['image']; ?>" alt="">
                    <input type="text" name="update_name" value="<?php echo $fetch_update['name']; ?>"
                           class="box" required placeholder="enter product name">
                    <input type="file" name="update_image" accept="image/jpg, image/jpeg, image/png">
                    <input type="submit" value="Update Product" name="update_product">
                </form>
                <?php
            }
        }
    } else {
        echo '<script>document.querySelector(".edit-product-form").style.display = "none";</script>';
    }
    ?>
</section>

<script src="js/admin_script.js"></script>
</body>
</html>
