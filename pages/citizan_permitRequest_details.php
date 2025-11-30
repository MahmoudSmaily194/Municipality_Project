<?php
require_once '/xampp/htdocs/Municipality/backend/config/db.php';

if (!isset($_GET['id'])) {
    die("Permit request ID is missing");
}

$request_id = $_GET['id'];


try {
    $stmt = $pdo->prepare("
        SELECT 
            pr.*,
            CONCAT(u.first_name, ' ', u.last_name) AS applicant_name,
            u.email,
            p.title AS permit_title,
            p.description AS permit_description,
            c.name AS category_name
        FROM permits_requests pr
        LEFT JOIN users u ON pr.user_id = u.id
        LEFT JOIN permits p ON pr.permit_id = p.id
        LEFT JOIN permits_categories c ON p.category_id = c.id
        WHERE pr.id = ?
    ");
    $stmt->execute([$request_id]);
    $request = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$request) {
        echo "<h2>Permit request not found.</h2>";
        exit;
    }

    // ----------------------------------------------------
    // 4) FETCH USER SUBMITTED FILES
    // ----------------------------------------------------
    $filesStmt = $pdo->prepare("
        SELECT * FROM permits_requests_attachments
        WHERE permit_request_id = ?
    ");
    $filesStmt->execute([$request_id]);
    $submitted_files = $filesStmt->fetchAll(PDO::FETCH_ASSOC);

    // ----------------------------------------------------
    // 5) FETCH HISTORY
    // ----------------------------------------------------
    $historyStmt = $pdo->prepare("
        SELECT h.*, CONCAT(u.first_name, ' ', u.last_name) AS updated_by_name 
        FROM permits_requests_history h
        LEFT JOIN users u ON h.updated_by = u.id
        WHERE permit_request_id = ?
        ORDER BY h.updated_at DESC
    ");
    $historyStmt->execute([$request_id]);
    $history = $historyStmt->fetchAll(PDO::FETCH_ASSOC);
}
catch (PDOException $e) {
    die("Error: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8"/>
<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
<link rel="stylesheet" href="/Municipality/css/citizan_permitRequest_details.css?v=1" />
</head>
<body>
<div class="layout-container">
    <main>
        <div class="title-section">
            <h1><?= $request['permit_title'] ?></h1>
            <div class="status-badge"><?= $request['status'] ?></div>
        </div>

        <div class="content-columns">
            <div class="left-column">
                <div class="card">
                    <h2>Permit Description</h2>
                    <p><?= $request['permit_description'] ?></p>
                </div>

                <div class="card">
                    <h2>Request History</h2>
                   <div class="timeline">
                        <?php foreach($history as $item): ?>
                        <div class="timeline-item <?= $item['status'] ?? 'info' ?>">
                            <p><?= date('M d, Y', strtotime($item['updated_at'])) ?></p>
                            <h3><?= $item['status'] ?></h3>
                            <p>by System</p>
                            <?php if(!empty($item['notes'])): ?>
                            <p><?= $item['notes'] ?></p>
                            <?php endif; ?>
                        </div>
                        <?php endforeach; ?>
                        </div>
                   </div>
            </div>

            <div class="right-column">
                <div class="card">
                    <h3>Details</h3>
                    <div class="details-grid">
                        <div class="detail"><p>Request ID</p><p><?= $request['id'] ?></p></div>
                        <div class="detail"><p>Category</p><p><?= $request['category_name'] ?></p></div>
                        <div class="detail"><p>Priority</p><p>High</p></div>
                        <div class="detail"><p>Request Date</p><p><?= $request['requested_at'] ?></p></div>
                        <div class="detail"><p>Completion Date</p><p>N/A</p></div>
                        <div class="detail"><p>Created By</p><p><?= $request['applicant_name'] ?></p></div>
                    </div>
                </div>

                <div class="card">
                    <h3>Documents</h3>
                    <ul class="documents-list">
                        <?php foreach ($submitted_files as $file): ?>
                           <li>
                            <div style="display:flex; align-items:center; gap:8px;">
                                <span><?= basename($file['file_url']) ?></span>
                            </div>
                            <a href="<?= $file['file_url'] ?>"><span class="material-symbols-outlined">download</span></a>
                           </li>
                        <?php endforeach; ?>
                    </ul>
            </div>
        </div>
    </main>
</div>
</body>
</html>
