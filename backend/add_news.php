<?php
require_once '/xampp/htdocs/Municipality/backend/config/db.php';

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

    function createUniqueSlug($pdo, $title) {
    $baseSlug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $title)));
    $slug = $baseSlug;
    $i = 1;

    while (true) {
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM news WHERE slug = ?");
        $stmt->execute([$slug]);
        if ($stmt->fetchColumn() == 0) {
            break; // unique
        }
        $slug = $baseSlug . '-' . $i;
        $i++;
    }

    return $slug;
    }

    // Create slug from title
  $slug = createUniqueSlug($pdo, $title);

    // Handle image upload
    $imagePath = null;
    if (isset($_FILES['imageUpload']) && $_FILES['imageUpload']['error'] === UPLOAD_ERR_OK) {
        $fileTmpPath = $_FILES['imageUpload']['tmp_name'];
        $fileName = $_FILES['imageUpload']['name'];
        $fileExt = pathinfo($fileName, PATHINFO_EXTENSION);
        $allowedExts = ['jpg', 'jpeg', 'png', 'gif'];

        if (in_array(strtolower($fileExt), $allowedExts)) {
            $newFileName = uniqid('news_', true) . '.' . $fileExt;
            $uploadDir = '/xampp/htdocs/Municipality/uploads/'; // make sure this folder exists and writable
            if (!file_exists($uploadDir)) mkdir($uploadDir, 0777, true);
            $destPath = $uploadDir . $newFileName;

            if (move_uploaded_file($fileTmpPath, $destPath)) {
                $imagePath = '/Municipality/uploads/' . $newFileName;
            } else {
                die('Failed to move uploaded file.');
            }
        } else {
            die('Invalid file type. Only JPG, JPEG, PNG, GIF allowed.');
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
        die("Database Error: " . $e->getMessage());
    }
}
?>
