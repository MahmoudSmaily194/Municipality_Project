<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Navbar Test</title>
    <link rel="stylesheet" href="/Municipality_Project/css/navbar.css?v=9" />
    <link rel="stylesheet" href="/Municipality_Project/css/style.css" />
  </head>
  <body>
    <div class="navbar_con"></div>

    <script>
      const navbarContainer = document.querySelector(".navbar_con");

      const navbar = `
        <div class="logo_con">
        <img src="/Municipality_Project/images/icon.png" alt="logo" />
        <h2>Lebanon Municipality  </h2>
        </div>
        <img class="menuIcon" src="/Municipality_Project/images/bars.svg" alt="menu icon" />
        <nav class="navbar">
        <a href="index.php?page=home">Home</a>
        <a href="index.php?page=permits">Permits</a>
        <a href="index.php?page=news">News</a>
        <a href="index.php?page=events">Events</a>
        <a href="index.php?page=complaints">Public Complaints</a>
        <a href="index.php?page=contact">Contact</a>
        <div class="langBtn">AR</div>
        </nav>
      `;

      const sideBar = `
        <div class="logo_con">
        <img src="/Municipality_Project/images/icon.png" alt="logo" />
        <h2>Lebanon Municipality  </h2>
        </div>
        <img class="menuIcon" src="/Municipality_Project/images/bars.svg" alt="menu icon" />
        <div class="side_navbar_con" id="close">
          <nav class="side_navbar">
            <div class="active"><img src="/Municipality_Project/images/house.svg" /><a href="index.php?page=home">Home</a></div>
            <div><img src="/Municipality_Project/images/landmark.svg" /><a href="index.php?page=about">Services</a></div>
            <div><img src="/Municipality_Project/images/house.svg" /><a href="index.php?page=news">News</a></div>
            <div><img src="/Municipality_Project/images/house.svg" /><a href="index.php?page=events">Events</a></div>
            <div><img src="/Municipality_Project/images/house.svg" /><a href="index.php?page=complaints">Public Complaints</a></div>
            <div><img src="/Municipality_Project/images/house.svg" /><a href="index.php?page=contact">Contact</a></div>
          </nav>
        </div>
      `;

      function renderNavbar() {
        // Re-render correct version
        if (window.innerWidth >= 767) {
          navbarContainer.innerHTML = navbar;
        } else {
          navbarContainer.innerHTML = sideBar;
        }

        // After rendering, attach the event listener again
        const menuIcon = document.querySelector(".menuIcon");
        const mySideNavbar = document.querySelector(".side_navbar_con");

        if (menuIcon && mySideNavbar) {
          let open = false;
          menuIcon.addEventListener("click", () => {
            open = !open;
            mySideNavbar.id = open ? "open" : "close";
          });
        }
      }

      // Run once on load
      renderNavbar();

      // Run again on resize
      window.addEventListener("resize", renderNavbar);
    </script>
  </body>
</html>
