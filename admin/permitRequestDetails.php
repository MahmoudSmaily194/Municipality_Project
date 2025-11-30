<?php 
require_once '/xampp/htdocs/Municipality/backend/config/db.php';

// ----------------------------------------------------
// 1) VALIDATE ID
// ----------------------------------------------------
if (!isset($_GET['id'])) {
    echo "<h2>No request ID provided.</h2>";
    exit;
}
$request_id = $_GET['id'];

try {

    // ----------------------------------------------------
    // 2) FETCH REQUEST MAIN INFO
    // ----------------------------------------------------
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
    // 3) FETCH REQUIRED DOCUMENTS
    // ----------------------------------------------------
    $reqDocs = $pdo->prepare("
        SELECT * FROM permits_required_attachments
        WHERE permit_id = ?
    ");
    $reqDocs->execute([$request["permit_id"]]);
    $required_documents = $reqDocs->fetchAll(PDO::FETCH_ASSOC);

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

} catch (PDOException $e) {
    die("Error loading permit request: " . $e->getMessage());
}
?>


<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Permit Request Details</title>
    <link rel="stylesheet" href="/Municipality/css/permitRequestDetails.css?v=2">
  </head>
  <body>
    <div class="permit-details-page">
      
      <!-- HEADER -->
      <div class="page-header">
        <h2 class="page-title">Permit Request Details</h2>
        <p class="page-subtitle">View and manage a citizen’s permit request</p>
      </div>

      <div class="permit-grid">

        <!-- LEFT COLUMN -->
        <div class="left-column">

          <!-- SUMMARY -->
          <div class="card request-summary-card">
            <h3 class="card-title">Request Summary</h3>

            <div class="summary-item">
            <span class="label">Request ID</span>
            <p class="value"><?= $request['id'] ?></p>
            </div>
       

            <div class="summary-item">
              <span class="label">Status</span>
              <p class="value status-badge status-<?= strtolower($request['status']) ?>">
                <?= ucfirst($request['status']) ?>
              </p>
            </div>

            <div class="summary-item">
              <span class="label">Priority</span>
              <p class="value priority-<?= strtolower($request['priority']) ?>">
                <?= ucfirst($request['priority']) ?>
              </p>
            </div>

            <div class="summary-item">
              <span class="label">Requested On</span>
              <p class="value"><?= $request['requested_at'] ?></p>
            </div>

          </div>

          <!-- CITIZEN INFO -->
          <div class="card citizen-info-card">
            <h3 class="card-title">Citizen Information</h3>

            <div class="citizen-details">
              <img src="/Municipality/images/mahmoud.jpg" class="citizen-avatar" />
              <div>
                <p class="citizen-name"><?= $request['applicant_name'] ?></p>
                <p class="citizen-email"><?= $request['email'] ?></p>
                <p class="citizen-phone"><?= $request['phone_number'] ?></p>
              </div>
            </div>
          </div>

          <!-- REQUIRED DOCUMENTS -->
          <div class="card required-documents-card">
            <h3 class="card-title">Required Documents</h3>

            <ul class="document-list">
              <?php foreach ($required_documents as $doc): ?>
                <li class="document-item"><?= $doc['document_name'] ?></li>
              <?php endforeach; ?>
            </ul>
          </div>

        </div>

        <!-- RIGHT COLUMN -->
        <div class="right-column">

          <!-- PERMIT INFO -->
          <div class="card permit-info-card">
            <h3 class="card-title">Permit Information</h3>

            <div class="info-row">
              <span class="label">Permit Title</span>
              <p class="value"><?= $request['permit_title'] ?></p>
            </div>

            <div class="info-row">
              <span class="label">Category</span>
              <p class="value"><?= $request['category_name'] ?></p>
            </div>

            <div class="info-row">
              <span class="label">Description</span>
              <p class="value"><?= $request['permit_description'] ?></p>
            </div>
          </div>

          <!-- SUBMITTED FILES -->
          <div class="card submitted-files-card">
            <h3 class="card-title">Submitted Files</h3>

            <div class="files-container">
              <?php foreach ($submitted_files as $file): ?>
                <a class="file-box" href="<?= $file['file_url'] ?>" download>
                  <span class="file-name"><?= basename($file['file_url']) ?></span>
                </a>
              <?php endforeach; ?>
            </div>
          </div>

          <!-- UPDATE STATUS -->
          <div class="card update-status-card">
            <h3 class="card-title">Update Request Status</h3>

            <form class="update-form" action="/Municipality/backend/updateRequest.php" method="POST" enctype="multipart/form-data">
              
              <input type="hidden" name="request_id" value="<?= $request['id'] ?>">

              <label for="status" class="form-label">Status</label>
              <select id="status" name="status" class="form-select">
                <option value="pending"     <?= $request['status']=="pending"?"selected":"" ?>>Pending</option>
                <option value="in_progress" <?= $request['status']=="in_progress"?"selected":"" ?>>In Progress</option>
                <option value="completed"    <?= $request['status']=="completed"?"selected":"" ?>>Approved</option>
                <option value="rejected"    <?= $request['status']=="rejected"?"selected":"" ?>>Rejected</option>
              </select>

              <label for="note" class="form-label">Notes (Optional)</label>
              <textarea id="note" name="note" class="form-textarea" placeholder="Add a note..."></textarea>

              <label class="form-label">Attach File (Optional)</label>
              <input type="file" name="file" class="file-input" />

              <button class="btn-save">Save Changes</button>
            </form>
          </div>

          <!-- HISTORY -->
          <div class="card request-history-card">
            <h3 class="card-title">Request History</h3>

            <?php foreach ($history as $row): ?>
              <div class="history-item">
                <p class="history-date"><?= $row['updated_at'] ?></p>
                <p class="history-text">
                  <?= $row['updated_by_name'] ?> updated status to 
                  <strong><?= ucfirst($row['status']) ?></strong><br>
                  Note: <?= $row['note'] ?>
                  <?php if ($row['file_url']): ?>
                    <br><a href="<?= $row['file_url'] ?>" download>Download Attachment</a>
                  <?php endif; ?>
                </p>
              </div>
            <?php endforeach; ?>
          </div>

        </div>
      </div>

    </div>
  </body>
</html>
