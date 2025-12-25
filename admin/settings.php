<?php
if (!defined('IS_ADMIN_PANEL')) {
    header('Location: /Municipality/admin/login.php');
    exit;
}

$errorMessage = $_SESSION['error'] ?? null;
$successMessage = $_SESSION['success'] ?? null;

// Clear after reading (flash messages)
unset($_SESSION['error'], $_SESSION['success']);


?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Settings</title>
<link rel="stylesheet" href="/Municipality/css/settings.css?v=9">
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
          <img src="/Municipality/images/userImg.png" alt="Admin" />
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
    <div class="register-container">
   <form 
     id="register-admin-form"
    action="/Municipality/backend/registerAdmin.php" 
    method="POST"
    class="register-form"
>
    <h2 class="form-title">Register Admin</h2>
<?php if ($errorMessage): ?>
    <p class="form-message error-message">
        <?= htmlspecialchars($errorMessage) ?>
    </p>
<?php endif; ?>

<?php if ($successMessage): ?>
    <p class="form-message success-message">
        <?= htmlspecialchars($successMessage) ?>
    </p>
<?php endif; ?>

    <div class="form-group">
        <label for="first_name">First Name</label>
        <input 
            type="text" 
            id="first_name" 
            name="first_name"
            class="form-input" 
            required
        >
    </div>

    <div class="form-group">
        <label for="last_name">Last Name</label>
        <input 
            type="text" 
            id="last_name" 
            name="last_name"
            class="form-input" 
            required
        >
    </div>

    <div class="form-group">
        <label for="email">Email</label>
        <input 
            type="email" 
            id="email" 
            name="email"
            class="form-input" 
            required
        >
    </div>

    <div class="form-group">
        <label for="password_hash">Password</label>
        <input 
            type="password" 
            id="password_hash" 
            name="password_hash"
            class="form-input" 
            required
        >
    </div>

    <div class="form-group">
        <label for="phone_number">Phone Number</label>
        <input 
            type="tel" 
            id="phone_number" 
            name="phone_number"
            class="form-input" 
            required
        >
    </div>

    <button type="submit" class="form-button">Register</button>
</form>

</div>
  </div>
</div>
</body>
</html>
