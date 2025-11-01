<?php
if (!defined('IS_ADMIN_PANEL')) {
    header('Location: /Municipality_Project/admin/login.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Settings</title>
<link rel="stylesheet" href="/Municipality_Project/css/settings.css">
</head>
<body>
<div class="admin_settings_page_con">
  <div class="admin_settings_page">
    <div class="settings_header">
      <h2>Admin Settings</h2>
    </div>

    <div class="adminInfo_card_con">
      <div class="adminInfo_card">
        <h3>Admin Information</h3>

        <div class="adminProfilePhoto">
          <div class="adminProfilePhoto_uploade_camera">
            <label for="file" title="Upload Photo">
              <i class="fa fa-camera adminProfilePhoto_cameraIcon"></i>
            </label>
            <input type="file" accept="image/*" hidden id="file" />
          </div>

          <img src="Admin.png" alt="Admin" />
        </div>

        <div>
          <div>
            <i class="fa fa-user"></i>
            <p>Username</p>
          </div>
          <p>John Doe</p>
        </div>

        <div>
          <div>
            <i class="fa fa-envelope"></i>
            <p>Email</p>
          </div>
          <p dir="rtl" class="admin_settings_page_email">...... johndoe@gm</p>
        </div>

        <div>
          <div>
            <i class="fa fa-shield"></i>
            <p>Role</p>
          </div>
          <p>Admin</p>
        </div>
      </div>
    </div>

    <div class="settings_theme_toggle_con">
      <div class="settings_theme_toggle">
        <div class="settings_theme_toggle_text">
          <h3>Appearance</h3>
          <p>Switch between light and dark mode</p>
        </div>

        <div class="theme_icon_con" title="Switch to dark mode">
          <i class="fa fa-moon theme_icon"></i>
        </div>
      </div>
    </div>

    <div class="settings_language_con">
      <div class="settings_language">
        <h2>Website Language</h2>

        <div>
          <input type="radio" id="english" name="language" value="en" checked />
          <label for="english">English</label>
        </div>

        <div>
          <input type="radio" id="arabic" name="language" value="ar" />
          <label for="arabic">Arabic</label>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Confirmation popup -->
<!-- <div class="confirm_overlay">
  <div class="confirm_box">
    <h3>Are you sure you want to upload this image?</h3>
    <div class="confirm_buttons">
      <button>Yes</button>
      <button>No</button>
    </div>
  </div>
</div> -->

  
</body>
</html>
