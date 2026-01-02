
<?php
require_once '/xampp/htdocs/Municipality/backend/config/db.php';
include '/xampp/htdocs/Municipality/includes/loader.php';
if (!defined('IS_ADMIN_PANEL')) {
  header('Location: /Municipality/admin/login.php');
  exit();
}

// Fetch all complaints
$stmt = $pdo->query("
    SELECT 
        c.id, 
        c.description, 
        c.status, 
        c.created_at, 
        c.image_url, 
        CONCAT_WS(' ', u.first_name, u.last_name) AS user_name, 
        ci.issue_name AS issue_type
    FROM complaints c
    LEFT JOIN users u ON c.created_by = u.id
    LEFT JOIN complaint_issues ci ON c.issue_id = ci.id
    ORDER BY c.created_at DESC
");
$complaints = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Complaints Management</title>
    <link rel="stylesheet" href="/Municipality/css/admin_complaints.css?v=4">
    <link rel="stylesheet" href="/Municipality/css/loader.css">
<script src="/Municipality/includes/loader.js"></script>
</head>
<body>
    <div class="ManageComplaints_page_con">
        <div class="ManageComplaints_page">
            <div class="ManageComplaints_header">
                <h1>Complaints Management</h1>
                <button><a href="admin.php?page=addIssueType">Add Issue Type</a></button>
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
                            <?php foreach ($complaints as $complaint): ?>
                                <tr>
                                    <td style="color: black;"><?= htmlspecialchars(
                                      $complaint['user_name'] ?? 'Unknown',
                                    ) ?></td>
                                    <td><?= htmlspecialchars(
                                      date('Y-m-d', strtotime($complaint['created_at'])),
                                    ) ?></td>
                                    <td><?= htmlspecialchars(
                                      $complaint['issue_type'] ?? 'N/A',
                                    ) ?></td>
                                    <td>
                                        <div>
                                            <p><?= ucfirst(
                                              str_replace('_', ' ', $complaint['status']),
                                            ) ?></p>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="ManageCompliants_div_viewBtn" data-href="">
                                            <a href="admin.php?page=complaintDetails&id=<?= urlencode($complaint['id']) ?>">View</a>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                    <div style="height: 20px;"></div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
