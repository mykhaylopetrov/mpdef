/**
 * Menu
 */
document.addEventListener('DOMContentLoaded', function () {
    const menu = document.querySelector('.header__menu');
    const toggle = document.querySelector('.burger-menu-btn');

    if (!menu || !toggle) {
        return;
    }

    toggle.addEventListener('click', function () {
        menu.classList.toggle('open');
        document.documentElement.classList.toggle('menu-open');
    });

    document.querySelectorAll('.header__menu .dropdown > a').forEach(function (link) {
        link.addEventListener('click', function (event) {
            if (window.innerWidth <= 991.98) {
                event.preventDefault();
                const dropdown = this.parentElement;
                const siblings = Array.from(dropdown.parentElement.children).filter(function (li) {
                    return li !== dropdown && li.classList.contains('dropdown');
                });

                siblings.forEach(function (li) {
                    li.classList.remove('active');
                    li.querySelectorAll('.dropdown.active').forEach(function (child) {
                        child.classList.remove('active');
                    });
                });

                if (dropdown.classList.contains('active')) {
                    dropdown.classList.remove('active');
                    dropdown.querySelectorAll('.dropdown.active').forEach(function (child) {
                        child.classList.remove('active');
                    });
                } else {
                    dropdown.classList.add('active');
                }
            }
        });
    });

    document.querySelectorAll('.header__menu a[href^="#"]').forEach(function (anchor) {
        anchor.addEventListener('click', function () {
            if (window.innerWidth <= 991.98) {
                menu.classList.remove('open');
                document.documentElement.classList.remove('menu-open');
            }
        });
    });

    window.addEventListener('scroll', function () {
        const header = document.querySelector('.header');
        if (header && window.scrollY > 70) {
            header.classList.add('header--active');
        } else if (header) {
            header.classList.remove('header--active');
        }
    });
});

/**
 * Scroll To Top Button
 */
document.addEventListener("scroll", handleScroll);
// get a reference to our predefined button
var scrollToTopBtn = document.querySelector(".scrollToTopBtn");

function handleScroll() {
  var scrollableHeight = document.documentElement.scrollHeight - document.documentElement.clientHeight;
  var GOLDEN_RATIO = 0.1;

  if ((document.documentElement.scrollTop / scrollableHeight ) > GOLDEN_RATIO) {
    //show button
    if(!scrollToTopBtn.classList.contains("showScrollBtn"))
    scrollToTopBtn.classList.add("showScrollBtn")
  } else {
    //hide button
    if(scrollToTopBtn.classList.contains("showScrollBtn"))
    scrollToTopBtn.classList.remove("showScrollBtn")
  }
}

scrollToTopBtn.addEventListener("click", scrollToTop);

function scrollToTop() {
  window.scrollTo({
    top: 0,
    behavior: "smooth"
  });
}

/**
 * Smooth Scroll for all links with hashes
 */
document.querySelectorAll('.menu a[href^="#"], .header__menu a[href^="#"]').forEach((anchor) => {
  anchor.addEventListener("click", function (e) {
    e.preventDefault();

    const targetID = this.getAttribute("href").substring(1);
    const targetElement = document.getElementById(targetID);

    if (targetElement) {
      const header = document.querySelector("header");
      const headerHeight = header ? header.offsetHeight : 0;

      const elementPosition = targetElement.getBoundingClientRect().top;
      const offsetPosition =
        elementPosition + window.pageYOffset - headerHeight;

      window.scrollTo({
        top: offsetPosition,
        behavior: "smooth",
      });
    }

    // Закриваємо мобільне меню після кліку по якорю (тільки на мобільних)
    if (window.innerWidth <= 991.98) {
      const menu = document.querySelector('.header__menu');
      if (menu && menu.classList.contains("open")) {
        menu.classList.remove("open");
      }
      if (document.documentElement.classList.contains("menu-open")) {
        document.documentElement.classList.remove("menu-open");
      }
      // У випадку, якщо ви використовуєте додаткові класи/стилі при відкритті меню,
      // видаліть їх тут (наприклад: body.classList.remove('no-scroll') якщо застосовано).
    }
  });
});

