let userBox = document.querySelector('.header .account-box');
let navbar = document.querySelector('.header .navbar');
let header2 = document.querySelector('.header .header-2');

document.querySelector('#user-btn').onclick = () => {
   console.log("User button clicked"); // Check if this message is logged
   if (userBox) userBox.classList.toggle('active');
   if (navbar) navbar.classList.remove('active');
};

document.querySelector('#menu-btn').onclick = () => {
   console.log("Menu button clicked"); // Check if this message is logged
   if (navbar) navbar.classList.toggle('active');
   if (userBox) userBox.classList.remove('active');
};

window.onscroll = () => {
   if (userBox) userBox.classList.remove('active');
   if (navbar) navbar.classList.remove('active');

   if (header2) {
      if (window.scrollY > 60) {
         header2.classList.add('active');
      } else {
         header2.classList.remove('active');
      }
   }
};
