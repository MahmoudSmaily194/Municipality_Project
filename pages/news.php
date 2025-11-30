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
    <link rel="stylesheet" href="/Municipality/css/news.css?v=4">
  </head>
  <body>
    <div class="news_page_con">
     <div class="news_page_title"> <h1>Latest News</h1></div>
      <div class="news_page">
        <div class="news_page_header">
          <select>
            <option value="">Date</option>
            <option value="">Today</option>
            <option value="">YesterDay</option>
            <option value="">Last Week</option>
          </select>
          <div class="news_page_search_con">
            <input type="text" placeholder="Search" /><img
              src="/Municipality/images/magnifying-glass.svg"
              alt=""
            />
          </div>
         
        </div>
         <div class="news">
          <?php foreach ($newsList as $news): ?>
            <div class="newsItem">
              <div class="newsItem_header">
                <h3><?= htmlspecialchars($news['title']) ?></h3>
                <p><?= htmlspecialchars($news['description']) ?></p>
                <div>
                <button>Veiw Details</button></div>
              </div>
               <img src="<?= !empty($news['image_url']) ? htmlspecialchars($news['image_url']) : '/Municipality/images/empty.jpg' ?>" alt="Event Image">
            </div>
             <?php endforeach; ?>
          </div>
      </div>
    </div>
  </body>
</html>
