<?php
require_once '/xampp/htdocs/Municipality/backend/config/db.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function uuidv4() {
    $data = random_bytes(16);
    $data[6] = chr((ord($data[6]) & 0x0f) | 0x40);
    $data[8] = chr((ord($data[8]) & 0x3f) | 0x80);
    return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Basic fields
    $title = trim($_POST['title']);
    $description = trim($_POST['description']);
    $category_id = $_POST['category_id'] ?? null;
    $status = $_POST['active'] ?? 'inActive';
    $created_by = $_SESSION['user_id'] ?? null;

    // Required files array (JSON)
    $requiredFiles = json_decode($_POST['required_files'] ?? '[]', true);

    // Upload image
    $image_url = null;
    if (!empty($_FILES['image']['name'])) {
        $uploadDir = '/xampp/htdocs/Municipality/uploads/';
        $fileName = uniqid() . '-' . basename($_FILES['image']['name']);
        $targetFile = $uploadDir . $fileName;

        if (move_uploaded_file($_FILES['image']['tmp_name'], $targetFile)) {
            $image_url = '/Municipality/uploads/' . $fileName;
        }
    }

    // Create permit ID
    $permit_id = uuidv4();

    // Convert title to slug
    $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $title)));

    // Insert main permit
    $stmt = $pdo->prepare("
        INSERT INTO permits (id, category_id, title, slug, description, image_url, status, created_by)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?)
    ");

    try {
        $stmt->execute([$permit_id, $category_id, $title, $slug, $description, $image_url, $status, $created_by]);
    } catch (PDOException $e) {
        die("Permit Insert Error: " . $e->getMessage());
    }

    // Insert required files
    $sql = $pdo->prepare("
        INSERT INTO permits_required_attachments (id, permit_id, document_name)
        VALUES (?, ?, ?)
    ");

    foreach ($requiredFiles as $file) {
        $file_id = $file['id'];
        $document_name = $file['name'];

        try {
            $sql->execute([$file_id, $permit_id, $document_name]);
        } catch (PDOException $e) {
            die("Required File Insert Error: " . $e->getMessage());
        }
    }

    // Redirect after everything is saved
    header("Location: /Municipality/admin.php?page=permits");
    exit;
}
?>
