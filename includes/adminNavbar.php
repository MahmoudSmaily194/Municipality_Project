<?php
$current_page = $_GET['page'] ?? 'dashboard';
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>admin navbar</title>
  <link rel="stylesheet" href="/Municipality_Project/css/adminNavbar.css?v=4">
  <link rel="stylesheet" href="/Municipality_Project/css/style.css?v=2">
</head>
<body>
  <div class="admin_sideBar_con">
    <div class="admin_sideBar">
      <div class="admin_logo_con">
        <img src="/Municipality_Project/images/icon.png" alt="logo" />
        <h2>Lebanon Municipality</h2>
      </div>
      <nav class="admin_side_navbar">

        <div class="<?= $current_page == 'dashboard' ? 'active' : '' ?>">
          <img src="/Municipality_Project/images/houseAdmin.svg" alt="">
          <a href="admin.php?page=dashboard">Dashboard</a>
        </div>

        <div class="<?= $current_page == 'news' ? 'active' : '' ?>">
          <img src="/Municipality_Project/images/landmark.svg" alt="">
          <a href="admin.php?page=news">News</a>
        </div>

        <div class="<?= $current_page == 'events' ? 'active' : '' ?>">
          <img src="/Municipality_Project/images/calendar.svg" alt="">
          <a href="admin.php?page=events">Events</a>
        </div>

        <div class="<?= $current_page == 'permits' ? 'active' : '' ?>">
          <img src="/Municipality_Project/images/book.svg" alt="">
          <a href="admin.php?page=permits">Permits</a>
        </div>

        <div class="<?= $current_page == 'complaints' ? 'active' : '' ?>">
          <img src="/Municipality_Project/images/flag.svg" alt="">
          <a href="admin.php?page=complaints">Complaints</a>
        </div>

        <div class="<?= $current_page == 'settings' ? 'active' : '' ?>">
          <img src="/Municipality_Project/images/gear.svg" alt="">
          <a href="admin.php?page=settings">Settings</a>
        </div>

        <div class="<?= $current_page == 'logout' ? 'active' : '' ?>">
          <img src="/Municipality_Project/images/right-from-bracket.svg" alt="">
          <a href="/Municipality_Project/admin/logout.php">Logout</a>
        </div>

      </nav>
    </div>
  </div>
</body>
</html>
