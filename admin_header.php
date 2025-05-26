<?php
if(isset($message)){
   foreach($message as $message){
      echo '
      <div class="message">
         <span>'.$message.'</span>
         <i class="fas fa-times" onclick="this.parentElement.remove();"></i>
      </div>
      ';
   }
}
?>

<header class="header">
   <div class="flex" style="height: 65px; padding-right:50px;">
      <a href="admin_page.php" class="logo">Admin Panel</a>

      <div class="icons">
         <div id="menu-btn" class="fas fa-bars"></div>
         <div id="user-btn" class="fas fa-user"></div>
      </div>

      <div class="account-box">
         <p>Name : <span style="color:#b13987;"><?php echo $_SESSION['admin_name']; ?></span></p>
         <p>Email : <span style="color:#b13987;"><?php echo $_SESSION['admin_email']; ?></span></p>
         <a href="logout.php" class="delete-btn">logout</a>
      </div>
   </div>
</header>

      <div class="admin-nav">
         <div class="logo-container">
            <img src="images/logoo.png." alt="Logo" class="sidebar-logo">
         </div>
         <ul>
            <li><a href="admin_page.php">Dashboard</a></li>
            <li><a href="admin_products.php">Products</a></li>
            <li><a href="admin_orders.php">Orders</a></li>
            <li><a href="admin_users.php">Users</a></li>
            <li><a href="admin_contacts.php">Messages</a></li>
         </ul>
      </div>
   </div>