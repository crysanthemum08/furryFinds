<?php
include 'config.php';

session_start();

$user_id = $_SESSION['user_id'];

if (!isset($user_id)) {
    header('location:login.php');
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Orders</title>

    <!-- Font Awesome CDN Link -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <!-- Custom CSS File Link -->
    <link rel="stylesheet" href="styles.css">
</head>
<body>

<?php include 'header.php'; ?>

<div class="heading">
    <h3>Your Orders</h3>
    <p><a href="home.php">Home</a> / Orders</p>
</div>

<section class="placed-orders">
    <h1 class="title">Placed Orders</h1>

    <div class="box-container">
        <?php
        // Fetch orders for the current user
        $order_query = mysqli_query($conn, "
            SELECT o.*, a.contact_num, a.address_line, a.barangay, a.city, a.state, a.postal_code, a.country
            FROM `orders` o
            LEFT JOIN `addresses` a ON o.address_id = a.address_id
            WHERE o.user_id = '$user_id'
        ") or die('Query failed');

        if (mysqli_num_rows($order_query) > 0) {
            while ($fetch_orders = mysqli_fetch_assoc($order_query)) {
        ?>
        <div class="box">
            <p>Placed on: <span><?php echo htmlspecialchars($fetch_orders['placed_on']); ?></span></p>
            <p>Name: <span><?php echo htmlspecialchars($fetch_orders['name']); ?></span></p>
            <p>Contact Number: <span><?php echo htmlspecialchars($fetch_orders['contact_num']); ?></span></p>
            <p>Email: <span><?php echo htmlspecialchars($fetch_orders['email']); ?></span></p>
            <p>Address: <span><?php echo htmlspecialchars($fetch_orders['address_line']) . ', ' . 
                htmlspecialchars($fetch_orders['barangay']) . ', ' . 
                htmlspecialchars($fetch_orders['city']) . ', ' . 
                htmlspecialchars($fetch_orders['state']) . ', ' . 
                htmlspecialchars($fetch_orders['postal_code']) . ', ' . 
                htmlspecialchars($fetch_orders['country']); ?></span></p>
            <p>Payment Method: <span><?php echo htmlspecialchars($fetch_orders['method']); ?></span></p>
            <p>Your Orders: <span><?php echo htmlspecialchars($fetch_orders['total_products']); ?></span></p>
            <p>Total Price: <span>₱<?php echo htmlspecialchars($fetch_orders['total_price']); ?></span></p>
            <p>Payment Status: 
                <span style="color:<?php echo $fetch_orders['payment_status'] == 'pending' ? 'red' : 'green'; ?>;">
                    <?php echo htmlspecialchars($fetch_orders['payment_status']); ?>
                </span>
            </p>
        </div>
        <?php
            }
        } else {
            echo '<p class="empty">No orders placed yet!</p>';
        }
        ?>
    </div>
</section>

<?php include 'footer.php'; ?>

<!-- Custom JS File Link -->
<script src="js/script.js"></script>

</body>
</html>
