<?php
session_start();
require_once '/xampp/htdocs/Municipality/backend/config/db.php';

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $message = 'Invalid email address.';
    } 
    else {
        $stmt = $pdo->prepare("SELECT id, first_name, last_name, email, password_hash, role FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$user) {
                $message = 'Incorrect Email.';
            } elseif (!password_verify($password, $user['password_hash'])) {
                $message = 'Incorrect Password.';
            } else {
                // Login success: set session
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['first_name'] = $user['first_name'];
                $_SESSION['last_name'] = $user['last_name'];
                $_SESSION['email'] = $user['email'];
                $_SESSION['role'] = $user['role'];
                 if($_SESSION['role']=="citizen"){
                 header("Location: /Municipality/index.php?page=home");
                }
                 // Redirect to dashboard
                if($_SESSION['role']=="admin"){
                 header("Location: /Municipality/admin.php?page=dashboard");
                }
                
                exit;
        }
    }
}

if (isset($_SESSION['user_id'])) {
    if ($_SESSION['role'] === 'admin') {
        header("Location: /Municipality/admin.php?page=dashboard");
    } else {
        header("Location: /Municipality/index.php?page=home");
    }
    exit;
}
include '/xampp/htdocs/Municipality/includes/toast.php'; 
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="/Municipality/css/login.css" />
    <link rel="stylesheet" href="/Municipality/css/style.css" />
    <title>Smart Municipality</title>
    <link rel="stylesheet" href="/Municipality/css/material-symbols.css" />
    <link rel="stylesheet" href="/Municipality/css/toast.css?v=4">
    <script src="/Municipality/includes/toast.js"></script>
  </head>
  <body>
    <div class="main-container">
      <div class="container">
        <div class="forms-container">
          <div class="signin-signup">
            <form action="" method="post" class="sign-in-form">
            <h2 class="title">Sign in</h2>

            <?php if (!empty($message)) : ?>
                <p class="error-message"><?= htmlspecialchars($message) ?></p>
            <?php endif; ?>

            <div class="input-field">
                <span class="material-symbols-outlined">person</span>
                <input
                type="email"
                name="email"
                placeholder="Email"
                required
                />
            </div>

            <div class="input-field">
                <span class="material-symbols-outlined">lock</span>
                <input
                type="password"
                name="password"
                placeholder="Password"
                required
                />
            </div>

            <input type="submit" class="btn solid" />

            <button type="button" class="google-btn">
                <img
                src="/Municipality/images/google-color.svg"
                alt="Google"
                class="google-icon"
                />
                Continue with Google
            </button>
            </form>

            <form id="sign-up-form" class="sign-up-form">
                <h2 class="title">Sign up</h2>

                <div class="input-field">
                    <span class="material-symbols-outlined"> person </span>
                    <input type="text" name="first_name" placeholder="First name" required />
                </div>

                <div class="input-field">
                    <span class="material-symbols-outlined"> person </span>
                    <input type="text" name="last_name" placeholder="Last name" required />
                </div>

                <div class="input-field">
                    <span class="material-symbols-outlined"> mail </span>
                    <input type="email" name="email" placeholder="Email" required />
                </div>

                <div class="input-field">
                    <span class="material-symbols-outlined"> lock </span>
                    <input type="password" name="password_hash" placeholder="Password" required />
                </div>

                <div class="input-field">
                    <span class="material-symbols-outlined"> phone </span>
                    <input type="text" name="phone_number" placeholder="Phone number" required />
                </div>

                <input type="submit" class="btn" value="Sign up" />

                <button type="button" class="google-btn">
                    <img src="/Municipality/images/google-color.svg" alt="Google" class="google-icon" />
                    Continue with Google
                </button>
            </form>

          </div>
        </div>

        <div class="panels-container">
          <div class="panel left-panel">
            <div class="content">
              <h3>Smart Municipality</h3>
              <p>
                Welcome to the Smart Municipality portal. Log in to access
                services, submit reports, and stay informed.
              </p>
              <button class="btn transparent" id="sign-up-btn">Sign up</button>
            </div>
            <img src="/Municipality/images/LogIn.png" class="image" alt="" />
          </div>
          <div class="panel right-panel">
            <div class="content">
              <h3>Smart Municipality</h3>
              <p>
                Join the Smart Municipality platform today. Create your account
                to access services, submit reports, and stay connected.
              </p>
              <button class="btn transparent" id="sign-in-btn">Sign in</button>
            </div>
            <img src="/Municipality/images/signIn.png" class="image" alt="" />
          </div>
        </div>
      </div>
    </div>
    <script>
      const sign_in_btn = document.querySelector("#sign-in-btn");
      const sign_up_btn = document.querySelector("#sign-up-btn");
      const container = document.querySelector(".container");

      sign_up_btn.addEventListener("click", () => {
        container.classList.add("sign-up-mode");
      });

      sign_in_btn.addEventListener("click", () => {
        container.classList.remove("sign-up-mode");
      });
    </script>
    <script>
       const form = document.getElementById('sign-up-form');

        form.addEventListener('submit', async (e) => {
        e.preventDefault();

        // Reset previous errors
        form.querySelectorAll('input').forEach(input => {
            input.classList.remove("inputError"); // إعادة اللون الطبيعي
        });

        const formData = new FormData(form);

        try {
            const res = await fetch('/Municipality/backend/registerCitizen.php', {
            method: 'POST',
            body: formData
            });

            const data = await res.json();

            if (data.status === 'error') {
            // لو PHP رجع field، نلوّن الحقل
            if (data.field) {
                const field = form.querySelector(`[name="${data.field}"]`);
                if (field) {
                field.classList.add("inputError");
                }
            }
            openToast(data.message,"#fee2e2","#991b1b"); // عرض الأخطاء
            } 
            if (data.status === 'success') {   // ⚡ هنا استخدمنا data بدل response
            window.location.href = '/Municipality/index.php?page=home';
            }

        } catch (err) {
            console.error(err);
            openToast("Something went wrong. Please try again","#fee2e2","#991b1b"); // عرض الأخطاء
        }
        });


    </script>
  </body>
</html>

