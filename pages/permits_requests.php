<?php
require_once __DIR__ . '/../backend/citizan_fetch_permitsRequests.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<title>My Permit Requests - City Portal</title>
<link rel="stylesheet" href="/Municipality/css/citizen_permits_requests.css?v=2" />
</head>
<body>

<div class="container">
  <h1>My Permit Requests</h1>
  <p>Track and manage your submitted municipality permit applications.</p>

<form action="index.php" method="get" class="search-box">
    <input type="hidden" name="page" value="permits_requests">
    <input 
      type="text" 
      name="search"  
      class="search-input" 
      placeholder="Search permits..."
      oninput="this.form.submit()"
      value="<?= htmlspecialchars($_GET['search'] ?? '') ?>" 
    />

   <select name="status" onchange="this.form.submit()">
  <option value="" <?= empty($_GET['status']) ? 'selected' : '' ?>>Status: All</option>
  <option value="pending"    <?= ($_GET['status'] ?? '') === 'pending' ? 'selected' : '' ?>>Pending</option>
  <option value="in_progress" <?= ($_GET['status'] ?? '') === 'in_progress' ? 'selected' : '' ?>>In Progress</option>
  <option value="completed"   <?= ($_GET['status'] ?? '') === 'completed' ? 'selected' : '' ?>>Completed</option>
  <option value="rejected"    <?= ($_GET['status'] ?? '') === 'rejected' ? 'selected' : '' ?>>Rejected</option>
</select>


   <select name="sort" onchange="this.form.submit()">
  <option value="newest" <?= (($_GET['sort'] ?? '') === 'newest' || empty($_GET['sort'])) ? 'selected' : '' ?>>
    Sort: Newest
  </option>
  <option value="oldest" <?= ($_GET['sort'] ?? '') === 'oldest' ? 'selected' : '' ?>>
    Oldest
  </option>
</select>

  </form>

  <table>
    <thead>
      <tr>
        <th>Permit Title</th>
        <th>Category</th>
        <th>Status</th>
        <th>Priority</th>
        <th>Requested Date</th>
        <th>Last Update</th>
        <th>Action</th>
      </tr>
    </thead>
    <tbody>

      <?php if (!empty($requests)): ?>
      <?php foreach ($requests as $permit): ?>
      <tr>
        <td><?= htmlspecialchars($permit['permit_title']) ?></td>
        <td><?= htmlspecialchars($permit['category'] ?? 'Uncategorized') ?></td>
        
        <td>
          <span class="badge <?= htmlspecialchars($permit['status']) ?>">
            <?= ucfirst($permit['status']) ?>
          </span>
        </td>

        <td>
          <div class="priority-circle">
            <?= htmlspecialchars($permit['priority'] ?? '-') ?>
          </div>
        </td>

        <td><?= htmlspecialchars($permit['requested_at']) ?></td>
        <td><?= htmlspecialchars($permit['completed_at'] ?? '-') ?></td>

        <td>
          <a class="view-btn"
             href="/Municipality/index.php?page=citizan_permitRequest_details&id=<?= $permit['id'] ?>">
             View Details
          </a>
        </td>
      </tr>
      <?php endforeach; ?>
      
      <?php else: ?>
      <tr>
        <td colspan="7" style="text-align:center;">No permit requests found.</td>
      </tr>
      <?php endif; ?>

    </tbody>
  </table>
</div>

<script>
window.addEventListener("load", () => {
  if (window.innerWidth <= 640) {
    const headers = Array.from(document.querySelectorAll("thead th")).map(h => h.textContent);
    document.querySelectorAll("tbody tr").forEach(row => {
      row.querySelectorAll("td").forEach((cell, i) => {
        cell.setAttribute("data-label", headers[i]);
      });
    });
  }
});
</script>

</body>
</html>
