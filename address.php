<?php
include 'config.php';
session_start();
$user_id = $_SESSION['user_id'];

if (!isset($user_id)) {
    header('location:login.php');
    exit();
}

// Fetch all addresses for the user
$address_query = mysqli_query($conn, "SELECT * FROM addresses WHERE user_id = '$user_id'");

// Handle address selection
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['selected_address_id'])) {
    $_SESSION['selected_address_id'] = $_POST['selected_address_id'];
    header('Location: checkout_confirmation.php');
    exit();
}

// Handle deleting address
if (isset($_GET['delete_address_id'])) {
    $delete_address_id = $_GET['delete_address_id'];
    $delete_query = mysqli_query($conn, "DELETE FROM addresses WHERE address_id = '$delete_address_id' AND user_id = '$user_id'");

    if ($delete_query) {
        header('Location: address.php');
        exit();
    } else {
        echo '<p>Error deleting address. Please try again later.</p>';
    }
}

// Handle address editing
// Fetch the selected address for editing
if (isset($_GET['edit_address_id'])) {
    $edit_address_id = $_GET['edit_address_id'];
    $address_query = mysqli_query($conn, "SELECT * FROM addresses WHERE address_id = '$edit_address_id' AND user_id = '$user_id'");
    
    // Ensure only one address is returned
    if (mysqli_num_rows($address_query) > 0) {
        $address = mysqli_fetch_assoc($address_query);
    }

}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_address'])) {
    $address_id = $_POST['address_id'];
    $contact_num = $_POST['contact_num'];
    $address_line = $_POST['address_line'];
    $barangay = $_POST['barangay'];
    $city = $_POST['city'];
    $state = $_POST['state'];
    $postal_code = $_POST['postal_code'];
    $country = $_POST['country'];

    $update_query = mysqli_query($conn, "UPDATE addresses SET 
        contact_num = '$contact_num', 
        address_line = '$address_line', 
        barangay = '$barangay', 
        city = '$city', 
        state = '$state', 
        postal_code = '$postal_code', 
        country = '$country' 
        WHERE address_id = '$address_id' AND user_id = '$user_id'");

    if ($update_query) {
        header('Location: address.php');
        exit();
    } else {
        echo '<p>Error updating address. Please try again later.</p>';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Manage Addresses</title>

   <!-- Font Awesome CDN -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

   <!-- Custom CSS -->
   <link rel="stylesheet" href="styles.css?v=1.0">
</head>
<body>

<?php include 'header.php'; ?>

<div class="address-selection-container">
    <div class="add-address-btn">
        <a href="add_address.php" class="btn-add">+ Add Address</a>
    </div>

    <!-- Address Selection Form -->
    <?php if (!isset($address)) { ?>
        <h3>Your Address</h3>
        <form method="POST" action="address.php">
            <?php
            if (mysqli_num_rows($address_query) > 0) {
                while ($address = mysqli_fetch_assoc($address_query)) {
                    $is_selected = (isset($_SESSION['selected_address_id']) && $_SESSION['selected_address_id'] == $address['address_id']) ? 'checked' : '';

                    echo '<div class="address-container">';
                    echo '<label style="display: flex; align-items: center;">';
                    echo '<input type="radio" name="selected_address_id" value="' . $address['address_id'] . '" ' . $is_selected . ' required style="margin-right: 10px;">';
                    echo '<div class="address-details">';
                    echo '<p><strong>Phone Number:</strong> ' . htmlspecialchars($address['contact_num']) . '</p>';
                    echo '<p>' . htmlspecialchars($address['barangay']) . ', ' . 
                        htmlspecialchars($address['city']) . ', ' . 
                        htmlspecialchars($address['state']) . ', ' . 
                        htmlspecialchars($address['postal_code']) . ', ' . 
                        htmlspecialchars($address['country']) . '</p>';
                    echo '</div>';
                    echo '</label>';
                    echo '<div class="address-actions">';
                    echo '<a href="address.php?edit_address_id=' . $address['address_id'] . '" class="action-icon" title="Edit"><i class="fas fa-solid fa-edit"></i></a>';
                    echo '<a href="?delete_address_id=' . $address['address_id'] . '" class="action-icon" title="Delete" onclick="return confirm(\'Are you sure you want to delete this address?\')"><i class="fas fa-solid fa-trash-alt"></i></a>';
                    echo '</div>';
                    echo '</div>';
                }
            
                echo '<button type="submit" class="btn">Confirm Address</button>';

            } else {
                echo '<div class="no-address-message"><p>No addresses found. Please add one.</p></div>';
            }
            ?>
        </form>
    <?php } ?>
</div>

<!-- Edit Address Section -->
<?php if (isset($address)) { ?>
    <div class="edit-address-container">
        <div class="edit-box-container">
            <h3>Edit Address</h3>
            <form id="update-form" action="address.php" method="post">
                <input type="hidden" name="address_id" value="<?php echo $address['address_id']; ?>">

                <label for="contact_num">Phone Number:</label>
                <input type="text" name="contact_num" value="<?php echo htmlspecialchars($address['contact_num']); ?>" class="edit-box" required placeholder="Enter phone number">

                <label for="address_line">Address Line:</label>
                <input type="text" name="address_line" value="<?php echo htmlspecialchars($address['address_line']); ?>" class="edit-box" required placeholder="Enter address line">

                <label for="barangay">Barangay:</label>
                <input type="text" name="barangay" value="<?php echo htmlspecialchars($address['barangay']); ?>" class="edit-box" required placeholder="Enter barangay">

                <label for="city">City:</label>
                <input type="text" name="city" value="<?php echo htmlspecialchars($address['city']); ?>" class="edit-box" required placeholder="Enter city">

                <label for="state">State:</label>
                <input type="text" name="state" value="<?php echo htmlspecialchars($address['state']); ?>" class="edit-box" required placeholder="Enter state">

                <label for="postal_code">Postal Code:</label>
                <input type="text" name="postal_code" value="<?php echo htmlspecialchars($address['postal_code']); ?>" class="edit-box" required placeholder="Enter postal code">

                <label for="country">Country:</label>
                <input type="text" name="country" value="<?php echo htmlspecialchars($address['country']); ?>" class="edit-box" required placeholder="Enter country">

                <input type="submit" value="Update Address" name="update_address" class="btn">
                <input type="button" value="Cancel" id="close-update" class="option-btn" onclick="window.location.href='address.php';">

            </form>
        </div>
    </div>

    <script>
        document.getElementById('close-update').addEventListener('click', function() {
            document.querySelector('.edit-address-container').style.display = 'none';
        });
    </script>
<?php } ?>

<?php include 'footer.php'; ?>
<script src="js/script.js"></script>
</body>
</html>
