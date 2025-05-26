let userBox = document.querySelector('.header .header-2 .user-box');

document.querySelector('#user-btn').onclick = () =>{
   userBox.classList.toggle('active');
   navbar.classList.remove('active');
}

let navbar = document.querySelector('.header .header-2 .navbar');

document.querySelector('#menu-btn').onclick = () =>{
   navbar.classList.toggle('active');
   userBox.classList.remove('active');
}

window.onscroll = () =>{
   userBox.classList.remove('active');
   navbar.classList.remove('active');

   if(window.scrollY > 60){
      document.querySelector('.header .header-2').classList.add('active');
   }else{
      document.querySelector('.header .header-2').classList.remove('active');
   }
}

function updateButtonState() {
   var selectedAddress = document.querySelector('input[name="selected_address_id"]:checked');
   var continueButton = document.getElementById('continue-address-btn');

   if (selectedAddress) {
       continueButton.disabled = false; // Enable button
       continueButton.style.backgroundColor = '#b13987'; // Change button color to red (clickable state)
   } else {
       continueButton.disabled = true; // Disable button
       continueButton.style.backgroundColor = '#4CAF50'; // Change button color to green (not clickable state)
   }
}
document.addEventListener("DOMContentLoaded", function () {
   const editButtons = document.querySelectorAll(".edit-address-btn");
   const closeButtons = document.querySelectorAll(".close-edit-box");

   // Show the specific edit box when clicking on an edit button
   editButtons.forEach(button => {
       button.addEventListener("click", function (e) {
           e.preventDefault();
           const addressId = this.getAttribute("data-id");

           // Hide all other edit boxes
           document.querySelectorAll(".edit-address-container").forEach(box => {
               box.style.display = "none";
           });

           // Show the specific edit box
           document.getElementById(`edit-box-${addressId}`).style.display = "block";
       });
   });

   // Close the edit box when clicking on the cancel button
   closeButtons.forEach(button => {
       button.addEventListener("click", function () {
           const addressId = this.getAttribute("data-id");
           document.getElementById(`edit-box-${addressId}`).style.display = "none";
       });
   });
});



