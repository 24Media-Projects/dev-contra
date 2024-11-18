const globalHeader = document.querySelector(".global_header");
const sideNavContainer = document.querySelector(".side_nav_container");
const sideNavContainerInner = document.querySelector(
  ".side_nav_container__inner"
);
const sideNavContainerForm = document.querySelector(".side_nav_container form");
const latestNewsContainer = document.querySelector(".latest_news_header");
const stickyHpPrimaryMenu = document.querySelector(
  ".global_header.global_header_hp .global_header__menu"
);
const offset = globalHeader.offsetTop;
const expandTagDescrption = document.querySelector(".expand-description");
const sideNavComputedStyle = window.getComputedStyle(sideNavContainer);
const forms = document.querySelectorAll(".search_form");

// Define an object with event handlers
const eventHandlers = {
  burgerMenu: () => {
    if (
      !sideNavContainer.classList.contains("active") &&
      !sideNavContainer.classList.contains("close_nav")
    ) {
      sideNavContainer.classList.add("active");
    } else {
      sideNavContainer.classList.toggle("active");
      sideNavContainer.classList.toggle("close_nav");
    }
    globalHeader.classList.toggle("active");
    document.body.classList.toggle("nav_is_open");

    if (latestNewsContainer.classList.contains("active")) {
      latestNewsContainer.classList.remove("active");
      latestNewsContainer.classList.add("close_nav");
      globalHeader.classList.remove("menuRoi_active");
      document.body.classList.remove("menuRoi_is_open");
    }
  },
  closeBurgerMenu: () => {
    sideNavContainer.classList.remove("active");
    sideNavContainer.classList.add("close_nav");
    globalHeader.classList.toggle("active");
    document.body.classList.remove("nav_is_open");
  },
  sideNavDropdown: (item) => {
    if (item.parentElement.classList.contains("menu-item-has-children")) {
      item.parentElement.classList.toggle("open-sub-menu");
    } else if (
      item.parentElement.parentElement.classList.contains("global_footer__col")
    ) {
      item.parentElement.parentElement.classList.toggle("open-sub-menu");
    }
  },
  scrollToTop: () => {
    window.scrollTo({
      top: 0,
      behavior: "smooth",
    });
  },
  expandDescription: () => {
    expandTagDescrption.parentElement.classList.toggle("expand");
  },
  latestNews: () => {
    if (
      !latestNewsContainer.classList.contains("active") &&
      !latestNewsContainer.classList.contains("close_nav")
    ) {
      latestNewsContainer.classList.add("active");
    } else {
      latestNewsContainer.classList.toggle("active");
      latestNewsContainer.classList.toggle("close_nav");
    }
    globalHeader.classList.toggle("menuRoi_active");
    document.body.classList.toggle("menuRoi_is_open");

    if (sideNavContainer.classList.contains("active")) {
      sideNavContainer.classList.remove("active");
      sideNavContainer.classList.add("close_nav");
      globalHeader.classList.remove("active");
      document.body.classList.remove("nav_is_open");
    }
  },
};

// Event listener on window
window.addEventListener("click", (event) => {
  let target = event.target;

  // Check if the target is an SVG or use element
  if (
    target.tagName === "svg" ||
    target.tagName === "use" ||
    target.tagName === "rect"
  ) {
    // Find the parent button element using closest
    if (target.closest("button")) {
      target = target.closest("button");
    }
  }

  // Check if the clicked element has a corresponding event handler
  if (
    target.matches(".toggle_side_menu") ||
    target.matches(".toggle_primary_menu")
  ) {
    eventHandlers.burgerMenu();
  } else if (target.matches(".toggle_latest_posts")) {
    eventHandlers.latestNews();
  } else if (target.matches(".menu-item__toggle-children")) {
    eventHandlers.sideNavDropdown(target);
  } else if (target.matches(".scroll-to-top--btn")) {
    eventHandlers.scrollToTop();
  } else if (target.matches(".expand-description")) {
    eventHandlers.expandDescription();
  } else if (
    target.matches(".close_side_menu") ||
    (sideNavComputedStyle.visibility === "visible" &&
      !sideNavContainerInner.contains(target) &&
      !sideNavContainerForm.contains(target))
  ) {
    eventHandlers.closeBurgerMenu();
  }
});

