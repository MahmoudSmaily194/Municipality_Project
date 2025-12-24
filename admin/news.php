<?php
if (!defined('IS_ADMIN_PANEL')) {
    header('Location: /Municipality/admin/login.php');
    exit;
}

require_once '/xampp/htdocs/Municipality/backend/config/db.php';
 include '/xampp/htdocs/Municipality/includes/delete_modal.php';

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
    <link rel="stylesheet" href="/Municipality/css/admin_news.css?v=2">
     <link rel="stylesheet" href="/Municipality/css/deleteDialog.css?v=8">
    <script src="/Municipality/includes/delete_modal.js"></script>
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
                      <p class="news_td_delete_div_p"  onclick="openDeleteModal('/Municipality/backend/delete_newsItem.php', '<?= htmlspecialchars($news['id'], ENT_QUOTES) ?>')">Delete</p>
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
          <span class="remove-image" title="Remove image">✖</span>
          <h3>Upload Image</h3>
          <p>Drag & drop an image here or click to select</p>
          <label for="file">Upload</label>
          <input name="imageUpload" type="file" accept="image/*" hidden id="file" />
        </div>
      </div>

      <button type="submit" class="publish_news">Publish</button>
    </form>
  </div>
</div>
<script>
  const input = document.getElementById("file");
  const preview = document.querySelector(".news_upload_image");
  const removeBtn = document.querySelector(".remove-image");

  const title = preview.querySelector("h3");
  const text = preview.querySelector("p");
  const label = preview.querySelector("label");

  input.addEventListener("change", function () {
    const file = this.files[0];
    if (!file) return;

    const reader = new FileReader();

    reader.onload = function () {
      preview.style.backgroundImage = `url('${reader.result}')`;
      preview.style.backgroundSize = "cover";
      preview.style.backgroundPosition = "center";

      title.style.display = "none";
      text.style.display = "none";
      label.style.display = "none";

      removeBtn.style.display = "flex";
    };

    reader.readAsDataURL(file);
  });

  removeBtn.addEventListener("click", function (e) {
    e.stopPropagation(); // مهم

    preview.style.backgroundImage = "none";
    input.value = "";

    title.style.display = "block";
    text.style.display = "block";
    label.style.display = "inline-block";

    removeBtn.style.display = "none";
  });
</script>

</body>
</html>
