<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once '/xampp/htdocs/Municipality/backend/config/db.php'; 

// Clear old messages
unset($_SESSION['success'], $_SESSION['error']);

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    $_SESSION['error'] = "Invalid request method.";
    header("Location: /Municipality/admin.php?page=settings");
    exit;
}

// 1️⃣ Get inputs
$firstName   = trim($_POST['first_name'] ?? '');
$lastName    = trim($_POST['last_name'] ?? '');
$email       = trim($_POST['email'] ?? '');
$password    = $_POST['password_hash'] ?? '';
$phoneNumber = trim($_POST['phone_number'] ?? '');

// 2️⃣ Validation
if (empty($firstName) || empty($lastName)) {
    $_SESSION['error'] = "First name and last name are required.";
    header("Location: /Municipality/admin.php?page=settings");
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $_SESSION['error'] = "Invalid email format.";
    header("Location: /Municipality/admin.php?page=settings");
    exit;
}

if (strlen($password) < 8) {
    $_SESSION['error'] = "Password must be at least 8 characters.";
    header("Location: /Municipality/admin.php?page=settings");
    exit;
}

if (!preg_match('/^[0-9+\-\s]{6,20}$/', $phoneNumber)) {
    $_SESSION['error'] = "Invalid phone number.";
    header("Location: /Municipality/admin.php?page=settings");
    exit;
}

// 3️⃣ Hash password
$passwordHash = password_hash($password, PASSWORD_BCRYPT);

// 4️⃣ Generate UUID (CHAR 36)
$uuid = bin2hex(random_bytes(16));
$uuid = substr($uuid, 0, 8) . '-' .
        substr($uuid, 8, 4) . '-' .
        substr($uuid, 12, 4) . '-' .
        substr($uuid, 16, 4) . '-' .
        substr($uuid, 20);

// 5️⃣ Insert admin
$sql = "
    INSERT INTO users
    (id, first_name, last_name, email, password_hash, phone_number, role)
    VALUES
    (:id, :first_name, :last_name, :email, :password_hash, :phone_number, 'admin')
";

try {
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':id'            => $uuid,
        ':first_name'    => $firstName,
        ':last_name'     => $lastName,
        ':email'         => $email,
        ':password_hash' => $passwordHash,
        ':phone_number'  => $phoneNumber
    ]);

    $_SESSION['success'] = "Admin registered successfully.";

} catch (PDOException $e) {

    // Duplicate email
    if ($e->getCode() == 23000) {
        $_SESSION['error'] = "This email is already registered.";
    } else {
        $_SESSION['error'] = "Something went wrong. Please try again.";
    }
}

// 6️⃣ Redirect back
header("Location: /Municipality/admin.php?page=settings#register-admin-form");
exit;
