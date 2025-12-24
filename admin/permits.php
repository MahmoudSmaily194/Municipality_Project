<?php
if (!defined('IS_ADMIN_PANEL')) {
    header('Location: /Municipality/admin/login.php');
    exit;
}
require_once '/xampp/htdocs/Municipality/backend/config/db.php';
include '/xampp/htdocs/Municipality/includes/delete_modal.php';
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
    <link rel="stylesheet" href="/Municipality/css/admin_permits.css?v=4">
    <link rel="stylesheet" href="/Municipality/css/deleteDialog.css?v=3">
    <script src="/Municipality/includes/delete_modal.js"></script>
</head>
<body>
<div class="ManageServices_page_con">
  <div class="manageServices_page">
    <div class="manageServices_header">
      <h1>Permits Management</h1>
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
                        <td><p class="<?= ucfirst($permit['status']) ?>"><?= ucfirst($permit['status']) ?></p></td>
                        <td>
                            <div>
                                <p class="edit_service_btn">Edit</p>
                                <p>|</p>
                                <p class="delete_service_btn" onclick="openDeleteModal('/Municipality/backend/delete_permit.php', '<?= htmlspecialchars($permit['id'], ENT_QUOTES) ?>')">Delete</p>
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
