<?php
require_once '/wamp64/www/Municipality/backend/config/db.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST'){
    if (!isset($_POST['title'], $_POST['eventDate'], $_POST['description'], $_POST['location'])) {
        die("All fields are required");
    }
    $title = trim($_POST['title']);
    $eventDate = trim($_POST['eventDate']);
    $description = trim($_POST['description']);
    $location = trim($_POST['location']);

    if (empty($title) || empty($eventDate) || empty($description) || empty($location)) {
        die("All fields must not be empty");
    }
    // Generate UUID
    function uuidv4(){
        $data = random_bytes(16);
        $data[6] = chr((ord($data[6]) & 0x0f) | 0x40); // version 4
        $data[8] = chr((ord($data[8]) & 0x3f) | 0x80); // variant
        return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
    }

    $id = uuidv4();
    try {
        $sql = "INSERT INTO events (id, title, date , description, location) VALUES (?, ?, ?, ?, ?)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$id, $title, $eventDate, $description, $location]);
        if ($stmt->rowCount() > 0) {
            header("Location: /Municipality/admin.php?page=events");
            exit;
        }
    } catch (PDOException $e) {
        die("Error: " . $e->getMessage());
    }
}
?>