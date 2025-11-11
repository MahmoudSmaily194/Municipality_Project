<?php
$host = 'localhost';
$db   = 'municipality';
$user = 'root';
$pass = '';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
    throw new \PDOException($e->getMessage(), (int)$e->getCode());
}
// function registerUser($pdo, $first_name, $last_name, $email, $password, $phone_number = null, $role = 'user', $profile_photo = null) {
//     // Validate email
//     if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
//         return ['success' => false, 'message' => 'Invalid email address.'];
//     }

//     // Check if email already exists
//     $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
//     $stmt->execute([$email]);
//     if ($stmt->fetch()) {
//         return ['success' => false, 'message' => 'Email already registered.'];
//     }

//     // Generate UUID for user ID
//     $id = bin2hex(random_bytes(16)); // 32 characters, could also use a library for UUID

//     // Hash the password
//     $password_hash = password_hash($password, PASSWORD_DEFAULT);

//     // Insert user into database
//     $stmt = $pdo->prepare("
//         INSERT INTO users (id, first_name, last_name, email, password_hash, phone_number, role, profile_photo)
//         VALUES (?, ?, ?, ?, ?, ?, ?, ?)
//     ");

//     try {
//         $stmt->execute([$id, $first_name, $last_name, $email, $password_hash, $phone_number, $role, $profile_photo]);
//         return ['success' => true, 'message' => 'User registered successfully.'];
//     } catch (Exception $e) {
//         return ['success' => false, 'message' => 'Error: ' . $e->getMessage()];
//     }
// }
// // Example usage
// $result = registerUser($pdo, 'John', 'Doe', 'john@example.com', 'password123', '1234567890');
// if ($result['success']) {
//     echo $result['message'];
// } else {
//     echo "Registration failed: " . $result['message'];
// }

?>