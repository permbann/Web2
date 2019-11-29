
//Set the width of the sidebar to 250px and the left margin of the page content to 250px 
function openNav() {
    document.getElementById("mySidebar").style.width = "260px";
    document.getElementById("mySidebar").style.visibility = "visible"; 
    document.getElementsByClassName("openbtn").style.visibility = "hidden"; 
    document.getElementsByClassName("closebtn").style.visibility = "visible"; 

  }
  
//Set the width of the sidebar to 0 and the left margin of the page content to 0 
function closeNav() {
    document.getElementById("mySidebar").style.width = "0";
    document.getElementById("mySidebar").style.visibility = "hidden"; 
    document.getElementsByClassName("closebtn").style.visibility = "hidden"; 
    document.getElementsByClassName("openbtn").style.visibility = "visible"; 
  } 

//Get the button:
mybutton = document.getElementById("myBtn");
mybutton.style.visibility ="hidden";

// When the user scrolls down 50px from the top of the document, show the button
window.onscroll = function() {scrollFunction()};

function scrollFunction() {
if (document.body.scrollTop > 50 || document.documentElement.scrollTop > 50) {
  mybutton.style.visibility = "visible";
} else {
  mybutton.style.visibility = "hidden";
}
}

// When the user clicks on the button, scroll to the top of the document
function topFunction() {
document.body.scrollTop = 0; // For Safari
document.documentElement.scrollTop = 0; // For Chrome, Firefox, IE and Opera
}