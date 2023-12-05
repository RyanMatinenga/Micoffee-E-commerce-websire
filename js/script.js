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

document.addEventListener("DOMContentLoaded", function() {
   var messageElement = document.querySelector(".message");
   messageElement.style.opacity = 1; // Show the message

   // Set a timeout to hide the message after 5 seconds
   setTimeout(function() {
       messageElement.style.opacity = 0; // Hide the message
   }, 4000);
});