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
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Document</title>
    <link rel="stylesheet" href="/Municipality/css/complaints.css?v=2">
  </head>
  <body>
    <div class="complaints_page_con">
     <div class="complaints_page_title"> <h1>Public Complaints</h1> <button><a href="index.php?page=report">Add Complaint</a></button></div>
      <div class="complaints_page">
        <div class="complaints_page_header">
          <select>
            <option value="">All Permits</option>
            <option value="">Public Works</option>
            <option value="">Building</option>
            <option value="">Super Market</option>
          </select>
          <div class="complaints_page_search_con">
            <input type="text" placeholder="Search" /><img
              src="/Municipality/images/magnifying-glass.svg"
              alt=""
            />
          </div>
         
        </div>
         <div class="complaints">
           <?php foreach($complaints as $complaint): ?>
            <div class="complaint">
              <div class="complaint_header">
               <div class="complaint_title"><h3>Name:</h3><h3 class="permit_tile_h"><?= htmlspecialchars($complaint['first_name']." ". $complaint['last_name']  ?? 'Unknown') ?></h3></div> 
               <div class="complaint_details"><h3>Details:</h3><?= htmlspecialchars($complaint['description'] ?? 'Unknown') ?></div> 
                <button>Veiw Details</button>
              </div>
              <img src="<?= !empty($complaint['image_url']) ? htmlspecialchars($complaint['image_url']) : '/Municipality/images/empty.jpg' ?>" alt="Complaint Image">
            </div>
            <?php endforeach; ?>
          </div>
      </div>
    </div>
  </body>
</html>
