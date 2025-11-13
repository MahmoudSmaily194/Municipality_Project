<?php
require_once '/xampp/htdocs/Municipality/backend/config/db.php';
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    function uuidv4() {
        $data = random_bytes(16);
        $data[6] = chr((ord($data[6]) & 0x0f) | 0x40); // version 4
        $data[8] = chr((ord($data[8]) & 0x3f) | 0x80); // variant
        return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title']);
    $description = trim($_POST['description']);
    $category_id = $_POST['category_id'] ?? null;
    $status = $_POST['active'] ?? 'inActive';
    $created_by = $_SESSION['user_id'] ?? null; // Assuming you store logged-in user ID in session
    $image_url = null;
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
    $uploadDir = '/xampp/htdocs/Municipality/uploads/';
    $fileName = uniqid() . '-' . basename($_FILES['image']['name']);
    $targetFile = $uploadDir . $fileName;
    if (move_uploaded_file($_FILES['image']['tmp_name'], $targetFile)) {
        $image_url = '/Municipality/uploads/' . $fileName;
    }
    }
    $id = uuidv4();
    $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $title)));

    $stmt = $pdo->prepare("
        INSERT INTO permits (id, category_id, title, slug, description,image_url, status, created_by)
        VALUES (?, ?, ?, ?, ?, ?, ?,?)
    ");

    try {
        $stmt->execute([$id, $category_id, $title, $slug, $description,$image_url, $status, $created_by]);
        header("Location: /Municipality/admin.php?page=permits");
        exit;
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>
