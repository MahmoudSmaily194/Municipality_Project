<?php
require_once '/xampp/htdocs/Municipality/backend/config/db.php';

// Always return JSON for AJAX errors
header('Content-Type: application/json');

// Allow POST only
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    echo json_encode([
        'status' => 'error',
        'message' => 'Invalid request method.'
    ]);
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
    echo json_encode([
        'status' => 'error',
        'field' => 'first_name',
        'message' => 'First name and last name are required.'
    ]);
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode([
        'status' => 'error',
        'field' => 'email',
        'message' => 'Invalid email format.'
    ]);
    exit;
}

if (strlen($password) < 8) {
    echo json_encode([
        'status' => 'error',
        'field' => 'password_hash',
        'message' => 'Password must be at least 8 characters.'
    ]);
    exit;
}

if (!preg_match('/^[0-9+\-\s]{6,20}$/', $phoneNumber)) {
    echo json_encode([
        'status' => 'error',
        'field' => 'phone_number',
        'message' => 'Invalid phone number.'
    ]);
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

// 5️⃣ Insert citizen
$sql = "
    INSERT INTO users
    (id, first_name, last_name, email, password_hash, phone_number, role)
    VALUES
    (:id, :first_name, :last_name, :email, :password_hash, :phone_number, 'citizen')
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

    echo json_encode([
        'status' => 'success',
        'message' => 'User registered successfully.'
    ]);
    exit;
} catch (PDOException $e) {

    if ($e->getCode() == 23000) {
        echo json_encode([
            'status' => 'error',
            'field' => 'email',
            'message' => 'This email is already registered.'
        ]);
    } else {
           // Temporary debug
    echo json_encode([
        'status' => 'error',
        'message' => $e->getMessage(),
        'code' => $e->getCode()
    ]);
    exit;
    }
    exit;
}