window.addEventListener("scroll", function () {
  if (document.body.classList.contains("home") && window.innerWidth > 1023) {
    if (window.scrollY >= 300) {
      globalHeader.classList.add("fixed");
      document.body.classList.add("menu_is_stuck");
    } else {
      globalHeader.classList.remove("fixed");
      document.body.classList.remove("menu_is_stuck");
    }
  } else {
    globalHeader.classList.remove("fixed");
    document.body.classList.remove("menu_is_stuck");
  }
});

//Search form

forms.forEach(function (form) {
  var inputField = form.querySelector(".searchInput");
  var clearButton = form.querySelector(".clear--btn");

  if (inputField.value.trim() !== "") {
    clearButton.style.display = "block";
  } else {
    clearButton.style.display = "none";
  }

  // Add an event listener to the input field for each form
  inputField.addEventListener("input", function () {
    if (inputField.value.trim() !== "") {
      clearButton.style.display = "block";
    } else {
      clearButton.style.display = "none";
    }
  });

  // Add an event listener to the clear button to clear the input field for each form
  clearButton.addEventListener("click", function () {
    inputField.value = "";
    clearButton.style.display = "none";
  });
});

if (
  document.querySelector(
    ".magazine_home_wrapper .most_popular_section .most_popular"
  )
) {
  document
    .querySelectorAll(".most_popular_section .most_popular .post__title")
    .forEach(($element) => {
      $element.addEventListener("mouseenter", function (event) {
        document
          .querySelector(".most_popular.active")
          .classList.remove("active");
        this.closest(".most_popular").classList.add("active");
      });
    });
}

if (document.querySelector(".home_magazine_section")) {
  document.querySelectorAll(".hover_title_magazine").forEach(($element) => {
    $element.addEventListener("mouseenter", function (event) {
      let $container = this.closest(".article_container");

      $container
        .querySelector(".hover_title_magazine.active")
        .classList.remove("active");

      this.classList.add("active");
    });
  });
}

// Embed code homepage group

window.addEventListener("DOMContentLoaded", () => {
  const embed_code_group = document.querySelector(".embed_codes_section");
  const embed_code_group_container_divs = document.querySelectorAll(
    ".embed_codes_section .container div"
  );

  if (embed_code_group) {
    const optionsList = document.querySelector(".dropdown-options");
    const listItems = optionsList.getElementsByTagName("li");
    const optionsListComputedStyle = window.getComputedStyle(optionsList);
    let selectedOptionIndex = 0;
    let selectedOption = listItems[0].innerHTML; // Default selected option
    window.toggleOptions = function () {
      if (optionsListComputedStyle.display === "block") {
        embed_code_group.classList.remove("open_dropdown");
      } else {
        embed_code_group.classList.add("open_dropdown");
        updateOptionsList();
      }
    };

    window.selectOption = function (option, index) {
      selectedOptionIndex = index;
      selectedOption = option.textContent;
      document.querySelector(".selected-option").textContent = selectedOption;
      embed_code_group.classList.remove("open_dropdown");
      showDivs(selectedOptionIndex);
    };

    function updateOptionsList() {
      for (let i = 0; i < listItems.length; i++) {
        if (listItems[i].textContent === selectedOption) {
          listItems[i].style.display = "none";
        } else {
          listItems[i].style.display = "block";
        }
      }
    }

    function showDivs(selectedOptionIndex) {
      embed_code_group_container_divs.forEach((div) => {
        if (
          parseInt(div.getAttribute("data-index"), 10) === selectedOptionIndex
        ) {
          div.classList.add("active");
        } else {
          div.classList.remove("active");
        }
      });
    }
  }
});
