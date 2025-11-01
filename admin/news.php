<?php
if (!defined('IS_ADMIN_PANEL')) {
    header('Location: /Municipality/admin/login.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="/Municipality/css/admin_news.css?v=2">
</head>
<body><div class="news_dashboard_page_con">
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
            <!-- Example News Row -->
            <tr>
              <td style="color: black;">Example Title ...</td>
              <td>01/01/2025</td>
              <td>
                <button>Public</button>
              </td>
              <td>
                <div>
                  <p class="news_td_div_p">View</p>
                  <p>|</p>
                  <p class="news_td_delete_div_p">Delete</p>
                </div>
              </td>
            </tr>
            <!-- More rows dynamically generated -->
          </tbody>
        </table>
        <div style="height: 20px;"></div>
      </div>
    </div>
  </div>

  <div class="AddNews_Con">
    <div class="AddNews">
      <h1>Add News</h1>
      <input type="text" placeholder="Title" required maxlength="150" />
      <textarea placeholder="Description" required maxlength="1000"></textarea>

      <div class="visibility_con">
        <p>Visibility</p>
        <div class="form_control">
          <select required>
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
  <input type="file" accept="image/*" hidden id="file" />
</div>

      </div>

      <button class="publish_news">Publish</button>
    </div>
  </div>


</div>

</body>
</html>