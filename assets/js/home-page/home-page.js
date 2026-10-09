/* Slider */

new Swiper(".slider .swiper", {
  // slidesPerView: 3,
  spaceBetween: 0,
  navigation: {
    nextEl: ".slider .swiper-navigation .swiper-button-next",
    prevEl: ".slider .swiper-navigation .swiper-button-prev",
  },
  pagination: {},
  watchOverflow: true,
  breakpoints: {
    320: {
      spaceBetween: 20,
      slidesPerView: 1,
    },
    768: {
      spaceBetween: 20,
      slidesPerView: 2,
    },
    992: {
      spaceBetween: 30,
      slidesPerView: 3,
    },
  },
});

/* FAQ (Accordion) */

var acc = document.getElementsByClassName("faq__question");
var i;

for (i = 0; i < acc.length; i++) {
  acc[i].addEventListener("click", function () {
    this.classList.toggle("active");
    var panel = this.nextElementSibling;
    if (panel.style.maxHeight) {
      panel.style.maxHeight = null;
    } else {
      panel.style.maxHeight = panel.scrollHeight + "px";
    }
  });
}