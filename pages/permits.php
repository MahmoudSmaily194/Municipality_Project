<?php
  if (!defined('IS_LOGGEDIN')) {
      header('Location: /Municipality/admin/login.php');
      exit;
  }
require_once '/xampp/htdocs/Municipality/backend/config/db.php';
try {
    $stmt = $pdo->query("
        SELECT p.id, p.title, p.status,p.description,p.image_url, c.name AS category
        FROM permits p
        LEFT JOIN permits_categories c ON p.category_id = c.id
        ORDER BY p.created_at DESC
    ");
    $permits = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $permits = [];
   
    echo "Error: " . $e->getMessage();
}
 ?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Document</title>
    <link rel="stylesheet" href="/Municipality/css/permits.css?v=4">
  </head>
  <body>
    <div class="permits_page_con">
     <div class="permits_page_title"> <h1>Municipality Permits</h1></div>
      <div class="permits_page">
        <div class="permits_page_header">
          <select>
            <option value="">All Permits</option>
            <option value="">Public Works</option>
            <option value="">Building</option>
            <option value="">Super Market</option>
          </select>
          <div class="permits_page_search_con">
            <input type="text" placeholder="Search" /><img
              src="/Municipality/images/magnifying-glass.svg"
              alt=""
            />
          </div>
         
        </div>
         <div class="permits">
           <?php foreach ($permits as $permit): ?>
            <div class="permit">
              <div class="permit_header">
                <h3><?= htmlspecialchars($permit['title']) ?></h3>
                <p><?= htmlspecialchars($permit['description']) ?></p>
                <div><button><a href="/Municipality/index.php?page=applyPermit&id=<?php echo $permit['id'] ?>">Apply Now</a></button>
                <button>Veiw Details</button></div>
              </div>
              <img src="<?= !empty($permit['image_url']) ? htmlspecialchars($permit['image_url']) : '/Municipality/images/empty.jpg' ?>" alt="Event Image">
            </div>
            <?php endforeach; ?>
          </div>
      </div>
    </div>
  </body>
</html>
