<?php
define('IS_ADMIN_PANEL', true);
require_once '/xampp/htdocs/Municipality/backend/config/db.php';

$data = json_decode(file_get_contents("php://input"), true);

$id = $data['id'] ?? null;
$type = $data['type'] ?? null;

if (!$id || !$type) {
    http_response_code(400);
    exit;
}

try {
    if ($type === 'complaint') {
        $stmt = $pdo->prepare("UPDATE complaints SET is_seen = 1 WHERE id = ?");
    } elseif ($type === 'permit') {
        $stmt = $pdo->prepare("UPDATE permits_requests SET is_seen = 1 WHERE id = ?");
    } else {
        http_response_code(400);
        exit;
    }

    $stmt->execute([$id]);
    echo json_encode(['success' => true]);

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}
