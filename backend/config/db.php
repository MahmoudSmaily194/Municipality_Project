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
// function registerUser(
//     $pdo,
//     $first_name,
//     $last_name,
//     $email,
//     $password,
//     $phone_number = null,
//     $role = 'admin',
//     $profile_photo = null
// ) {
//     // Validate email
//     if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
//         return ['success' => false, 'message' => 'Invalid email address.'];
//     }

//     // Check valid role
//     $valid_roles = ['admin', 'citizen'];
//     if (!in_array($role, $valid_roles)) {
//         return ['success' => false, 'message' => 'Invalid role provided.'];
//     }

//     // Check if email already exists
//     $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
//     $stmt->execute([$email]);
//     if ($stmt->fetch()) {
//         return ['success' => false, 'message' => 'Email already registered.'];
//     }

//     // Generate UUID v4
//     $id = generateUUIDv4();

//     // Hash the password
//     $password_hash = password_hash($password, PASSWORD_DEFAULT);

//     // Insert user
//     $stmt = $pdo->prepare("
//         INSERT INTO users 
//         (id, first_name, last_name, email, password_hash, phone_number, role, profile_photo)
//         VALUES (?, ?, ?, ?, ?, ?, ?, ?)
//     ");

//     try {
//         $stmt->execute([
//             $id,
//             htmlspecialchars(trim($first_name)),
//             htmlspecialchars(trim($last_name)),
//             htmlspecialchars(trim($email)),
//             $password_hash,
//             $phone_number,
//             $role,
//             $profile_photo
//         ]);
//         return ['success' => true, 'message' => 'User registered successfully.'];
//     } catch (Exception $e) {
//         return ['success' => false, 'message' => 'Database error: ' . $e->getMessage()];
//     }
// }

// // Helper function to generate a proper UUID v4
// function generateUUIDv4() {
//     $data = random_bytes(16);
//     $data[6] = chr(ord($data[6]) & 0x0f | 0x40); // Version 4
//     $data[8] = chr(ord($data[8]) & 0x3f | 0x80); // Variant
//     return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
// }

// // Example usage
// $result = registerUser($pdo, 'John', 'Doe', 'john@example.com', 'password123', '1234567890');
// echo $result['message'];


?>