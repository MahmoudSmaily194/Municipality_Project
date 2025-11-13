<?php
if (!defined('IS_ADMIN_PANEL')) {
    header('Location: /Municipality/admin/login.php');
    exit;
}

require_once '/xampp/htdocs/Municipality/backend/config/db.php';

try {
    $stmt = $pdo->query("SELECT id, title, created_at, visibility FROM news ORDER BY created_at DESC");
    $newsList = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Error fetching news: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="/Municipality/css/admin_news.css?v=3">
</head>
<body>

<div class="news_dashboard_page_con">
  <div class="news_dashboard_page">
    <h1>News</h1>

    <div class="news_table_con">
      <div class="news_table_wrapper">
        <table class="news_table">
          <thead>
            <tr>
              <th>Title</th>
              <th>Date</th>
              <th>Visibility</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            <?php if (!empty($newsList)): ?>
              <?php foreach ($newsList as $news): ?>
                <tr>
                  <td style="color: black;"><?= htmlspecialchars($news['title']) ?></td>
                  <td><?= date('d/m/Y', strtotime($news['created_at'])) ?></td>
                  <td>
                    <button>
                      <?= $news['visibility'] == 1 ? 'Public' : 'Private' ?>
                    </button>
                  </td>
                  <td>
                    <div>
                      <p class="news_td_div_p" onclick="viewNews('<?= $news['id'] ?>')">View</p>
                      <p>|</p>  
                      <p class="news_td_delete_div_p" onclick="deleteNews('<?= $news['id'] ?>')">Delete</p>
                    </div>
                  </td>
                </tr>
              <?php endforeach; ?>
            <?php else: ?>
              <tr><td colspan="4">No news available.</td></tr>
            <?php endif; ?>
          </tbody>
        </table>
        <div style="height: 20px;"></div>
      </div>
    </div>
  </div>

  <!-- Add News Form -->
  <div class="AddNews_Con">
    <form class="AddNews" action="/Municipality/backend/add_news.php" method="post" enctype="multipart/form-data">
      <h1>Add News</h1>
      <input type="text" name="title" placeholder="Title" required maxlength="150" />
      <textarea placeholder="Description" name="description" required maxlength="1000"></textarea>

      <div class="visibility_con">
        <p>Visibility</p>
        <div class="form_control">
          <select name="visibility" required>
            <option value="0">Private</option>
            <option value="1">Public</option>
          </select>
        </div>
      </div>

      <div class="news_dashboard_uploadPhoto_con">
        <div class="news_upload_image" role="button" tabindex="0">
          <h3>Upload Image</h3>
          <p>Drag & drop an image here or click to select</p>
          <label for="file">Upload</label>
          <input name="image" type="file" accept="image/*" hidden id="file" />
        </div>
      </div>

      <button type="submit" class="publish_news">Publish</button>
    </form>
  </div>
</div>
</body>
</html>
