<?php
// Get the requested page from URL
$page = isset($_GET['page']) ? $_GET['page'] : 'home';

// Security: only allow specific pages
$allowed_pages = ['home', 'permits','events','complaints', 'news','contact', 'products'];
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