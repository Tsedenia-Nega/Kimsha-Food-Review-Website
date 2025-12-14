const categoryLinks = document.querySelectorAll(".category-list a");
const categoryLayouts = document.querySelectorAll(
  ".image-description-layouts .image-description-layout"
);

categoryLinks.forEach((link) => {
  link.addEventListener("click", (event) => {
    event.preventDefault();
    const activeLink = document.querySelector(".category-list a.active");
    activeLink.classList.remove("active");
    event.target.classList.add("active");

    const selectedCategory = event.target.dataset.category;
    categoryLayouts.forEach((layout) => {
      if (
        layout.dataset.category === selectedCategory ||
        selectedCategory === "all"
      ) {
        layout.style.display = "flex";
      } else {
        layout.style.display = "none";
      }
    });
  });
});
