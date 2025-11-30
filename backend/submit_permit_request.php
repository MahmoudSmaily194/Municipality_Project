<?php
session_start();
require_once '/xampp/htdocs/Municipality/backend/config/db.php';
 
function uuidv4() {
    $data = random_bytes(16);
    $data[6] = chr((ord($data[6]) & 0x0f) | 0x40);
    $data[8] = chr((ord($data[8]) & 0x3f) | 0x80);
    return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
}

// -------------------------
// 1. Security: User must be logged in
// -------------------------
if (!isset($_SESSION['user_id'])) {
    die("You must be logged in to submit a permit request.");
}

$user_id = $_SESSION['user_id'];

// -------------------------
// 2. Validate required fields
// -------------------------
if (!isset($_POST['permit_id']) || empty($_POST['permit_id'])) {
    die("Invalid permit ID.");
}

$permit_id = $_POST['permit_id'];
$request_description = $_POST['request_description'] ?? '';


// -------------------------
// 3. Start transaction
// -------------------------
try {
    $pdo->beginTransaction();

    // -----------------------------
    // 4. Insert into permits_requests
    // -----------------------------
    $request_id = uuidv4();

    $stmt = $pdo->prepare("
        INSERT INTO permits_requests (id, permit_id, user_id, priority, status)
        VALUES (?, ?, ?, 1, 'pending')
    ");

    $stmt->execute([
        $request_id,
        $permit_id,
        $user_id
    ]);

    // -----------------------------
    // 5. Insert into history table
    // -----------------------------
    $history_id = uuidv4();

    $stmt2 = $pdo->prepare("
        INSERT INTO permits_requests_history (id, permit_request_id, updated_by, status)
        VALUES (?, ?, ?, 'pending')
    ");

    $stmt2->execute([
        $history_id,
        $request_id,
        $user_id
    ]);

    // -----------------------------
    // 6. Handle file uploads
    // -----------------------------
    $uploadDir = "/Municipality/uploads/";
    $folderPath = $_SERVER['DOCUMENT_ROOT'] . $uploadDir;

    if (!is_dir($folderPath)) {
        mkdir($folderPath, 0777, true);
    }

    foreach ($_FILES as $key => $file) {
        if ($file['error'] === UPLOAD_ERR_NO_FILE) {
            continue;
        }

        if ($file['error'] !== UPLOAD_ERR_OK) {
            throw new Exception("Error uploading file: " . $file['error']);
        }

        $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
        $newName = uuidv4() . "." . $ext;
        $uploadPath = $folderPath . $newName;

        if (!move_uploaded_file($file['tmp_name'], $uploadPath)) {
            throw new Exception("Failed to move uploaded file.");
        }

        $fileUrl = $uploadDir . $newName;

        // Save in permits_requests_attachments table
        $attachment_id = uuidv4();
        $stmt3 = $pdo->prepare("
            INSERT INTO permits_requests_attachments (id, permit_request_id, file_url)
            VALUES (?, ?, ?)
        ");

        $stmt3->execute([
            $attachment_id,
            $request_id,
            $fileUrl
        ]);
    }

    // -----------------------------
    // 7. Commit transaction
    // -----------------------------
    $pdo->commit();

    // Redirect back with success
    header("Location: /Municipality/index.php?page=permits");
    exit;

} catch (Exception $e) {
    // Rollback on ANY error
    $pdo->rollBack();
    die("Error submitting permit request: " . $e->getMessage());
}
?>
