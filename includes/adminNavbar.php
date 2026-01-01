<?php
$current_page = $_GET['page'] ?? 'dashboard';
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Admin Panel</title>
  <link rel="stylesheet" href="/Municipality/css/adminNavbar.css?v=4">
  <link rel="stylesheet" href="/Municipality/css/style.css?v=2">
</head>
<body>
  <div class="admin_sideBar_con">
    <div class="admin_sideBar">
      <div class="admin_logo_con">
        <img src="/Municipality/images/icon.png" alt="logo" />
        <h2>Smart Municipality</h2>
      </div>
      <nav class="admin_side_navbar">

        <div class="<?= $current_page == 'dashboard' ? 'active' : '' ?>">
          <img src="/Municipality/images/houseAdmin.svg" alt="">
          <a href="admin.php?page=dashboard">Dashboard</a>
        </div>

        <div class="<?= $current_page == 'news' ? 'active' : '' ?>">
          <img src="/Municipality/images/landmark.svg" alt="">
          <a href="admin.php?page=news">News</a>
        </div>

        <div class="<?= $current_page == 'events' ? 'active' : '' ?>">
          <img src="/Municipality/images/calendar.svg" alt="">
          <a href="admin.php?page=events">Events</a>
        </div>

        <div class="<?= $current_page == 'permits' ? 'active' : '' ?>">
          <img src="/Municipality/images/book.svg" alt="">
          <a href="admin.php?page=permits">Permits</a>
        </div>
        <div class="<?= $current_page == 'permits_requests' ? 'active' : '' ?>">
          <img src="/Municipality/images/book.svg" alt="">
          <a href="admin.php?page=permits_requests">Permits Requests</a>
        </div>
        <div class="<?= $current_page == 'complaints' ? 'active' : '' ?>">
          <img src="/Municipality/images/flag.svg" alt="">
          <a href="admin.php?page=complaints">Complaints</a>
        </div>

        <div class="<?= $current_page == 'settings' ? 'active' : '' ?>">
          <img src="/Municipality/images/gear.svg" alt="">
          <a href="admin.php?page=settings">Settings</a>
        </div>

        <div class="<?= $current_page == 'logout' ? 'active' : '' ?>">
          <img src="/Municipality/images/right-from-bracket.svg" alt="">
          <a href="/Municipality/admin/logout.php">Logout</a>
        </div>

      </nav>
    </div>
  </div>
</body>
</html>
