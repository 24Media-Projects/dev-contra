const globalHeader = document.querySelector(".global__header");
const globalHeaderLogo = document.querySelector(".global__header__logo");

// Define an object with event handlers
const eventHandlers = {
  burgerMenu: () => {
    document.body.classList.toggle("has-open-menu");
  },
};

// Event listener on window
window.addEventListener("click", (event) => {
  let target = event.target;

  // Check if the target is an SVG or use element
  if (
    target.tagName === "svg" ||
    target.tagName === "use" ||
    target.tagName === "rect" ||
    target.tagName === "SPAN"
  ) {
    // Find the parent button element using closest
    if (target.closest("button")) {
      target = target.closest("button");
    } else if (target.closest(".footer_menu__button")) {
      target = target.closest(".footer_menu__button");
    }
  }

  // Check if the clicked element has a corresponding event handler
  if (target.matches(".global__header__toggle_side_menu")) {
    eventHandlers.burgerMenu();
  }
});
