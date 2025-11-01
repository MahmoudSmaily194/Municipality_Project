<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Document</title>
    <link rel="stylesheet" href="/Municipality/css/home.css?v=8" />
  </head>
  <body>
    <div class="home">
      <div class="home_header">
        <h1>Municipality of Lebanon</h1>
        <p>Your community hub for information, services, and engagement.</p>
        <button>Explore Services</button>
      </div>
    </div>
    <div class="explore_town_sec">
      <h1>Explore Our Town</h1>
      <div class="carousel_container"></div>
      <div class="dots">
        <button class="carousel_arrow_btn" onclick="goToPrev()"><</button>
        <button class="dot" onclick="goToIndex(0)"></button>
        <button class="dot" onclick="goToIndex(1)"></button>
        <button class="dot" onclick="goToIndex(2)"></button>
        <button class="dot" onclick="goToIndex(3)"></button>
        <button class="dot" onclick="goToIndex(4)"></button>
        <button class="carousel_arrow_btn" onclick="goToNext()">></button>
      </div>
    </div>
    <div class="quick_access_con">
    <div class="quick_access_con_title"><h1>Quick Access</h1></div>
    <div class="quick_access">
      <div>
        <img src="/Municipality/images/payBell.png" alt="payBell" />
        <p>Pay Bills</p>
      </div>
      <div>
        <img src="/Municipality/images/permits.png" alt="permits" />
        <p>Apply for Permits</p>
      </div>

      <div>
        <img src="/Municipality/images/trash.png" alt="trash" />
        <p>Trash Schedule</p>
      </div>
      <div>
        <img src="/Municipality/images/report.png" alt="report" />
        <p>Report a Problem</p>
      </div>
    </div>
    </div>
    <script>
      const images = [
        "/Municipality/images/explore.jpg",
        "/Municipality/images/olive.jpg",
        "/Municipality/images/potato.jpg",
        "/Municipality/images/town.jpg",
        "/Municipality/images/colorsBack.jpg",
      ];
      function renderCarousel() {
        const carouselContainer = document.querySelector(".carousel_container");
        images.map((item, index) => {
          let className = "image";
          if (currentIndex == index) {
            className += " active";
          } else if (
            index ==
            (currentIndex - 1 + images.length) % images.length
          ) {
            className += " leftImage";
          } else if (index == (currentIndex + 1) % images.length) {
            className += " rightImage";
          } else if (
            index ==
            (currentIndex - 2 + images.length) % images.length
          ) {
            className += " farLeftImage";
          } else if (index == (currentIndex + 2) % images.length) {
            className += " farRightImage";
          }

          const div = document.createElement("div");
          div.className = className;
          div.style.backgroundImage = `url(${item})`;
          carouselContainer.appendChild(div);
        });
        const dots = document.querySelectorAll(".dot");
        dots.forEach((dot, i) => {
          dot.classList.toggle("active-dot", i === currentIndex);
        });
      }

      let currentIndex = 2;
      function goToIndex(index) {
        currentIndex = index;
        renderCarousel();
      }
      function goToPrev() {
        currentIndex =
          currentIndex == 0
            ? (currentIndex = images.length - 1)
            : currentIndex - 1;
        renderCarousel();
      }
      function goToNext() {
        currentIndex = (currentIndex + 1) % images.length;

        renderCarousel();
      }
      function startAutoSlide() {
        autoSlideInterval = setInterval(goToNext, 2000);
      }

      function stopAutoSlide() {
        clearInterval(autoSlideInterval);
      }

      function resetAutoSlide() {
        stopAutoSlide();
        startAutoSlide();
      }

      const carouselContainer = document.querySelector(".carousel_container");

      // Pause on hover
      carouselContainer.addEventListener("mouseenter", stopAutoSlide);
      carouselContainer.addEventListener("mouseleave", startAutoSlide);

      // Initialize
      renderCarousel();
      startAutoSlide();
    </script>
  </body>
</html>
