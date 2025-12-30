<?php
require_once '/xampp/htdocs/Municipality/backend/config/db.php';

if (!isset($_GET['id'])) {
    die("Permit ID is missing");
}

$permit_id = $_GET['id'];

try {
    // Fetch selected permit
    $stmt = $pdo->prepare("
        SELECT p.*, c.name AS category
        FROM permits p
        LEFT JOIN permits_categories c ON p.category_id = c.id
        WHERE p.id = ?
    ");
    $stmt->execute([$permit_id]);
    $permit = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$permit) {
        die("Permit not found.");
    }

    // Fetch required attachments
    $stmt2 = $pdo->prepare("
        SELECT * FROM permits_required_attachments
        WHERE permit_id = ?
    ");
    $stmt2->execute([$permit_id]);
    $required_docs = $stmt2->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    die("Error: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/Municipality/css/apply_permit.css?v=4">
     <link rel="stylesheet" href="/Municipality/css/style.css" />
    <title>Apply for Permit</title>
</head>
<body>

<div class="apply_permit_page_con">
<form action="/Municipality/backend/submit_permit_request.php" 
      method="POST" enctype="multipart/form-data" class="apply_permit_page">

    <input type="hidden" name="permit_id" value="<?= $permit_id ?>">

    <h1>Apply for <span>Permit</span></h1>

    <!-- Permit Overview -->
    <div class="permit_overview_con">
        <div class="permit_overview">
            <div class="permit_overview_content">
                <div class="permit_overview_categ_status">
                    <label class="permit_overview_categ"><?= htmlspecialchars($permit['category']) ?></label>
                    <label class="permit_overview_status"><?= htmlspecialchars($permit['status']) ?></label>
                </div>

                <div class="permit_overview_details">
                    <h3><?= htmlspecialchars($permit['title']) ?></h3>
                    <p><?= nl2br(htmlspecialchars($permit['description'])) ?></p>
                </div>
            </div>

            <img src="<?= !empty($permit['image_url']) ? $permit['image_url'] : '/Municipality/images/empty.jpg' ?>" alt="">
        </div>
    </div>

    <!-- Required Attachments -->
    <div class="permit_required_att_con">
        <div class="permit_required_atts">
            <h2>Required Attachments</h2>
            <hr>

            <?php if (count($required_docs) === 0): ?>
                <p>No attachments required for this permit.</p>
            <?php else: ?>
                <?php foreach ($required_docs as $doc): ?>
                    <div class="permit_required_att">
                        <h3><?= htmlspecialchars($doc['document_name']) ?></h3>

                        <label for="<?= $doc['id'] ?>">
                            <img src="/Municipality/images/cloud-upload-alt.svg" alt="">
                            <h5>Click to Upload</h5>
                        </label>

                        <input 
                        id="<?= $doc['id'] ?>"
                            type="file" 
                            name="attachment_<?= $doc['id'] ?>" 
                            accept="image/*,application/pdf" 
                            hidden 
                            required
                        >
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>

    <!-- Application Information -->
    <div class="request_information_con">
        <div class="request_information">
            <h2>Application Information</h2>
            <hr>
            <p>You may describe the purpose of your request or any special notes.</p>

            <textarea name="request_description" required></textarea>
        </div>
    </div>

    <!-- Buttons -->
    <div class="apply">
        <button type="button" onclick="window.history.back()">Cancel</button>
        <button type="submit">Submit</button>
    </div>

</form>
</div>

</body>
</html>
