<?php
  if (!defined('IS_LOGGEDIN')) {
      header('Location: /Municipality/admin/login.php');
      exit;
  }
require_once '/xampp/htdocs/Municipality/backend/config/db.php';
try {
    $stmt = $pdo->query("
        SELECT p.id, p.title, p.status,p.description,p.image_url, c.name AS category
        FROM permits p
        LEFT JOIN permits_categories c ON p.category_id = c.id
        ORDER BY p.created_at DESC
    ");
    $permits = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $permits = [];
   
    echo "Error: " . $e->getMessage();
}
 ?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Document</title>
    <link rel="stylesheet" href="/Municipality/css/permits.css?v=3" />
    <link rel="stylesheet" href="/Municipality/css/material-symbols.css" />
    <link rel="stylesheet" href="/Municipality/css/style.css" />
  </head>
  <body>
    <main class="permits_page_con">
      <div class="permits_page">
        <!-- HEADER -->
        <section class="permits-header">
          <div class="header-text">
            <h1>
              Digital Permits &
              <span>Licenses</span>
            </h1>
            <p>
              Apply for official building, business, and event permits online.
              Fast, transparent, and paperless.
            </p>
          </div>

          <a href="index.php?page=permits_requests" class="my-permits-btn">
            <span class="material-symbols-outlined">history_edu</span>
            My Permit Requests
          </a>
        </section>

        <!-- SEARCH + FILTER -->
        <section class="filters">
          <div class="search-box">
            <span class="material-symbols-outlined">search</span>
            <input
              type="text"
              placeholder="Search permits (e.g. Construction)"
            />
          </div>

          <div class="select-box">
            <span class="material-symbols-outlined">filter_list</span>
            <select>
              <option>All Categories</option>
              <option>Building & Construction</option>
              <option>Business & Trade</option>
              <option>Events & Public Spaces</option>
              <option>Infrastructure</option>
            </select>
          </div>

          <button class="search-btn">Find Permit</button>
        </section>

        <!-- PERMITS GRID -->
        <section class="permits-grid">
          <!-- CARD -->
         <?php foreach ($permits as $permit): ?>
          <article class="permit-card">
            <div
              class="card-image"
              style="
                background-image: url('<?= !empty($permit['image_url']) ? htmlspecialchars($permit['image_url']) : '/Municipality/images/empty.jpg' ?>');
              "
            >
              <h3> <?= htmlspecialchars($permit['title']) ?></h3>
            </div>
            <div class="card-body">
              <p>
                <?= htmlspecialchars($permit['description']) ?>
              </p>
              <div class="card-actions">
                <button class="primary" ><a href="/Municipality/index.php?page=applyPermit&id=<?php echo $permit['id'] ?>">Apply Now</a></button>
                <button class="secondary">Details</button>
              </div>
            </div>
          </article>
        <?php endforeach; ?>
        </section>
      </div>
    </main>
  </body>
</html>
