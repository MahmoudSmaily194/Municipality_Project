
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
    <title>Navbar</title>
    <link
      href="/Municipality/css/material-symbols.css"
      rel="stylesheet"
    />
    <link
      href="/Municipality/css/navbar.css"
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
            src="/Municipality/images/mahmoud.jpg"
              alt="User"
            />
          </div>
        </div>
      </div>
    </nav>
  </body>
</html>
