<?php
if (!defined('IS_ADMIN_PANEL')) {
    header('Location: /Municipality_Project/admin/login.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
          <link rel="stylesheet" href="/Municipality_Project/css/admin_complaints.css">
</head>
<body>
    <div class="ManageComplaints_page_con">
  <div class="ManageComplaints_page">
    <div class="ManageComplaints_header">
      <h1>Complaints Management</h1>
      <button>New Complaint</button>
    </div>
    <p>Here you can view and manage all existing complaints.</p>
    <h3>Existing Complaints</h3>

    <div class="ManageComplaints_table_con">
      <div>
        <table>
          <thead>
            <tr>
              <th>Name</th>
              <th>Date</th>
              <th>Type</th>
              <th>Status</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td style="color: black;">John Doe</td>
              <td>2025-10-31</td>
              <td>Road Issue</td>
              <td>
                <div>
                  <p>Pending</p>
                </div>
              </td>
              <td>
                <div class="ManageCompliants_div_viewBtn">
                  <p>View</p>
                </div>
              </td>
            </tr>
            <!-- Repeat <tr> for each complaint -->
          </tbody>
        </table>
        <div style="height: 20px;"></div>
      </div>
    </div>
  </div>
</div>

</body>
</html>