<?php
require_once '/xampp/htdocs/Municipality/backend/config/db.php'; // PDO connection

// Example user ID (replace with dynamic value)
$userId =$_SESSION['user_id'] ;

try {
    // Prepare the SQL query with a placeholder
    $stmt = $pdo->prepare("
        SELECT first_name, last_name, email, profile_photo 
        FROM users 
        WHERE id = :id
    ");

    // Bind the ID parameter
    $stmt->bindParam(':id', $userId, PDO::PARAM_STR);

    // Execute the query
    $stmt->execute();

    // Fetch the single user
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Navbar Test</title>
    <link rel="stylesheet" href="/Municipality/css/navbar.css?v=4" />
    <link rel="stylesheet" href="/Municipality/css/style.css" />
  </head>
  <body>
    <div class="navbar_con"></div>
    <div class="dropdown-wrapper" onclick="hideDropdown()">
      <div class="dropdown-box">
        <!-- User Info Section -->
        <div class="user-info">
          <div
            class="avatar"
            style="
              background-image: url(<?= $user['profile_photo']? $user['profile_photo']:"/Municipality/images/userImg.png" ?>);
            "
          ></div>
          <div class="user-details">
            <p class="user-name"><?=  $user['first_name']." ".$user['last_name'] ?></p>
            <p class="user-email"><?= $user['email'] ?></p>
          </div>
        </div>

        <!-- Menu Items -->
        <div class="menu">
          <a href="/Municipality/index.php?page=profile" class="menu-item">
            <img src="/Municipality/images/user.svg" />
            <p>Profile</p>
          </a>
          <a href="/Municipality/index.php?page=settings" class="menu-item">
            <img src="/Municipality/images/gear.svg" />
            <p>Settings</p>
          </a>
        </div>

        <hr class="divider" />

        <!-- Logout -->
        <a href="/Municipality/admin/logout.php" class="menu-item">
          <img src="/Municipality/images/right-from-bracket.svg" />
          <p>Logout</p>
        </a>
      </div>
    </div>
    <script>
      const navbarContainer = document.querySelector(".navbar_con");

      const navbar = `
        <div class="logo_con">
        <img src="/Municipality/images/icon.png" alt="logo" />
        <h2>Lebanon Municipality  </h2>
        </div>
        <img class="menuIcon" src="/Municipality/images/bars.svg" alt="menu icon" />
        <nav class="navbar">
        <a href="index.php?page=home">Home</a>
        <a href="index.php?page=permits">Permits</a>
        <a href="index.php?page=news">News</a>
        <a href="index.php?page=events">Events</a>
        <a href="index.php?page=complaints">Public Complaints</a>
        <a href="index.php?page=contact">Contact</a>
        <a href="index.php?page=permits_requests">Permits Requests</a>
        <img onclick="triggerDropdown()" class="sett" src="/Municipality/uploads/mahmoud.jpg"/>
        </nav>
      `;

      const sideBar = `
        <div class="logo_con">
        <img src="/Municipality/images/icon.png" alt="logo" />
        <h2>Lebanon Municipality  </h2>
        </div>
        <img class="menuIcon" src="/Municipality/images/bars.svg" alt="menu icon" />
        <div class="side_navbar_con" id="close">
          <nav class="side_navbar">
            <div class="active"><img src="/Municipality/images/house.svg" /><a href="index.php?page=home">Home</a></div>
            <div><img src="/Municipality/images/landmark.svg" /><a href="index.php?page=about">Services</a></div>
            <div><img src="/Municipality/images/house.svg" /><a href="index.php?page=news">News</a></div>
            <div><img src="/Municipality/images/house.svg" /><a href="index.php?page=events">Events</a></div>
            <div><img src="/Municipality/images/house.svg" /><a href="index.php?page=complaints">Public Complaints</a></div>
            <div><img src="/Municipality/images/house.svg" /><a href="index.php?page=contact">Contact</a></div>
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

      function triggerDropdown() {
        const dropDownWrapper = document.querySelector(".dropdown-wrapper");
        dropDownWrapper.classList.add("triggerDropDown");
      }
      function hideDropdown() {
        const dropDownWrapper = document.querySelector(".dropdown-wrapper");
        dropDownWrapper.classList.remove("triggerDropDown");
      }

      // Run once on load
      renderNavbar();

      // Run again on resize
      window.addEventListener("resize", renderNavbar);
    </script>
  </body>
</html>
