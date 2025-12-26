<?php
require_once '/xampp/htdocs/Municipality/backend/config/db.php';
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode([
        'success' => false,
        'message' => 'Invalid request method'
    ]);
    exit;
}

// ================== Validation ==================
$title = trim($_POST['title'] ?? '');
$description = trim($_POST['description'] ?? '');
$visibility = trim($_POST['visibility'] ?? ''); ;

if ($title === '' || $description === '') {
    echo json_encode([
        'success' => false,
        'message' => 'Title and description are required'
    ]);
    exit;
}

// ================== UUID ==================
function uuidv4() {
    $data = random_bytes(16);
    $data[6] = chr((ord($data[6]) & 0x0f) | 0x40);
    $data[8] = chr((ord($data[8]) & 0x3f) | 0x80);
    return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
}

$id = uuidv4();

// ================== Slug ==================
function createUniqueSlug($pdo, $title) {
    $baseSlug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $title)));
    $slug = $baseSlug;
    $i = 1;

    while (true) {
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM news WHERE slug = ?");
        $stmt->execute([$slug]);
        if ($stmt->fetchColumn() == 0) break;
        $slug = $baseSlug . '-' . $i++;
    }

    return $slug;
}

$slug = createUniqueSlug($pdo, $title);

// ================== Image Upload ==================
$imagePath = null;

if (!empty($_FILES['imageUpload']['name'])) {
    if ($_FILES['imageUpload']['error'] !== UPLOAD_ERR_OK) {
        echo json_encode([
            'success' => false,
            'message' => 'Image upload failed'
        ]);
        exit;
    }

    $fileExt = strtolower(pathinfo($_FILES['imageUpload']['name'], PATHINFO_EXTENSION));
    $allowedExts = ['jpg', 'jpeg', 'png', 'gif'];

    if (!in_array($fileExt, $allowedExts)) {
        echo json_encode([
            'success' => false,
            'message' => 'Invalid image type'
        ]);
        exit;
    }

    $uploadDir = '/xampp/htdocs/Municipality/uploads/';
    if (!file_exists($uploadDir)) mkdir($uploadDir, 0777, true);

    $newFileName = uniqid('news_', true) . '.' . $fileExt;
    $destPath = $uploadDir . $newFileName;

    if (!move_uploaded_file($_FILES['imageUpload']['tmp_name'], $destPath)) {
        echo json_encode([
            'success' => false,
            'message' => 'Failed to save image'
        ]);
        exit;
    }

    $imagePath = '/Municipality/uploads/' . $newFileName;
}else{
     echo json_encode([
        'success' => false,
        'message' => 'Image was not uploaded'
    ]);
    exit;
}

// ================== Insert ==================
try {
    $stmt = $pdo->prepare("
        INSERT INTO news (id, title, slug, description, image_url, visibility)
        VALUES (?, ?, ?, ?, ?, ?)
    ");

    $stmt->execute([$id, $title, $slug, $description, $imagePath, $visibility]);

    echo json_encode([
        'success' => true,
        'message' => 'News published successfully'
    ]);
    exit;

} catch (PDOException $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Database error'
    ]);
    exit;
}
