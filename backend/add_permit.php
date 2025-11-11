<?php
require_once '/wamp64/www/Municipality/backend/config/db.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title']);
    $description = trim($_POST['description']);

    // Generate UUID
    function uuidv4() {
        $data = random_bytes(16);
        $data[6] = chr((ord($data[6]) & 0x0f) | 0x40); // version 4
        $data[8] = chr((ord($data[8]) & 0x3f) | 0x80); // variant
        return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
    }
    $id = uuidv4();

    // Create slug from title
    $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $title)));

    // Insert into DB
    $stmt = $pdo->prepare("
        INSERT INTO events (id, title, slug, event_date, location, description)
        VALUES (?, ?, ?, ?, ?, ?)
    ");

    try {
        $stmt->execute([$id, $title, $slug, $date, $location, $description]);
        header("Location: /Municipality/admin.php?page=events");
        exit;
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>