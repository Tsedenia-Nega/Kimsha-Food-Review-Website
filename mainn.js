document.addEventListener("DOMContentLoaded", function () {
  var contactLinks = document.querySelectorAll(".contact-link");
  var popupContainer = document.querySelector(".popup-container");
  var closeBtn = document.querySelector(".popup-header .close-btn");
  var submitBtn = document.querySelector(
    ".popup-card .popup-buttons .submit-btn"
  );
  var commentSuccess = document.querySelector(".comment-success");

  contactLinks.forEach(function (link) {
    link.addEventListener("click", function (event) {
      event.preventDefault();
      popupContainer.style.display = "flex";
    });
  });

  closeBtn.addEventListener("click", function () {
    popupContainer.style.display = "none";
    commentSuccess.style.display = "none";
  });

  submitBtn.addEventListener("click", function () {
    
    popupContainer.style.display = "none";
    commentSuccess.textContent = "Comment submitted";
    commentSuccess.style.display = "block";
    setTimeout(function () {
      commentSuccess.style.display = "none";
    }, 3000);
  });
});
