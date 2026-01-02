<?php
if (!defined('IS_LOGGEDIN')) {
    header('Location: /Municipality/admin/login.php');
    exit;
}

require_once '/xampp/htdocs/Municipality/backend/config/db.php';

// Get search and category filter from GET
$search = $_GET['q'] ?? '';
$categoryFilter = $_GET['category'] ?? '';

try {
    // Build dynamic WHERE conditions
    $conditions = [];
    $params = [];

    if (!empty($search)) {
        // Search in title and description
        $conditions[] = "(p.title LIKE :search OR p.description LIKE :search)";
        $params[':search'] = "%$search%";
    }

    if (!empty($categoryFilter)) {
        // Filter by category
        $conditions[] = "c.name = :category";
        $params[':category'] = $categoryFilter;
    }

    $where = '';
    if (!empty($conditions)) {
        $where = 'WHERE ' . implode(' AND ', $conditions);
    }

    // Prepare and execute the query
    $stmt = $pdo->prepare("
        SELECT p.id, p.title, p.status, p.description, p.image_url, c.name AS category
        FROM permits p
        LEFT JOIN permits_categories c ON p.category_id = c.id
        $where
        ORDER BY p.created_at DESC
    ");
    $stmt->execute($params);
    $permits = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    $permits = [];
    echo "Error: " . $e->getMessage();
}

// Optional: Fetch all categories for the filter dropdown dynamically
try {
    $catStmt = $pdo->query("SELECT name FROM permits_categories ORDER BY name ASC");
    $categories = $catStmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $categories = [];
}
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Document</title>
    <link rel="stylesheet" href="/Municipality/css/permits.css?v=3" />
    <link rel="stylesheet" href="/Municipality/css/material-symbols.css" />
    <link rel="stylesheet" href="/Municipality/css/style.css" />
  </head>
  <body>
    <main class="permits_page_con">
      <div class="permits_page">
        <!-- HEADER -->
        <section class="permits-header">
          <div class="header-text">
            <h1>
              Digital Permits &
              <span>Licenses</span>
            </h1>
            <p>
              Apply for official building, business, and event permits online.
              Fast, transparent, and paperless.
            </p>
          </div>

          <a href="index.php?page=permits_requests" class="my-permits-btn">
            <span class="material-symbols-outlined">history_edu</span>
            My Permit Requests
          </a>
        </section>

        <!-- SEARCH + FILTER -->
        <form method="GET" action="" class="filters">
            <input type="hidden" name="page" value="permits">
            <div class="search-box">
              <span class="material-symbols-outlined">search</span>
              <input
                type="text"
                name="q"
                placeholder="Search permits (e.g. Construction)"
                value="<?= isset($_GET['q']) ? htmlspecialchars($_GET['q']) : '' ?>"
              />
            </div>

            <div class="select-box">
              <span class="material-symbols-outlined">filter_list</span>
              <select name="category">
                <option value="">All Categories</option>
                <?php foreach ($categories as $cat): ?>
                  <option 
                    value="<?= htmlspecialchars($cat['name']) ?>" 
                    <?= (isset($_GET['category']) && $_GET['category'] == $cat['name']) ? 'selected' : '' ?>
                  >
                    <?= htmlspecialchars($cat['name']) ?>
                  </option>
                <?php endforeach; ?>
              </select>
            </div>

            <button type="submit" class="search-btn">Find Permit</button>

                </form>


        <!-- PERMITS GRID -->
        <section class="permits-grid">
          <!-- CARD -->
         <?php foreach ($permits as $permit): ?>
          <article class="permit-card">
            <div
              class="card-image"
              style="
                background-image: url('<?= !empty($permit['image_url']) ? htmlspecialchars($permit['image_url']) : '/Municipality/images/empty.jpg' ?>');
              "
            >
              <h3> <?= htmlspecialchars($permit['title']) ?></h3>
            </div>
            <div class="card-body">
              <p>
                <?= htmlspecialchars($permit['description']) ?>
              </p>
              <div class="card-actions">
                <button class="primary" ><a href="/Municipality/index.php?page=applyPermit&id=<?php echo $permit['id'] ?>">Apply Now</a></button>
                <button class="secondary">Details</button>
              </div>
            </div>
          </article>
        <?php endforeach; ?>
        </section>
      </div>
    </main>
    <script>
// Remove query parameters from URL without reloading the page
if (window.history.replaceState) {
    const cleanUrl = window.location.protocol + "//" + window.location.host + window.location.pathname + '?page=permits';
    window.history.replaceState(null, null, cleanUrl);
}
    </script>
  </body>
</html>
