
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

$currentPage = $_GET['page'] ?? 'home';

?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <title>Smart Municipality</title>
    <link
      href="/Municipality/css/material-symbols.css"
      rel="stylesheet"
    />
    <link
      href="/Municipality/css/navbar.css?v=2"
      rel="stylesheet"
    />
     <link rel="stylesheet" href="/Municipality/css/style.css?v=2">
  </head>
  <body>
    <nav class="navbar">
      <div class="navbar-container">
        <!-- Logo -->
        <div class="navbar-logo">
          <div class="logo-icon">
            <span class="material-symbols-outlined">account_balance</span>
          </div>
          <div class="logo-text">
            <h2>Smart Municipality</h2>
            <span>CITIZEN PORTAL</span>
          </div>
        </div>

        <!-- Menu -->
        <ul class="navbar-menu">
        <li>
          <a href="index.php?page=home"
            class="<?= ($currentPage === 'home') ? 'active' : '' ?>">
            Home
          </a>
        </li>

        <li>
          <a href="index.php?page=permits"
            class="<?= ($currentPage === 'permits') ? 'active' : '' ?>">
            Permits
          </a>
        </li>

        <li>
          <a href="index.php?page=news"
            class="<?= ($currentPage === 'news') ? 'active' : '' ?>">
            News
          </a>
        </li>

        <li>
          <a href="index.php?page=events"
            class="<?= ($currentPage === 'events') ? 'active' : '' ?>">
            Events
          </a>
        </li>

        <li>
          <a href="index.php?page=complaints"
            class="<?= ($currentPage === 'complaints') ? 'active' : '' ?>">
            Public Complaints
          </a>
        </li>

        <li>
          <a href="index.php?page=contact"
            class="<?= ($currentPage === 'contact') ? 'active' : '' ?>">
            Contact
          </a>
        </li>
      </ul>

          <!-- Navigation Sidbar -->
          
      <nav class="nav custom-scrollbar">
        <a class="nav-item <?= ($currentPage === 'home') ? 'active_side' : '' ?>" href="index.php?page=home">
          <span class="material-symbols-outlined">home</span>
          <span>Home</span>
        </a>

        <a class="nav-item <?= ($currentPage === 'permits') ? 'active_side' : '' ?>" href="index.php?page=permits">
          <span class="material-symbols-outlined">description</span>
          <span>Permits & Licenses</span>
        </a>

        <a class="nav-item <?= ($currentPage === 'news') ? 'active_side' : '' ?>" href="index.php?page=news">
          <span class="material-symbols-outlined">newspaper</span>
          <span>News & Updates</span>
        </a>

        <a class="nav-item  <?= ($currentPage === 'events') ? 'active_side' : '' ?>" href="index.php?page=events">
          <span class="material-symbols-outlined">event</span>
          <span>Community Events</span>
        </a>

        <a class="nav-item  <?= ($currentPage === 'complaints') ? 'active_side' : '' ?>" href="index.php?page=complaints">
          <span class="material-symbols-outlined">campaign</span>
          <span>Public Complaints</span>
        </a>

        <a class="nav-item  <?= ($currentPage === 'contact') ? 'active_side' : '' ?>" href="index.php?page=contact">
          <span class="material-symbols-outlined">call</span>
          <span>Contact Us</span>
        </a>
      </nav>
        <!-- Right Side -->
        <div class="navbar-actions">
          <button class="notification-btn">
            <span class="material-symbols-outlined">notifications</span>
            <span class="dot"></span>
          </button>

          <div class="user-info">
            <div class="user-text">
              <strong><?= $user['first_name']." ".$user['last_name'] ?></strong>
              <small>Resident</small>
            </div>
            <img
            id="avatarImg"
            src="/Municipality/images/userImg.png"
              alt="User"
            />
          </div>
          <span class="material-symbols-outlined menu">
                menu
          </span>
        </div>
      </div>
    </nav>
    <div class="profile-dropdown-wrap">
      <div class="dropdown-container">
          <!-- User Info Section -->
          <div class="profile-info">
              <div class="avatar" style="background-image: url('/Municipality/images/userImg.png');"></div>
              <div class="user-text">
                  <p><?= $user['first_name']." ".$user['last_name'] ?></p>
                  <p><?= $user['email'] ?></p>
              </div>
          </div>

          <!-- Menu Items -->
          <a href="#" class="menu-item">
              <span>person</span>
              <p>Profile</p>
          </a>
          <a href="#" class="menu-item">
              <span>settings</span>
              <p>Settings</p>
          </a>
          <a href="#" class="menu-item">
              <span>description</span>
              <p>My Requests</p>
          </a>

          <!-- Divider -->
          <div class="divider"></div>

          <!-- Logout -->
          <a href="/Municipality/admin/logout.php" class="menu-item">
              <span>logout</span>
              <p>Logout</p>
          </a>
      </div>
  </div>
    <script>
      const nav =document.querySelector(".nav");
      const menu= document.querySelector(".menu");
      menu.addEventListener("click",()=>{
        if(nav.classList.contains("open_nav")){
         nav.classList.remove("open_nav");
        }
        else{
          nav.classList.add("open_nav");
        }
      })
    </script>
    <script>
      const profileDropdownWrapper=document.querySelector(".profile-dropdown-wrap");
      const avatar=document.getElementById("avatarImg");
      avatar.addEventListener("click",()=>{
        if(profileDropdownWrapper.classList.contains("open-dropdown")){
          profileDropdownWrapper.classList.remove("open-dropdown");
        }
        else{
          profileDropdownWrapper.classList.add("open-dropdown");
        }
      });
           profileDropdownWrapper.addEventListener("click",()=>{
        if(profileDropdownWrapper.classList.contains("open-dropdown")){
          profileDropdownWrapper.classList.remove("open-dropdown");
        }
      });
    </script>
  </body>
</html>
