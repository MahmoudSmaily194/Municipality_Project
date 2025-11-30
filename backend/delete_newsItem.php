<?php
session_start();
require_once '/xampp/htdocs/Municipality/backend/config/db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: /Municipality/admin/login.php');
    exit;
}

// Get news ID
if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("News ID is required");
}

$newsItemId = $_GET['id'];

try {
    // 1. Get the image URL to delete the file
    $stmt = $pdo->prepare("SELECT image_url FROM news WHERE id = ?");
    $stmt->execute([$newsItemId]);
    $newsItem = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$newsItem) {
        die("News item not found");
    }

    // Delete the image file if it exists
    if (!empty($newsItem['image_url'])) {
        $imagePath = $_SERVER['DOCUMENT_ROOT'] . $newsItem['image_url']; // full server path
        if (file_exists($imagePath)) {
            unlink($imagePath); // delete the file
        }
    }

    // 2. Delete the news item from database
    $stmt = $pdo->prepare("DELETE FROM news WHERE id = ?");
    $stmt->execute([$newsItemId]);

    if ($stmt->rowCount() > 0) {
        header("Location: /Municipality/admin.php?page=news");
        exit;
    } else {
        die("Failed to delete news item");
    }
} catch (PDOException $e) {
    die("Database error: " . $e->getMessage());
}
?>
