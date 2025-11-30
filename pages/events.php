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
    <link rel="stylesheet" href="/Municipality/css/events.css?v=2">
  </head>
  <body>
    <div class="events_page_con">
     <div class="events_page_title"> <h1>Municipality Permits</h1></div>
      <div class="events_page">
        <div class="events_page_header">
          <select>
            <option value="">All Permits</option>
            <option value="">Public Works</option>
            <option value="">Building</option>
            <option value="">Super Market</option>
          </select>
          <div class="events_page_search_con">
            <input type="text" placeholder="Search" /><img
              src="/Municipality/images/magnifying-glass.svg"
              alt=""
            />
          </div>
         
        </div>
         <div class="events">
           <?php foreach ($events as $event): ?>
            <div class="event">
              <div class="event_header">
               <div class="event_title"><h3>Title:</h3><h3 class="permit_tile_h"><?php echo htmlspecialchars($event['title']); ?></h3></div> 
               <div class="event_details"><h3>Details:</h3><p><?php echo htmlspecialchars($event['description']); ?></p></div> 
                <div class="event_date"><h3>Date:</h3><h4 style="color:#1a80e5"><?php echo date('d/m/Y', strtotime($event['date'])); ?></h4></div>
             <div class="event_loc"><h3>Location:</h3><h4 class="permit_tile_h"><?php echo htmlspecialchars($event['location']); ?></h4></div>
                <button>Veiw Details</button>
              </div>
              <img src="<?= !empty($event['image_url']) ? htmlspecialchars($event['image_url']) : '/Municipality/images/empty.jpg' ?>" alt="Event Image">
            </div>
             <?php endforeach; ?>
          </div>
      </div>
    </div>
  </body>
</html>
