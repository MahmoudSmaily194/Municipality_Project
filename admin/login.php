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
            $message = 'Incorrect Email or Password.';
        } elseif (!password_verify($password, $user['password_hash'])) {
            $message = 'Incorrect Email or Password.';
        } else {
            // Login success: set session
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['first_name'] = $user['first_name'];
            $_SESSION['last_name'] = $user['last_name'];
            $_SESSION['email'] = $user['email'];
            $_SESSION['role'] = $user['role'];

            // Redirect to dashboard
            header("Location: /Municipality/admin.php?page=dashboard");
            exit;
        }
    }
}

// If user is already logged in, redirect to dashboard
if (isset($_SESSION['user_id'])) {
    header("Location: /Municipality/admin.php?page=dashboard");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Municipality Login</title>
<link rel="stylesheet" href="/Municipality/css/login.css">
</head>
<body>
<div class="admin_login_page">
    <div class="admin_login_form_con">
        <div class="admin_login_header">
            <img src="/Municipality/images/icon.png" alt="Logo" />
            <h1>Municipality</h1>
            <h2>Login</h2>
        </div>

        <?php if ($message): ?>
            <div style="color: red; margin-bottom: 1rem; text-align:center;">
                <?php echo htmlspecialchars($message); ?>
            </div>
        <?php endif; ?>

        <form method="post">
            <div>
                <label for="email">Email</label>
                <input id="email" name="email" type="text" required maxlength="100" value="<?php echo isset($email) ? htmlspecialchars($email) : ''; ?>" />
            </div>
            <div>
                <label for="password">Password</label>
                <input id="password" name="password" type="password" required maxlength="100" />
            </div>
            <a href="#">Forgot password?</a>
            <button type="submit">Login</button>
        </form>
    </div>
</div>
</body>
</html>
