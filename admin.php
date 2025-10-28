<?php
$page = isset($_GET['page']) ? $_GET['page'] : 'dashboard';

$allowed_pages = ['dashboard', 'news', 'events', 'complaints', 'settings'];

if (!in_array($page, $allowed_pages)) {
    $page = 'dashboard';
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel</title>
   
      <link rel="stylesheet" href="/Municipality/css/admin.css">
</head>

<body>
    <div class="admin">
<div class="admin_side">
    <?php include 'includes/adminNavbar.php'; ?>
    </div>
<div class="admin_page" >
   <?php include "admin/$page.php"; ?>
</div>
</div>
  

</body>
</html>
