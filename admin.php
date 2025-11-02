<?php
session_start();

// Check if user is logged in
$loggedin = isset($_SESSION['user_id']);

// Redirect to login if not logged in
if (!$loggedin) {
    header("Location: /Municipality/admin/login.php");
    exit;
}
define('IS_ADMIN_PANEL', true);
// Determine which page to include
$page = isset($_GET['page']) ? $_GET['page'] : 'dashboard';
$allowed_pages = ['dashboard', 'news', 'events', 'permits', 'complaints', 'settings','addEventModel'];

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

    <div class="admin_page">
        <?php include "admin/$page.php"; ?>
    </div>
</div>

</body>
</html>
