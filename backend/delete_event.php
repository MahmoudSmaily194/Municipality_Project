<?php
session_start();
require_once '/xampp/htdocs/Municipality/backend/config/db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: /Municipality/admin/login.php');
    exit;
}

// Get event ID
if (!isset($_POST['id']) || empty($_POST['id'])) {
    die("Event ID is required");
}

$eventId = $_POST['id'];

try {
    // 1. Get the image URL to delete the file
    $stmt = $pdo->prepare("SELECT image_url FROM events WHERE id = ?");
    $stmt->execute([$eventId]);
    $event = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$event) {
        die("Event not found");
    }

    // Delete the image file if it exists
    if (!empty($event['image_url'])) {
        $imagePath = $_SERVER['DOCUMENT_ROOT'] . $event['image_url']; // full server path
        if (file_exists($imagePath)) {
            unlink($imagePath); // delete the file
        }
    }

    // 2. Delete the event from database
    $stmt = $pdo->prepare("DELETE FROM events WHERE id = ?");
    $stmt->execute([$eventId]);

    if ($stmt->rowCount() > 0) {
        header("Location: /Municipality/admin.php?page=events");
        exit;
    } else {
        die("Failed to delete event");
    }
} catch (PDOException $e) {
    die("Database error: " . $e->getMessage());
}
?>
