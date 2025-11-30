<?php
require_once '/xampp/htdocs/Municipality/backend/config/db.php';

try {
    $stmt = $pdo->prepare("
        SELECT 
            pr.id,
            CONCAT(u.first_name, ' ', u.last_name) AS applicant_name,
            p.title AS permit_title,
            c.name AS category,
            pr.status
        FROM permits_requests pr
        LEFT JOIN users u ON pr.user_id = u.id
        LEFT JOIN permits p ON pr.permit_id = p.id
        LEFT JOIN permits_categories c ON p.category_id = c.id
        ORDER BY pr.requested_at DESC
    ");
    $stmt->execute();
    $requests = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    die("Error loading permit requests: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Permit Requests</title>
    <link rel="stylesheet" href="/Municipality/css/permits_requests.css?v=3">
</head>
<body>

<div class="ManageServices_page_con">
  <div class="manageServices_page">

    <div class="manageServices_header">
      <h1>Permit Requests Management</h1>
    </div>

    <p>Below is a list of all requests submitted by users for different types of permits.</p>

    <div class="add_category">
      <h3>Existing Permit Requests</h3>
    </div>

    <div class="manageServices_table_con">
      <div>
        <table>
          <thead>
            <tr>
              <th>Applicant Name</th>
              <th>Permit Name</th>
              <th>Category</th>
              <th>Status</th>
              <th>Actions</th>
            </tr>
          </thead>

          <tbody>
            <?php if (!empty($requests)): ?>
                <?php foreach ($requests as $req): ?>
                    <tr>
                        <td><?= htmlspecialchars($req['applicant_name']) ?></td>
                        <td><?= htmlspecialchars($req['permit_title']) ?></td>
                        <td><?= htmlspecialchars($req['category']) ?></td>
                        <td><?= ucfirst($req['status']) ?></td>
                        <td>
                            <div>
                                <p class="edit_service_btn"><a href="admin.php?page=permitRequestDetails&id=<?php echo($req['id']) ?>">View</a></p>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="5" style="text-align:center;">No permit requests found.</td>
                </tr>
            <?php endif; ?>
          </tbody>

        </table>

        <div style="height: 20px;"></div>
      </div>
    </div>

  </div>
</div>

</body>
</html>
