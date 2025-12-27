<?php
  if (!defined('IS_LOGGEDIN')) {
      header('Location: /Municipality/admin/login.php');
      exit;
  }
  require_once '/xampp/htdocs/Municipality/backend/config/db.php';
 $stmt = $pdo->query("
   SELECT 
    c.id, 
    c.description, 
    c.status, 
    c.created_at, 
    c.image_url, 
     u.first_name,
    u.last_name,
    ci.issue_name AS issue_type
   FROM complaints c
   LEFT JOIN users u ON c.created_by = u.id
   LEFT JOIN complaint_issues ci ON c.issue_id = ci.id
   WHERE c.visibility = 'visible'
   ORDER BY c.created_at DESC
");
$complaints = $stmt->fetchAll();
 ?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>
  <link rel="stylesheet" href="/Municipality/h.css">
  <link rel="stylesheet" href="/Municipality/css/material-symbols.css">
</head>
<body>
  <main class="p_complaints_page_con">
    <div class="p_complaints_page">
      <!-- Header -->
      <section class="page-header">
        <div class="page-header-top">
          <h1>Public <span>Complaints</span></h1>
          <button class="add-btn" onclick="window.location.href='index.php?page=report'">
            <span class="material-symbols-outlined">add</span>
            Submit New Complaint
          </button>
        </div>
        <p class="description">
          Reported issues and public concerns submitted by citizens.
        </p>
      </section>

      <!-- Search -->
      <div class="search-box">
        <span class="material-symbols-outlined search-icon">search</span>
        <input type="text" placeholder="Search complaints..." />
      </div>

      <!-- Complaint Card -->
          <?php foreach($complaints as $complaint): ?>
      <article class="complaint-card">
        <div
          class="card-image"
          style="
            background-image: url('<?= !empty($complaint['image_url']) ? htmlspecialchars($complaint['image_url']) : '/Municipality/images/empty.jpg' ?>');
          "
        >
          <div class="card-date">
            <span class="material-symbols-outlined" style="font-size: 14px"
              >calendar_today</span
            >
            <?php echo date('d/m/Y', strtotime($complaint['created_at'])); ?>
          </div>
        </div>

        <div class="card-content">
          <div class="card-title">
            <h3><?= htmlspecialchars($complaint['first_name']." ". $complaint['last_name']  ?? 'Unknown') ?></h3>
            <span class="status pending"><?= $complaint['status'] ?></span>
          </div>

          <div class="location">
            <span class="material-symbols-outlined" style="font-size: 16px"
              >location_on</span
            >
           <?= htmlspecialchars($complaint['issue_type'] )?>
          </div>

          <p class="description">
           <?= htmlspecialchars($complaint['description'] ?? 'Unknown') ?>
          </p>

          <div class="card-actions">
            <button class="read-btn">
              Read More
              <span class="material-symbols-outlined" style="font-size: 18px"
                >arrow_forward</span
              >
            </button>
          </div>
        </div>
      </article>
      <?php endforeach; ?>
      </div>
    </main>
</body>
</html>
