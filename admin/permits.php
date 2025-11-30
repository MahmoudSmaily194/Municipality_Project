<?php
if (!defined('IS_ADMIN_PANEL')) {
    header('Location: /Municipality/admin/login.php');
    exit;
}
require_once '/xampp/htdocs/Municipality/backend/config/db.php';
try {
    $stmt = $pdo->query("
        SELECT p.id, p.title, p.status, c.name AS category
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
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="/Municipality/css/admin_permits.css?v=3">
</head>
<body>
<div class="ManageServices_page_con">
  <div class="manageServices_page">
    <div class="manageServices_header">
      <h1>Services Management</h1>
      <button><a href="admin.php?page=addPermitModel">Add Permit</a></button>
    </div>

    <p>Add or manage permits in the system.</p>

    <div class="add_category">
      <h3>Existing Permits</h3>
      <button><a href="admin.php?page=addPermitCateg">Add Category</a></button>
    </div>

    <div class="manageServices_table_con">
      <div>
        <table>
          <thead>
            <tr>
              <th>Permit Name</th>
              <th>Category</th>
              <th>Status</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            <?php if (!empty($permits)): ?>
                <?php foreach ($permits as $permit): ?>
                    <tr>
                        <td><?= htmlspecialchars($permit['title']) ?></td>
                        <td><?= htmlspecialchars($permit['category'] ?? 'Uncategorized') ?></td>
                        <td><?= ucfirst($permit['status']) ?></td>
                        <td>
                            <div>
                                <p class="edit_service_btn">Edit</p>
                                <p>|</p>
                                <p class="delete_service_btn"><a href="/Municipality/backend/delete_permit.php?id=<?php echo $permit['id'];?>">Delete</a></p>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="4" style="text-align:center;">No permits found.</td>
                </tr>
            <?php endif; ?>
          </tbody>
        </table>

        <!-- Loader -->
        <div style="height: 20px;"></div>
      </div>
    </div>
  </div>
</div>
</body>
</html>
