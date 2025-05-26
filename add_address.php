<?php

include 'config.php';

session_start();

$user_id = $_SESSION['user_id'];
if (!$user_id) {
    header('location:login.php');
    exit();
}

if (isset($_POST['save_address_btn'])) {
    // Sanitize and retrieve user input
    $flat = mysqli_real_escape_string($conn, $_POST['flat']);
    $street = mysqli_real_escape_string($conn, $_POST['street']);
    $barangay = mysqli_real_escape_string($conn, $_POST['barangay']);
    $city = mysqli_real_escape_string($conn, $_POST['city']);
    $state = mysqli_real_escape_string($conn, $_POST['state']);
    $country = mysqli_real_escape_string($conn, $_POST['country']);
    $pin_code = mysqli_real_escape_string($conn, $_POST['pin_code']);
    $contact_num = mysqli_real_escape_string($conn, $_POST['number']);

    // Create address line
    $address_line = "$flat, $street, $barangay";

    // Insert new address into the `addresses` table
    $insert_address_query = "INSERT INTO `addresses` 
        (`user_id`, `address_line`, `barangay`, `city`, `state`, `postal_code`, `country`, `contact_num`, `created_at`) 
        VALUES 
        ('$user_id', '$address_line','$barangay', '$city', '$state', '$pin_code', '$country', '$contact_num', CURRENT_TIMESTAMP)";

    if (mysqli_query($conn, $insert_address_query)) {
        // Redirect back to address.php to show the updated list of addresses
        header('location:address.php');
        exit();
    } else {
        $message[] = 'Error saving address: ' . mysqli_error($conn);
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Save Address</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="styles.css">
</head>
<body>

<?php include 'header.php'; ?>

<div class="heading">
   <h3>Save Address</h3>
   <p> <a href="home.php">home</a> / save address </p>
</div>
<section class="checkout">
   <form action="" method="post">
      <h3>Save Address</h3>
      <div class="flex">
         <div class="inputBox">
            <span>Flat/Building Number:</span>
            <input type="text" name="flat" required placeholder="e.g. Flat No.">
         </div>
         <div class="inputBox">
            <span>Street Name:</span>
            <input type="text" name="street" required placeholder="e.g. Street Name">
         </div>
         <div class="inputBox">
            <span>Barangay:</span>
            <input type="text" name="barangay" required placeholder="e.g. Barangay Name">
         </div>
         <div class="inputBox">
            <span>City:</span>
            <input type="text" name="city" required placeholder="e.g. Manila">
         </div>
         <div class="inputBox">
            <span>State:</span>
            <input type="text" name="state" required placeholder="e.g. Metro Manila">
         </div>
         <div class="inputBox">
            <span>Country:</span>
            <input type="text" name="country" required placeholder="e.g. Philippines">
         </div>
         <div class="inputBox">
            <span>Pin Code:</span>
            <input type="number" min="1000" max="9999" name="pin_code" required placeholder="e.g. 1234">
         </div>
         <div class="inputBox">
            <span>Contact Number:</span>
            <input type="text" name="number" required placeholder="e.g. 09123456789" pattern="\d{10,11}" title="Enter a valid 10- or 11-digit phone number">
         </div>
      </div>
      <input type="submit" value="Save Address" class="btn" name="save_address_btn">
   </form>

   <?php
   if (!empty($message)) {
       foreach ($message as $msg) {
           echo '<p class="message">' . $msg . '</p>';
       }
   }
   ?>
</section>

<?php include 'footer.php'; ?>

<!-- custom js file link  -->
<script src="js/script.js"></script>

</body>
</html>
