<?php
if (!defined('IS_ADMIN_PANEL')) {
    header('Location: /Municipality/admin/login.php');
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    session_unset();
    session_destroy();
    exit;
}

require_once '/xampp/htdocs/Municipality/backend/config/db.php';
 include '/xampp/htdocs/Municipality/includes/delete_modal.php';
// Fetch events from database
try {
    $stmt = $pdo->query("SELECT id, title, date, location FROM events ORDER BY date DESC");
    $events = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $events = [];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Events Management</title>
    <link rel="stylesheet" href="/Municipality/css/admin_events.css?v=2">
    <link rel="stylesheet" href="/Municipality/css/deleteDialog.css?v=3">
    <script src="/Municipality/includes/delete_modal.js"></script>
</head>
<body>
<div class="events_control_page_con">
  <div class="events_control_page">
    <div class="events_control_page_header">
      <h1>Events Management</h1>
      <button><a href="admin.php?page=addEventModel">Add Event</a></button>
    </div>

    <p>Add or manage events in the system.</p>

    <h3>Existing Events</h3>

    <div class="events_control_table_con">
      <div>
        <table>
          <thead>
            <tr>
              <th>Title</th>
              <th>Date</th>
              <th>Location</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            <?php if (!empty($events)): ?>
                <?php foreach ($events as $event): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($event['title']); ?></td>
                        <td><?php echo date('d/m/Y', strtotime($event['date'])); ?></td>
                        <td><?php echo htmlspecialchars($event['location']); ?></td>
                        <td>
                            <div>
                                <p class="events_edit_btn">View</p>
                                <p>|</p>
                                <p class="events_delete_btn" onclick="openDeleteModal('/Municipality/backend/delete_event.php', '<?= htmlspecialchars($event['id'], ENT_QUOTES) ?>')">Delete</p>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="4" style="text-align:center;">No events found.</td>
                </tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
</body>
</html>
