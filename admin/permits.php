<?php
if (!defined('IS_ADMIN_PANEL')) {
    header('Location: /Municipality/admin/login.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
      <link rel="stylesheet" href="/Municipality/css/admin_permits.css?v=2">
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
      <button>Add Category</button>
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
            <!-- Example static row -->
            <tr>
              <td>Public Lighting Maintenance</td>
              <td>Infrastructure</td>
              <td>Active</td>
              <td>
                <div>
                  <p class="edit_service_btn">Edit</p>
                  <p>|</p>
                  <p class="delete_service_btn">Delete</p>
                </div>
              </td>
            </tr>
          </tbody>
        </table>

        <!-- Loader -->
        <div style="height: 20px;"></div>
      </div>
    </div>
  </div>

  <!-- Delete Dialog placeholder -->
  <div class="delete_row_dialog">
    <!-- DeleteRowDialog content -->
  </div>
</div>

</body>
</html>