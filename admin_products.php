<?php
include 'config.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if (!isset($admin_id)) {
    header('location:login.php');
    exit;
}

$message = [];

if (isset($_POST['add_product'])) {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $price = $_POST['price'];
    $category = mysqli_real_escape_string($conn, $_POST['category']); // Get selected category
    $image = $_FILES['image']['name'];
    $image_size = $_FILES['image']['size'];
    $image_tmp_name = $_FILES['image']['tmp_name'];
    $image_folder = 'D:/xampp/htdocs/kapoy/images/' . $image;

    $select_product_name = mysqli_query($conn, "SELECT name FROM `products` WHERE name = '$name'") or die('Query failed');

    if (mysqli_num_rows($select_product_name) > 0) {
        $message[] = 'Product name already exists!';
    } else {
        $add_product_query = mysqli_query($conn, 
            "INSERT INTO `products` (name, price, category, image) 
             VALUES ('$name', '$price', '$category', '$image')") or die('Query failed');

        if ($add_product_query) {
            if ($image_size > 2000000) {
                $message[] = 'Image size is too large!';
            } else {
                move_uploaded_file($image_tmp_name, $image_folder);
                $message[] = 'Product added successfully!';
            }
        } else {
            $message[] = 'Product could not be added!';
        }
    }
}

if (isset($_GET['delete'])) {
    $delete_id = $_GET['delete'];
    $delete_image_query = mysqli_query($conn, "SELECT image FROM `products` WHERE id = '$delete_id'") or die('Query failed');
    $fetch_delete_image = mysqli_fetch_assoc($delete_image_query);
    unlink('images/' . $fetch_delete_image['image']);
    mysqli_query($conn, "DELETE FROM `products` WHERE id = '$delete_id'") or die('Query failed');
    header('location:admin_products.php');

}


if (isset($_POST['update_product'])) {
    $update_p_id = $_POST['update_p_id'];
    $update_name = $_POST['update_name'];
    $update_price = $_POST['update_price'];
    $update_category = $_POST['update_category'];

    // Update the product details in the database
    $update_query = "UPDATE `products` SET 
                        name = '$update_name', 
                        price = '$update_price', 
                        category = '$update_category' 
                     WHERE id = '$update_p_id'";
                     
    if (mysqli_query($conn, $update_query)) {
        // Handle image upload (if any)
        $update_image = $_FILES['update_image']['name'];
        $update_image_tmp_name = $_FILES['update_image']['tmp_name'];
        $update_image_size = $_FILES['update_image']['size'];
        $update_folder = 'uploaded_img/' . $update_image;
        $update_old_image = $_POST['update_old_image'];

        if (!empty($update_image)) {
            if ($update_image_size > 2000000) {
                echo "<script>alert('Image file size is too large');</script>";
            } else {
                $image_update_query = "UPDATE `products` SET image = '$update_image' WHERE id = '$update_p_id'";
                if (mysqli_query($conn, $image_update_query)) {
                    move_uploaded_file($update_image_tmp_name, $update_folder);

                    // Delete old image
                    if (file_exists('uploaded_img/' . $update_old_image)) {
                        unlink('uploaded_img/' . $update_old_image);
                    }
                }
            }
        }

        // Redirect to admin_products.php after a successful update
        header('Location: admin_products.php');
        exit;
    } else {
        echo "<script>alert('Error updating product: " . mysqli_error($conn) . "');</script>";
    }
}
?>





<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - Products</title>

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <!-- Custom Admin CSS -->
    <link rel="stylesheet" href="admin_styles.css">
</head>
<body>
<?php include 'admin_header.php'; ?>

<section class="add-products">
    <h1 class="title">Add New Product</h1>

    <form action="" method="post" enctype="multipart/form-data">
        <h3>Add Product</h3>
        <input type="text" name="name" class="box" placeholder="Enter product name" required>
        <input type="number" min="0" name="price" class="box" placeholder="Enter product price" required>
        <select name="category" class="box" required>
        <option value="" disabled selected>Select Category</option>
            <option value="Dog">Dog</option>
            <option value="Cat">Cat</option>
            <option value="Bird">Bird</option>
            <option value="Small Pet">Small Pet</option>
            <option value="Bird Food">Healthcare</option>
            <option value="Bird Food">Hygiene</option>
            <option value="Bird Food">Pet Supplies</option>
            <option value="Bird Food">Pet Accessories</option>
        </select>
        <input type="file" name="image" accept="image/jpg, image/jpeg, image/png" class="box" required>
        <input type="submit" value="Add Product" name="add_product" class="btn">
    </form>

    <?php
    // Ensure $message is an array and output messages
    if (!empty($message) && is_array($message)) {
        foreach ($message as $msg) {
            echo '<p class="message">' . $msg . '</p>';
        }
    }
    ?>
</section>


</section>
<section class="show-products">
    <h1 class="title">Shop Products</h1>
    <div class="box-container">
        <?php
        $select_products = mysqli_query($conn, "SELECT * FROM `products`") or die('Query failed');
        if (mysqli_num_rows($select_products) > 0) {
            while ($fetch_products = mysqli_fetch_assoc($select_products)) {
        ?>
        <div class="box">
            <img src="images/<?php echo $fetch_products['image']; ?>" alt="">
            <div class="name"><?php echo $fetch_products['name']; ?></div>
            <div class="price">₱<?php echo $fetch_products['price']; ?></div>
            <a href="admin_products.php?update=<?php echo $fetch_products['id']; ?>" class="option-btn">Update</a>
            <a href="admin_products.php?delete=<?php echo $fetch_products['id']; ?>" class="delete-btn" onclick="return confirm('Delete this product?');">Delete</a>
        </div>
        <?php
            }
        } else {
            echo '<p class="empty">No products added yet!</p>';
        }
        ?>
    </div>
</section>

<section class="edit-product-form">
   <?php
      if(isset($_GET['update'])){
         $update_id = $_GET['update'];
         $update_query = mysqli_query($conn, "SELECT * FROM `products` WHERE id = '$update_id'") or die('query failed');
         if(mysqli_num_rows($update_query) > 0){
            while($fetch_update = mysqli_fetch_assoc($update_query)){
   ?>
   <form id="update-form" action="" method="post" enctype="multipart/form-data">
  <h3 style="font-size: 28px; font-weight: bold;">EDIT PRODUCT</h3> 
      <input type="hidden" name="update_p_id" value="<?php echo $fetch_update['id']; ?>">
      <input type="hidden" name="update_old_image" value="<?php echo $fetch_update['image']; ?>">
      <img src="uploaded_img/<?php echo $fetch_update['image']; ?>" alt="">
      <input type="text" name="update_name" value="<?php echo $fetch_update['name']; ?>" class="box" required placeholder="enter product name">
      <input type="number" name="update_price" value="<?php echo $fetch_update['price']; ?>" min="0" class="box" required placeholder="enter product price">
      <select name="update_category" class="box" required>
         <option value="<?php echo $fetch_update['category']; ?>" selected><?php echo $fetch_update['category']; ?></option>
         <option value="Dog">Dog</option>
         <option value="Cat">Cat</option>
         <option value="Bird">Bird</option>
         <option value="Small Pet">Small Pet</option>
         <option value="Healthcare">Healthcare</option>
         <option value="Hygiene">Hygiene</option>
         <option value="Pet Supplies">Pet Supplies</option>
         <option value="Pet Accessories">Pet Accessories</option>
      </select>
      <input type="file" class="box" name="update_image" accept="image/jpg, image/jpeg, image/png">
      <input type="submit" value="update" name="update_product" class="btn">
      <input type="reset" value="cancel" id="close-update" class="option-btn">
   </form>
   <script>
      document.getElementById('close-update').addEventListener('click', function() {
         document.querySelector('.edit-product-form').style.display = 'none';
      });
      </script>
   <?php
         }
      }
      }else{
         echo '<script>document.querySelector(".edit-product-form").style.display = "none";</script>';
      }
   ?>
</section>



<script src="js/admin_script.js"></script>
</body>
</html>
