const stars = document.querySelectorAll(".rating-stars i");
const ratingInput = document.querySelector(
  '.rating-container input[name="rating"]'
);
const submitBtn = document.querySelector(".submit-btn");
const ratingCount = document.querySelector(".rating-count");

stars.forEach((star, index) => {
  star.addEventListener("click", () => {
    ratingInput.value = index + 1;

    for (let i = 0; i < stars.length; i++) {
      if (i <= index) {
        stars[i].classList.add("active");
      } else {
        stars[i].classList.remove("active");
      }
    }
  });
});

submitBtn.addEventListener("click", () => {
  const rating = parseInt(ratingInput.value);
});
