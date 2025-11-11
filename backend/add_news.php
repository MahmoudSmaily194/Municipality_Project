<?php


require_once '/wamp64/www/Municipality/backend/config/db.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title']);
    $description = trim($_POST['description']);
    $visibility = (int)$_POST['visibility'];

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

    // Handle image upload
    $imagePath = null;
    if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
        $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
        $newName = uniqid('news_', true) . '.' . $ext;
        $uploadDir = '../uploads/news/';
        if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);
        $targetPath = $uploadDir . $newName;
        if (move_uploaded_file($_FILES['image']['tmp_name'], $targetPath)) {
            $imagePath = '/Municipality/uploads/news/' . $newName;
        }
    }

    // Insert into DB
    $stmt = $pdo->prepare("
        INSERT INTO news (id, title, slug, description, image_url, visibility)
        VALUES (?, ?, ?, ?, ?, ?)
    ");

    try {
        $stmt->execute([$id, $title, $slug, $description, $imagePath, $visibility]);
        header("Location: /Municipality/admin.php?page=news");
        exit;
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}