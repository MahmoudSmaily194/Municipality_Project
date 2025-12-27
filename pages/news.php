<?php
  if (!defined('IS_LOGGEDIN')) {
      header('Location: /Municipality/admin/login.php');
      exit;
  }
  require_once '/xampp/htdocs/Municipality/backend/config/db.php';

try {
    $stmt = $pdo->query("SELECT id, title, created_at, visibility,description,image_url FROM news ORDER BY created_at DESC");
    $newsList = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Error fetching news: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Document</title>
    <link rel="stylesheet" href="/Municipality/css/news.css?v=3" />
    <link rel="stylesheet" href="/Municipality/css/style.css" />
    <link rel="stylesheet" href="/Municipality/css/material-symbols.css" />
  </head>
  <body>
    <main class="news_page_con">
      <div class="news_page">
        <div class="page-header">
          <h1>Municipal<span>ity News</span></h1>
          <p>
            Official announcements and public updates from the municipality.
          </p>
        </div>

        <div class="search-wrapper">
          <div class="search-box">
            <span class="material-symbols-outlined">search</span>
            <input type="text" placeholder="Search news..." />
          </div>
        </div>

        <div class="news-grid">
             <?php foreach ($newsList as $news): ?>
          <article class="news-card">
            <div
              class="news-image"
              style="background-image: url('<?= !empty($news['image_url']) ? htmlspecialchars($news['image_url']) : '/Municipality/images/empty.jpg' ?>')"
            ></div>
            <div class="news-body">
              <div class="news-meta">
                <span class="badge">Public</span>
                <span class="date">12/10/2023</span>
              </div>
              <h3><?= htmlspecialchars($news['title']) ?></h3>
              <p>
                <?= htmlspecialchars($news['description']) ?>
              </p>
              <a href="#" class="read-more"
                >Read more
                <span class="material-symbols-outlined">arrow_forward</span></a
              >
            </div>
          </article>
           <?php endforeach; ?>
        </div>
      </div>
    </main>
  </body>
</html>

