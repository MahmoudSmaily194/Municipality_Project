<?php
if (!defined('IS_ADMIN_PANEL')) {
  header('Location: /Municipality/admin/login.php');
  exit();
}
require_once '/xampp/htdocs/Municipality/backend/config/db.php';
// Fetch only unseen complaints and permit requests
try {
  $stmt = $pdo->query("
        SELECT 
            c.id, 
            'complaint' AS type, 
            CONCAT_WS(' ', u.first_name, u.last_name) AS user_name, 
            ci.issue_name AS title, 
            c.created_at AS activity_date
        FROM complaints c
        LEFT JOIN users u ON c.created_by = u.id
        LEFT JOIN complaint_issues ci ON c.issue_id = ci.id
        WHERE c.is_seen = 0

        UNION ALL

        SELECT 
            pr.id, 
            'permit' AS type, 
            CONCAT(u.first_name, ' ', u.last_name) AS user_name, 
            p.title AS title, 
            pr.requested_at AS activity_date
        FROM permits_requests pr
        LEFT JOIN users u ON pr.user_id = u.id
        LEFT JOIN permits p ON pr.permit_id = p.id
        WHERE pr.is_seen = 0

        ORDER BY activity_date DESC
    ");

  $activities = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
  die('Error loading recent activity: ' . $e->getMessage());
}

$stmt = $pdo->query("
    SELECT 
        (SELECT COUNT(*) FROM news) AS total_news,
        (SELECT COUNT(*) FROM events) AS total_events,
        (SELECT COUNT(*) FROM permits) AS total_permits,
        (SELECT COUNT(*) FROM complaints) AS total_complaints
");
$counts = $stmt->fetch(PDO::FETCH_ASSOC);

// Access counts
$newsNb = $counts['total_news'];
$eventsNb = $counts['total_events'];
$permitsNb = $counts['total_permits'];
$complaintsNb = $counts['total_complaints'];
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Document</title>
    <link rel="stylesheet" href="/Municipality/css/dashboard.css?v=2" />
    <link rel="stylesheet" href="/Municipality/css/material-symbols.css?v=2" />
  </head>
  <body>
    <div class="dashboard_page_con">
      <div class="dashboard_page">
        <h1>Dashboard</h1>
        <div class="over_view_con">
          <h2>Over view</h2>
          <div class="over_view">
            <div>
              <h3>Total News Articles</h3>
              <h3><?= $newsNb ?></h3>
            </div>
            <div>
              <h3>Up Coming Events</h3>
              <h3><?= $eventsNb ?></h3>
            </div>
            <div>
              <h3>Permits Offered</h3>
              <h3><?= $permitsNb ?></h3>
            </div>
            <div>
              <h3>Compliants Recieved</h3>
              <h3><?= $complaintsNb ?></h3>
            </div>
          </div>
        </div>
        <div class="recent_act">
          <h2>Recent Activity</h2>
          <div class="notifications">
            <?php if (empty($activities)): ?>
            <p class="no-notifications">No new activity</p>
            <?php endif; ?>

            <?php foreach ($activities as $activity): ?>
            <div class="notific"  
            data-id="<?= $activity['id'] ?>"
            data-type="<?= $activity['type'] ?>">
              <div>
                <?php if ($activity['type'] === 'complaint'): ?>
                <span class="material-symbols-outlined">campaign</span>
                <?php else: ?>
                <span class="material-symbols-outlined">description</span>
                <?php endif; ?>
              </div>

              <div>
                <h4><?= htmlspecialchars($activity['user_name']) ?></h4>
                <p>
                  <?= $activity['type'] === 'complaint' ? 'Complaint:' : 'Permit Request:' ?>
                  <?= htmlspecialchars($activity['title']) ?>
                </p>
              </div>
            </div>
            <?php endforeach; ?>
          </div>
        </div>
      </div>
    </div>
<script>
document.querySelectorAll('.notific').forEach(item => {
  item.addEventListener('click', async () => {
    const id = item.dataset.id;
    const type = item.dataset.type;

    try {
      await fetch('/Municipality/backend/mark_as_seen.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ id, type })
      });
     item.remove();
      // Redirect based on type
      if (type === 'complaint') {
        window.location.href = `/Municipality/admin.php?page=complaints`;
      } else {
        
        window.location.href = `/Municipality/admin.php?page=permitRequestDetails&id=${id}`;
      }

    } catch (err) {
      console.error('Error marking as seen', err);
    }
  });
});
</script>

  </body>
</html>
