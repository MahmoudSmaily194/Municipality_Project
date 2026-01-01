<?php
require_once '/xampp/htdocs/Municipality/backend/config/db.php';

/* ------------------------------
   Validate complaint ID
------------------------------ */
$id = $_GET['id'] ?? null;
if (!$id) {
    exit('<h2>Invalid request</h2>');
}
/* ------------------------------
   Handle update (POST)
------------------------------ */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $status     = $_POST['status'] ?? 'pending';
    $visibility = isset($_POST['visibility']) ? 'visible' : 'hidden';
    $note       = trim($_POST['note'] ?? '');

    // Update complaint status & visibility
    $stmt = $pdo->prepare("
        UPDATE complaints
        SET status = ?, visibility = ?, updated_at = NOW()
        WHERE id = ?
    ");
    $stmt->execute([$status, $visibility, $id]);

    // Redirect to avoid resubmission
    header("Location: admin.php?page=complaintDetails&id=" . $id);
    exit;
}

/* ------------------------------
   Fetch complaint
------------------------------ */
$stmt = $pdo->prepare("
  SELECT
    c.*,
    ci.issue_name,
    CONCAT(u.first_name, ' ', u.last_name) AS reporter
  FROM complaints c
  LEFT JOIN complaint_issues ci ON c.issue_id = ci.id
  LEFT JOIN users u ON c.created_by = u.id
  WHERE c.id = ?
");
$stmt->execute([$id]);
$complaint = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$complaint) {
    exit('<h2>Complaint not found</h2>');
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Complaint Details</title>
<link rel="stylesheet" href="/Municipality/css/complaintDetails.css">
<link rel="stylesheet" href="/Municipality/css/material-symbols.css">
</head>
<body>
<main class="container">
<div class="complaintDetails_page">

    <!-- HEADER -->
    <section class="page-header">
        <div>
            <div class="page-header-title">
                <h1><?= htmlspecialchars($complaint['issue_name']) ?></h1>
                <span class="badge  <?= ucfirst($complaint['status']) ?>">
                    <span class="material-symbols-outlined">radio_button_checked</span>
                    <?= ucfirst(str_replace('_', ' ', $complaint['status'])) ?>
                </span>
            </div>
            <p class="meta">
                <span class="material-symbols-outlined">calendar_today</span>
                Created <?= date('M d, Y', strtotime($complaint['created_at'])) ?>
            </p>
        </div>
    </section>

    <!-- CONTENT -->
    <section class="content">

        <!-- LEFT -->
        <div class="left">

            <!-- DESCRIPTION -->
            <div class="description_card">
                <div class="description_card_text">
                    <h3>Issue Description</h3>
                    <p><?= nl2br(htmlspecialchars($complaint['description'])) ?></p>
                </div>
                <div class="description_card_user">
                    <img src="/Municipality/images/userImg.png" alt="Reporter">
                    <div>
                        <h5><?= htmlspecialchars($complaint['reporter'] ?? 'Unknown') ?></h5>
                        <p>Citizen Reporter</p>
                    </div>
                </div>
            </div>

            <!-- MEDIA -->
            <div class="media">

                <div class="image-card">
                    <h4>Attached Evidence</h4>
                    <?php if ($complaint['image_url']): ?>
                        <a href="<?= htmlspecialchars($complaint['image_url']) ?>" download>Download</a>
                    <?php else: ?>
                        <a>No attachment</a>
                    <?php endif; ?>
                </div>

                <div class="map-card">
                    <h4><span class="material-symbols-outlined">location_on</span> Location</h4>
                    <?php if ($complaint['latitude'] && $complaint['longitude']): ?>
                        <a href="https://www.google.com/maps?q=<?= $complaint['latitude'] ?>,<?= $complaint['longitude'] ?>" target="_blank">
                            Open Maps
                        </a>
                    <?php else: ?>
                        <span>No location provided</span>
                    <?php endif; ?>
                </div>

            </div>
        </div>

        <!-- RIGHT -->
        <div class="right">
            <form method="POST" class="update_card_con">
                <div class="update-card-header">
                    <h4>
                        <span class="material-symbols-outlined">edit_square</span>
                        Update Ticket
                    </h4>
                </div>

                <div class="update_card">

                    <!-- STATUS -->
                    <div class="status">
                        <label>Current Status</label>
                        <select name="status">
                            <?php
                            $statuses = ['pending','in_progress','completed','rejected'];
                            foreach ($statuses as $statusOption):
                            ?>
                                <option value="<?= $statusOption ?>" <?= $complaint['status'] === $statusOption ? 'selected' : '' ?>>
                                    <?= ucfirst(str_replace('_',' ',$statusOption)) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- VISIBILITY -->
                    <div class="visibility">
                        <label>Public visibility</label>
                        <label class="switch">
                            <input type="checkbox" name="visibility" <?= $complaint['visibility']==='visible' ? 'checked' : '' ?>>
                            <span class="slider"></span>
                        </label>
                    </div>

                    <!-- INTERNAL NOTE -->
                    <textarea name="note" placeholder="Add internal note"></textarea>

                    <!-- SUBMIT -->
                    <button type="submit" class="btn-primary full">Save Changes</button>

                </div>
            </form>
        </div>

    </section>

</div>
</main>
</body>
</html>
