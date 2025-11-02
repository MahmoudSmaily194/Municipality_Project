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
    <link rel="stylesheet" href="/Municipality/css/admin_events.css?v=2">
</head>
<body>
    <div class="events_control_page_con">
  <div class="events_control_page">
    <div class="events_control_page_header">
      <h1>Events Management</h1>
      <button><a href="admin.php?page=addEventModel">Add Event</a></button>
    </div>

    <p>Add or manage events in the system.</p>

    <h3>Existing Events</h3>

    <div class="events_control_table_con">
      <div>
        <table>
          <thead>
            <tr>
              <th>Title</th>
              <th>Date</th>
              <th>Location</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            <!-- Dynamic event rows would appear here -->
            <tr>
              <td>Sample Event Title</td>
              <td>01/11/2025</td>
              <td>Beirut</td>
              <td>
                <div>
                  <p class="events_edit_btn">View</p>
                  <p>|</p>
                  <p class="events_delete_btn">Delete</p>
                </div>
              </td>
            </tr>
          </tbody>
        </table>

        <!-- Loader Trigger -->
        <div style="height: 20px;"></div>
      </div>
    </div>
  </div>

  <!-- Delete Dialog -->
  <div class="delete_row_dialog">
    <!-- DeleteRowDialog component content goes here -->
  </div>
</div>
</body>
</html>
