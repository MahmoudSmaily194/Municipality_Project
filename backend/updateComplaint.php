<?php
require_once '/xampp/htdocs/Municipality/backend/config/db.php';
header('Content-Type: application/json');

$id = $_POST['id'] ?? null;
if (!$id) {
    echo json_encode([
        'success' => false,
        'message' => 'There is no ID',
    ]);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $status = $_POST['status'] ?? 'pending';
    $visibility = isset($_POST['visibility']) ? 'visible' : 'hidden';

    try {
        // ----------------- Update complaint -----------------
        $stmt = $pdo->prepare("
            UPDATE complaints
            SET status = ?, visibility = ?, updated_at = NOW()
            WHERE id = ?
        ");
        $stmt->execute([$status, $visibility, $id]);

        // ----------------- Fetch the updated complaint -----------------
        $stmt = $pdo->prepare("SELECT * FROM complaints WHERE id = ?");
        $stmt->execute([$id]);
        $updatedComplaint = $stmt->fetch(PDO::FETCH_ASSOC);

        echo json_encode([
            'success' => true,
            'message' => 'Complaint updated successfully',
            'complaint' => $updatedComplaint
        ]);
    } catch (PDOException $e) {
        echo json_encode([
            'success' => false,
            'message' => 'Database error: ' . $e->getMessage(),
        ]);
    }

    exit();
}
