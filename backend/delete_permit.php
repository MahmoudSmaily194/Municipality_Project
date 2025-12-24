<?php
session_start();
require_once '/xampp/htdocs/Municipality/backend/config/db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: /Municipality/admin/login.php');
    exit;
}

// Get news ID
if (!isset($_POST['id']) || empty($_POST['id'])) {
    die("permit ID is required");
}

$permitId = $_POST['id'];

try {
    // 1. Get the image URL to delete the file
    $stmt = $pdo->prepare("SELECT image_url FROM permits WHERE id = ?");
    $stmt->execute([$permitId]);
    $permit = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$permit) {
        die("News item not found");
    }

    // Delete the image file if it exists
    if (!empty($permit['image_url'])) {
        $imagePath = $_SERVER['DOCUMENT_ROOT'] . $permit['image_url']; // full server path
        if (file_exists($imagePath)) {
            unlink($imagePath); // delete the file
        }
    }

    // 2. Delete the news item from database
    $stmt = $pdo->prepare("DELETE FROM permits WHERE id = ?");
    $stmt->execute([$permitId]);

    if ($stmt->rowCount() > 0) {
        header("Location: /Municipality/admin.php?page=permits");
        exit;
    } else {
        die("Failed to delete permit");
    }
} catch (PDOException $e) {
    die("Database error: " . $e->getMessage());
}
?>
