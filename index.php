<?php
session_start();

// Check if user is logged in
$loggedin = isset($_SESSION['user_id']);

// Redirect to login if not logged in
if (!$loggedin) {
    header("Location: /Municipality/admin/login.php");
    exit;
}
define('IS_LOGGEDIN', true);

// Get the requested page from URL
$page = isset($_GET['page']) ? $_GET['page'] : 'home';

// Security: only allow specific pages
$allowed_pages = ['home', 'permits','events','complaints', 'news','contact', 'report','permits_requests','citizan_permitRequest_details','applyPermit'];
if (!in_array($page, $allowed_pages)) {
    $page = 'home';
}

// Include navbar

include 'includes/navbar.php';

// Include the requested page
include "pages/$page.php";

// Include footer
include 'includes/footer.php';
?>