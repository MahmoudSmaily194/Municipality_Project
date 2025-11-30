<?php
session_start();
require_once '/xampp/htdocs/Municipality/backend/config/db.php';

// Admin protection
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    http_response_code(403);
    die("Unauthorized access");
}

// Validate POST
if (!isset($_POST['request_id']) || !isset($_POST['status'])) {
    die("Missing required fields");
}

$req_id  = $_POST['request_id'];
$status  = $_POST['status'];
$note    = $_POST['note'] ?? null;
$admin_id = $_SESSION['user_id'];

$file_url = null;

// -----------------------------
// 1) Handle File Upload (Optional)
// -----------------------------
if (isset($_FILES['attachment']) && $_FILES['attachment']['error'] === 0) {

    // Create directory if not exists
    $uploadDir = "/uploads/permits_history/";
    $absolutePath = $_SERVER['DOCUMENT_ROOT'] . $uploadDir;

    if (!is_dir($absolutePath)) {
        mkdir($absolutePath, 0777, true);
    }

    // Generate unique file name
    $ext = pathinfo($_FILES['attachment']['name'], PATHINFO_EXTENSION);
    $newFileName = uniqid("history_", true) . "." . $ext;

    // Move uploaded file
    if (move_uploaded_file($_FILES['attachment']['tmp_name'], $absolutePath . $newFileName)) {
        $file_url = $uploadDir . $newFileName; // relative path for DB
    } else {
        die("File upload failed. Check folder permissions.");
    }
}

// -----------------------------
// 2) Insert History Record
// -----------------------------
try {
    $stmt = $pdo->prepare("
        INSERT INTO permits_requests_history 
        (id, permit_request_id, updated_by, file_url, note, status, updated_at)
        VALUES (UUID(), ?, ?, ?, ?, ?, NOW())
    ");

    $stmt->execute([$req_id, $admin_id, $file_url, $note, $status]);

} catch (PDOException $e) {
    die("Error inserting history: " . $e->getMessage());
}

// -----------------------------
// 3) Update main request status + completion date
// -----------------------------
try {

    if ($status === "completed") {
        $update = $pdo->prepare("
            UPDATE permits_requests
            SET status = ?, completed_at = NOW()
            WHERE id = ?
        ");
        $update->execute([$status, $req_id]);
    } else {
        $update = $pdo->prepare("
            UPDATE permits_requests
            SET status = ?, completed_at = NULL
            WHERE id = ?
        ");
        $update->execute([$status, $req_id]);
    }

} catch (PDOException $e) {
    die("Error updating request: " . $e->getMessage());
}

// -----------------------------
// 4) Redirect back to details page
// -----------------------------
header("Location: /Municipality/admin.php?page=permitRequestDetails&id=".$req_id);
exit;
?>
