<?php

include 'config.php';

session_start();

$user_id = $_SESSION['user_id'];

if(!isset($user_id)){
   header('location:login.php');
}

$user_id = $_SESSION['user_id'];

if(isset($_POST['add_to_cart'])){
   $product_name = $_POST['product_name'];
   $product_price = $_POST['product_price'];
   $product_image = $_POST['product_image'];
   $product_quantity = $_POST['product_quantity'];

   $check_cart_numbers = mysqli_query($conn, "SELECT * FROM `cart` WHERE name = '$product_name' AND user_id = '$user_id'") or die('query failed');

   if(mysqli_num_rows($check_cart_numbers) > 0){
      $message[] = 'already added to cart!';
   }else{
      mysqli_query($conn, "INSERT INTO `cart`(user_id, name, price, quantity, image) VALUES('$user_id', '$product_name', '$product_price', '$product_quantity', '$product_image')") or die('query failed');
      $message[] = 'product added to cart!';
   }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Home</title>

   <!-- Font Awesome CDN -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

   <!-- Custom CSS -->
   <link rel="stylesheet" href="styles.css?v=1.0">

   <script>(function(w, d) { w.CollectId = "65da531a56c8a5b9944dfb5a"; var h = d.head || d.getElementsByTagName("head")[0]; var s = d.createElement("script"); s.setAttribute("type", "text/javascript"); s.async=true; s.setAttribute("src", "https://collectcdn.com/launcher.js"); h.appendChild(s); })(window, document);</script>

</head>
<body>
   
<?php include 'header.php'; ?>
<? include 'cookie_consent.php' ?>
<div class="home">
    <div class="slider">
        <div class="slide active">
            <img src="images/slide1.png" alt="Image 1">
        </div>
        <div class="slide">
            <img src="images/slide2.png" alt="Image 2">
        </div>
        <div class="slide">
            <img src="images/slide3.png" alt="Image 3">
        </div>
        <div class="slide">
            <img src="images/slide4.png" alt="Image 4">
        </div>
    </div>
    <div class="slider-nav">
        <div class="dot-container"></div>
    </div>
</div>

<script>
    let slideIndex = 0;
    const slides = document.querySelectorAll('.slide');
    const dotsContainer = document.querySelector('.dot-container');

    // Create dots
    slides.forEach((_, index) => {
        const dot = document.createElement('span');
        dot.classList.add('dot');
        dot.setAttribute('data-slide', index);
        dotsContainer.appendChild(dot);
    });

    const dots = document.querySelectorAll('.dot');
    showSlides();

    function showSlides() {
        slides.forEach((slide) => {
            slide.style.display = 'none';
        });
        dots.forEach(dot => dot.classList.remove('active'));
        slideIndex++;
        if (slideIndex > slides.length) {
            slideIndex = 1;
        }
        slides[slideIndex - 1].style.display = 'block';
        dots[slideIndex - 1].classList.add('active');
        setTimeout(showSlides, 2000); // Change image every 2 seconds
    }

    // Click event for dots navigation
    dotsContainer.addEventListener('click', (e) => {
        if (e.target.classList.contains('dot')) {
            const clickedDotIndex = parseInt(e.target.getAttribute('data-slide'));
            slideIndex = clickedDotIndex;
            showSlides();
        }
    });
</script>

<section class="voucher">

   <div class="flex">

      <div class="image">
         <img src="images/slide_5.png" alt="">
      </div>

   </div>
   
</section>

<section class="products">

   <h1 class="title">TAKE A WOOF AT THESE PAWSOME PET SUPPLIES</h1>

   <div class="box-container">

      <?php  
         $select_products = mysqli_query($conn, "SELECT * FROM `products` LIMIT 6") or die('query failed');
         if(mysqli_num_rows($select_products) > 0){
            while($fetch_products = mysqli_fetch_assoc($select_products)){
      ?>
     <form action="" method="post" class="box" style="height: 100%;">
      <img class="image" src="images/<?php echo $fetch_products['image']; ?>" alt="">
      <div class="name"><?php echo $fetch_products['name']; ?></div>
      <div class="price">₱<?php echo $fetch_products['price']; ?></div>
      <input type="hidden" min="1" name="product_quantity" value="1" class="qty">
      <input type="hidden" name="product_name" value="<?php echo $fetch_products['name']; ?>">
      <input type="hidden" name="product_price" value="<?php echo $fetch_products['price']; ?>">
      <input type="hidden" name="product_image" value="<?php echo $fetch_products['image']; ?>">
      <input type="submit" value="add to cart" name="add_to_cart" class="btn">
     </form>
      <?php
         }
      }else{
         echo '<p class="empty">no products added yet!</p>';
      }
      ?>
   </div>

   <div class="load-more" style="margin-top: 1rem; text-align:center">
      <a href="shop.php" class="option-btn">Load More</a>
   </div>

</section>

<section class="about">

   <div class="flex">

      <div class="image">
         <img src="images/about-us.png" alt="">
      </div>

      <div class="content">
         <h3>About Us</h3>
         <p>Lorem ipsum dolor, sit amet consectetur adipisicing elit. Impedit quos enim minima ipsa dicta officia corporis ratione saepe sed adipisci?</p>
         <a href="about.php" class="btn">Read More</a>
      </div>

   </div>

</section>
<section class="authentic">
<div class="image">
   <img src="images/authentic.png" alt="">
</div>

<section class="home-contact">
   <div class="content">
      <h3>Have any questions?</h3>
      <p>Lorem ipsum, dolor sit amet consectetur adipisicing elit. Atque cumque exercitationem repellendus, amet ullam voluptatibus?</p>
      <a href="contact.php" class="white-btn">Contact Us</a>
   </div>

</section>

<?php include 'footer.php'; ?>

<!-- custom js file link  -->
<script src="js/script.js"></script>

</body>
</html>
