<?php
include 'config.php';

session_start();

$user_id = $_SESSION['user_id'];

if (!isset($user_id)) {
    header('location:login.php');
    exit();
}
date_default_timezone_set('Asia/Manila');
if (isset($_POST['placeorder_btn'])) {
    // Retrieve the selected address ID from the session
    $selected_address_id = isset($_SESSION['selected_address_id']) ? $_SESSION['selected_address_id'] : null;

    if (!$selected_address_id) {
        $message[] = 'Please select a delivery address before placing the order.';
    } else {
        // Fetch the details of the selected address
        $address_query = mysqli_query($conn, "SELECT * FROM `addresses` WHERE address_id = '$selected_address_id' AND user_id = '$user_id'") or die('Query failed');
        if (mysqli_num_rows($address_query) == 0) {
            $message[] = 'Invalid address selected. Please try again.';
        } else {
            $address_data = mysqli_fetch_assoc($address_query);

            // Extract address details
            $address_line = $address_data['address_line'];
            $barangay = $address_data['barangay'];
            $city = $address_data['city'];
            $state = $address_data['state'];
            $postal_code = $address_data['postal_code'];
            $country = $address_data['country'];
            $full_address = "$address_line, $barangay, $city, $state, $postal_code, $country";

            // Retrieve user details
            $user_query = mysqli_query($conn, "SELECT * FROM `users` WHERE id = '$user_id'") or die('Query failed');
            $user_data = mysqli_fetch_assoc($user_query);

            $name = $user_data['name'];
            $email = $user_data['email']; // Retrieve email address

            // Retrieve cart details
            $cart_total = 0;
            $cart_products = [];
            $cart_query = mysqli_query($conn, "SELECT * FROM `cart` WHERE user_id = '$user_id'") or die('Query failed');
            if (mysqli_num_rows($cart_query) > 0) {
                while ($cart_item = mysqli_fetch_assoc($cart_query)) {
                    $cart_products[] = $cart_item['name'] . ' (' . $cart_item['quantity'] . ')';
                    $cart_total += $cart_item['price'] * $cart_item['quantity'];
                }
            } else {
                $message[] = 'Your cart is empty.';
                return;
            }

            // Prepare order details
            $method = mysqli_real_escape_string($conn, $_POST['method']);
            $total_products = implode(', ', $cart_products);
            $placed_on = date('Y-m-d H:i:s');


             // Insert the address_id (as foreign key) and full address (for storage)
            $order_query = "INSERT INTO `orders` (user_id, address_id, address_line, name, email, method, total_products, total_price, placed_on) 
            VALUES ('$user_id', '$selected_address_id', '$full_address', '$name', '$email', '$method', '$total_products', '$cart_total', '$placed_on')";
            mysqli_query($conn, $order_query) or die('Query failed');
            $message[] = 'Order placed successfully!';

            // Clear user's cart
            mysqli_query($conn, "DELETE FROM `cart` WHERE user_id = '$user_id'") or die('Query failed');
        }
    }
}
?>






<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Checkout Confirmation</title>

   <!-- Font Awesome CDN -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

   <!-- Custom CSS -->
   <link rel="stylesheet" href="styles.css?v=1.0">
</head>

<body>
   
<?php include 'header.php'; ?>

<div class="heading">
   <h3>Checkout Confirmation</h3>
   <p><a href="home.php">Home</a> / Checkout</p>
</div>

<?php

?>
<div class="checkout-container">
   <!-- Address Section -->
   <div class="checkout-section">
      <h3>Delivery Address</h3>

      <?php
      $selected_address_id = isset($_SESSION['selected_address_id']) ? $_SESSION['selected_address_id'] : null;

      // Fetch the selected address if set, otherwise fetch the first address
      if ($selected_address_id) {
         $address_query = mysqli_query($conn, "SELECT * FROM `addresses` WHERE address_id = '$selected_address_id' AND user_id = '$user_id'");
      } else {
         $address_query = mysqli_query($conn, "SELECT * FROM `addresses` WHERE user_id = '$user_id' LIMIT 1");
      }

      if (mysqli_num_rows($address_query) > 0) {
         $address = mysqli_fetch_assoc($address_query);

         // Display the selected or default address
         echo '<div class="address-container">';
         echo '<div class="address-details">';
         echo '<p><strong>Contact:</strong> ' . htmlspecialchars($address['contact_num']) . '</p>';
         echo '<p><strong>Address:</strong> ' . htmlspecialchars($address['address_line']) . ', ' . 
            htmlspecialchars($address['barangay']) . ', ' . 
            htmlspecialchars($address['city']) . ', ' . 
            htmlspecialchars($address['state']) . ', ' . 
            htmlspecialchars($address['postal_code']) . ', ' . 
            htmlspecialchars($address['country']) . '</p>';
         echo '</div>';

         // Move the Edit Address link to the bottom
         echo '<a href="address.php" class="edit-btn"><i class="fa fa-edit"></i> Edit Address</a>';
         echo '</div>';
      } else {
         // No address found
         echo '<p>No address found. Please add one.</p>';
         echo '<form method="POST" action="add_address.php">';
         echo '<button type="submit" class="btn">Add Address</button>';
         echo '</form>';
      }
      ?>
   </div>








   <!-- Order Information Section -->
   <div class="checkout-section">
      <h3>Order Information</h3>
      <table>
         <thead>
            <tr>
               <th>Product</th>
               <th>Quantity</th>
               <th>Price</th>
            </tr>
         </thead>
         <tbody>
            <?php
               $cart_total = 0;
               $cart_query = mysqli_query($conn, "SELECT * FROM `cart` WHERE user_id = '$user_id'") or die('query failed');
               if(mysqli_num_rows($cart_query) > 0){
                  while($cart_item = mysqli_fetch_assoc($cart_query)){
                     $sub_total = $cart_item['price'] * $cart_item['quantity'];
                     $cart_total += $sub_total;
                     echo "<tr>
                           <td>".$cart_item['name']."</td>
                           <td>".$cart_item['quantity']."</td>
                           <td>₱".$sub_total."</td>
                        </tr>";
                  }
               }
            ?>
         </tbody>
      </table>
      <p><strong>Total Price: </strong>₱<?php echo $cart_total; ?></p>
   </div>

   <!-- Payment Section -->
   <div class="checkout-section">
      <h3>Payment Details</h3>
      <form method="POST">
         <label for="method">Select Payment Method:</label>
         <select name="method" required>
            <option value="credit_card">Credit Card</option>
            <option value="paypal">PayPal</option>
            <option value="cod">Cash on Delivery</option>
         </select>
         <button type="submit" name="placeorder_btn" class="btn">Place Order</button>
      </form>
   </div>
</div>

<?php include 'footer.php'; ?>

<script src="js/script.js"></script>

</body>
</html>