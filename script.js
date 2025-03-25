// Select DOM elements
const nextBtn = document.querySelector(".next");
const prevBtn = document.querySelector(".prev");
const carousel = document.querySelector(".carousel");
const list = document.querySelector(".list");
const items = Array.from(document.querySelectorAll(".item"));
const runningTimeBar = document.querySelector(".carousel .timeRunning");

// Timing configurations
const TIME_RUNNING = 500; // Animation duration for the transition
const TIME_AUTO_NEXT = 3000; // Auto-slide duration

// Initialize timeout variables
let transitionTimeout;
let autoNextTimeout;

// Create and append the progress bar
const arrowsDiv = document.querySelector(".arrows");
const progressBarContainer = document.createElement("div");
progressBarContainer.className = "progress-bar-container";

const progressBar = document.createElement("div");
progressBar.className = "progress-bar";

progressBarContainer.appendChild(progressBar);
arrowsDiv.appendChild(progressBarContainer);

// Event listeners for navigation buttons
nextBtn.addEventListener("click", () => handleSliderNavigation("next"));
prevBtn.addEventListener("click", () => handleSliderNavigation("prev"));

// Add attribute to each item
items.forEach((item, index) => {
  item.querySelector(".title").setAttribute("data-item", index + 1);
});

// Automatically navigate to the next slide
autoNextTimeout = setTimeout(() => {
  nextBtn.click();
}, TIME_AUTO_NEXT);

// Start the initial running time animation and progress bar
// resetAnimation();
afterSlideChange();

// // Resets the running time animation
// function resetAnimation() {
//   runningTimeBar.style.animation = "none"; // Remove current animation
//   runningTimeBar.offsetHeight; // Trigger reflow to restart animation
//   runningTimeBar.style.animation = `runningTime ${
//     TIME_AUTO_NEXT / 1000
//   }s linear forwards`; // Restart animation
// }

// Handles slider navigation (next/prev)
function handleSliderNavigation(direction) {
  const sliderItems = list.querySelectorAll(".item"); // Get all current items in the list

  if (direction === "next") {
    list.appendChild(sliderItems[0]); // Move the first item to the end of the list
    carousel.classList.add("next"); // Add the "next" class for transition
  } else if (direction === "prev") {
    list.prepend(sliderItems[sliderItems.length - 1]); // Move the last item to the start of the list
    carousel.classList.add("prev"); // Add the "prev" class for transition
  }

  afterSlideChange(); // Log the active slide index
}

// Logs the current active slide's original index
function afterSlideChange() {
  const slideNumberElement = document.querySelector(".slide-number");
  if (slideNumberElement) slideNumberElement.remove();

  const sliderItems = Array.from(list.querySelectorAll(".item")); // Get the current visible order of items
  const activeItem = parseInt(
    sliderItems[1].querySelector(".title").getAttribute("data-item")
  ); // The first visible item is the active one

  const activeIndex =
    activeItem < 10 ? `0${activeItem}` : activeItem.toString();

  const div = document.createElement("div");
  div.classList.add("slide-number");
  div.textContent = `${activeIndex}/${sliderItems.length}`;

  arrowsDiv.appendChild(div);

  // console.log(`Current active slide original index: ${activeIndex}`);

  updateProgressBar();
  resetCarouselState();
}

// Updates the progress bar based on the active slide index
function updateProgressBar() {
  const totalSlides = items.length;

  const sliderItems = Array.from(list.querySelectorAll(".item")); // Get the current visible order of items
  const activeItem =
    parseInt(sliderItems[0].querySelector(".title").getAttribute("data-item")) +
    1; // The first visible item is the active one

  const progressPercentage = (activeItem / totalSlides) * 100; // Calculate progress percentage
  progressBar.style.width = `${progressPercentage}%`; // Update the progress bar's width
}

// Resets the carousel state after navigation
function resetCarouselState() {
  // Clear existing timeouts for transitions and auto-slide
  clearTimeout(transitionTimeout);
  clearTimeout(autoNextTimeout);

  // Remove the transition class after the animation duration
  transitionTimeout = setTimeout(() => {
    carousel.classList.remove("next");
    carousel.classList.remove("prev");
  }, TIME_RUNNING);

  // Restart the auto-slide timer
  autoNextTimeout = setTimeout(() => {
    nextBtn.click();
  }, TIME_AUTO_NEXT);

  // Reset the running time bar animation
  // resetAnimation();
}

// FAQ Accordion

// Select all question buttons
const faqQuestions = document.querySelectorAll(".faq-question");

// Loop through each question button
faqQuestions.forEach((question) => {
  // Add a click event listener to each question
  question.addEventListener("click", () => {
    // Close any other open answers except the one clicked
    faqQuestions.forEach((item) => {
      if (item !== question) {
        item.classList.remove("active"); // Remove 'active' class to reset arrow rotation
        item.nextElementSibling.style.maxHeight = null; // Collapse the answer
      }
    });

    // Toggle 'active' class on the clicked question to rotate the arrow
    question.classList.toggle("active");

    // Select the corresponding answer div
    const answer = question.nextElementSibling;

    // Check if the answer is already open
    if (answer.style.maxHeight) {
      // If open, close it by resetting max-height
      answer.style.maxHeight = null;
    } else {
      // If closed, set max-height to scrollHeight to expand it
      answer.style.maxHeight = answer.scrollHeight + "px";
    }
  });
});

// smooth scrolling
// Select all links with hashes
$('a[href*="#"]')
  // Remove links that don't actually link to anything
  .not('[href="#"]')
  .not('[href="#0"]')
  .click(function (event) {
    // On-page links
    if (
      location.pathname.replace(/^\//, "") ==
        this.pathname.replace(/^\//, "") &&
      location.hostname == this.hostname
    ) {
      // Figure out element to scroll to
      var target = $(this.hash);
      target = target.length ? target : $("[name=" + this.hash.slice(1) + "]");
      // Does a scroll target exist?
      if (target.length) {
        // Only prevent default if animation is actually gonna happen
        event.preventDefault();
        $("html, body").animate(
          {
            scrollTop: target.offset().top,
          },
          1000,
          function () {
            // Callback after animation
            // Must change focus!
            var $target = $(target);
            $target.focus();
            if ($target.is(":focus")) {
              // Checking if the target was focused
              return false;
            } else {
              $target.attr("tabindex", "-1"); // Adding tabindex for elements not focusable
              $target.focus(); // Set focus again
            }
          }
        );
      }
    }
  });

// Back to top
//Make sure the user has scrolled at least double the height of the browser
var toggleHeight = $(window).outerHeight() * 2;

$(window).scroll(function () {
  if ($(window).scrollTop() > toggleHeight) {
    //Adds active class to make button visible
    $(".m-backtotop").addClass("active");

    //Just some cool text changes
    $("h1 span").text("TA-DA! Now hover it and hit dat!");
  } else {
    //Removes active class to make button visible
    $(".m-backtotop").removeClass("active");

    //Just some cool text changes
    $("h1 span").text("(start scrolling)");
  }
});

//Scrolls the user to the top of the page again
$(".m-backtotop").click(function () {
  $("html, body").animate({ scrollTop: 0 }, "slow");
  return false;
});
