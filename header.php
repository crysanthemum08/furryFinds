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
   <div class="header-2">
      <div class="flex" style="height: 65px; padding-right:50px;">
      <a href="home.php" class="logo" style="display: flex; flex-direction: row; margin-left: 50px; align-items: center; font-size: 3rem; height:64px"><img src="images/logo.png" alt="Furry Finds Logo">
      </a>
      
      <nav class="navbar">
   <section class="search-form">
      <form action="search_page.php" method="post">
         <input type="text" name="search" placeholder="Search products..." class="box">
         <button type="submit" name="submit" class="btn">
            <i class="fas fa-search"></i>
         </button>
      </form>
   </section>
</nav>


         <div class="icons">
            <div id="menu-btn" class="fas fa-bars"></div>
            <a href="search_page.php" class="fas fa-search"></a>
            <div id="user-btn" class="fas fa-user"></div>
            <?php
               $select_cart_number = mysqli_query($conn, "SELECT * FROM `cart` WHERE user_id = '$user_id'") or die('query failed');
               $cart_rows_number = mysqli_num_rows($select_cart_number); 
            ?>
            <a href="orders.php" class="fas fa-clipboard-check"></a> 
            <a href="cart.php"> <i class="fas fa-shopping-cart"></i> <span>(<?php echo $cart_rows_number; ?>)</span> </a>
         </div>

         <div class="user-box">
            <?php if(isset($_SESSION['user_name']) && isset($_SESSION['user_email'])): ?>
               <p>Name : <span style="font-size: 2rem;"><?php echo $_SESSION['user_name']; ?></span></p>
               <p>Email : <span style="font-size: 2rem;"><?php echo $_SESSION['user_email']; ?></span></p>
               <a href="logout.php" class="delete-btn">Logout</a>
            <?php endif; ?>
         </div>
      </div>
   </div>

</header>

</header>
<header class="header">
   <div class="header-1">
      <div class="flex" style="height: 70px; display:flex; justify-content: center; align-items: center;">
         <nav class="navbar" style="font-size: 20px;">
            <a href="categories.php?category=Dog" class="nav-link">Dog</a>
            <a href="categories.php?category=Cat" class="nav-link">Cat</a>
            <a href="categories.php?category=Bird" class="nav-link">Bird</a>
            <a href="categories.php?category=Small Pet" class="nav-link">Small Pet</a>
            <a href="categories.php?category=Healthcare" class="nav-link">Healthcare</a>
            <a href="categories.php?category=Hygiene" class="nav-link">Hygiene</a>
            <a href="categories.php?category=Pet Supplies" class="nav-link">Pet Supplies</a>
            <a href="categories.php?category=Pet Accessories" class="nav-link">Pet Accessories</a>
         </nav>
      </div>
   </div>
</header>