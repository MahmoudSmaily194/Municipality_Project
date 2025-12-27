<?php
  if (!defined('IS_LOGGEDIN')) {
      header('Location: /Municipality/admin/login.php');
      exit;
  }
  
require_once '/xampp/htdocs/Municipality/backend/config/db.php';

// Fetch events from database
try {
    $stmt = $pdo->query("SELECT id, title, date,description,image_url, location FROM events ORDER BY date DESC");
    $events = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $events = [];
}
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Document</title>
    <link rel="stylesheet" href="/Municipality/css/events.css" />
    <link rel="stylesheet" href="/Municipality/css/material-symbols.css" />
    <link rel="stylesheet" href="/Municipality/css/style.css" />
  </head>
  <body>
    <main class="events_page_con">
      <div class="events_page">
        <section class="page-header">
          <h1>Municipal<span>ity Events</span></h1>
          <p>
            Discover upcoming municipal activities, public meetings, and
            community events in your area.
          </p>
        </section>

        <div class="search-box">
          <span class="material-symbols-outlined">search</span>
          <input type="text" placeholder="Search events..." />
        </div>

        <section class="events-grid">
          <!-- EVENT CARD -->
             <?php foreach ($events as $event): ?>
          <article class="event-card">
            <div
              class="card-image"
              style="
                background-image: url('<?= !empty($event['image_url']) ? htmlspecialchars($event['image_url']) : '/Municipality/images/empty.jpg' ?>');
              "
            ></div>
            <div class="card-content">
              <h3><?php echo htmlspecialchars($event['title']); ?> </h3>
              <p>
                <?php echo htmlspecialchars($event['description']); ?>
              </p>

              <div class="event-meta">
                <div>
                  <span class="material-symbols-outlined">calendar_month</span>
                  <?php echo date('d/m/Y', strtotime($event['date'])); ?>
                </div>
                <div>
                  <span class="material-symbols-outlined">location_on</span>
                 <?php echo htmlspecialchars($event['location']); ?>
                </div>
              </div>

              <a href="#" class="details-link">
                View details
                <span class="material-symbols-outlined">arrow_forward</span>
              </a>
            </div>
          </article>
          <?php endforeach; ?>
        </section>
      </div>
    </main>
  </body>
</html>

