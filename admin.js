// Get the link that opens the popup
var contactLink = document.getElementsByClassName("contact-link")[0];

// Get the popup
var contactPopup = document.getElementsByClassName("contact-popup")[0];

// Get the close button
var closeButton = document.getElementsByClassName("close-button")[0];

// When the user clicks the link, open the popup
contactLink.onclick = function(event) {
  event.preventDefault(); // Prevent the default link behavior
  contactPopup.style.display = "block";
}

// When the user clicks on the close button, close the popup
closeButton.onclick = function() {
  contactPopup.style.display = "none";
}

// When the user clicks anywhere outside of the popup, close it
window.onclick = function(event) {
  if (event.target == contactPopup) {
    contactPopup.style.display = "none";
  }
}