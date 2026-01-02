
<?php

require_once '/xampp/htdocs/Municipality/backend/config/db.php';
include '/xampp/htdocs/Municipality/includes/toast.php';
include '/xampp/htdocs/Municipality/includes/loader.php';
/* ---------------------------------
   1. Validate complaint ID
--------------------------------- */
$complaintId = $_GET['id'] ?? null;
if (!$complaintId) {
  exit('Invalid complaint ID');
}
/* ---------------------------------
   2. Fetch complaint data
--------------------------------- */
$stmt = $pdo->prepare("
  SELECT
    c.*,
    ci.issue_name,
    CONCAT(u.first_name, ' ', u.last_name) AS reporter
  FROM complaints c
  LEFT JOIN complaint_issues ci ON c.issue_id = ci.id
  LEFT JOIN users u ON c.created_by = u.id
  WHERE c.id = ?
");
$stmt->execute([$complaintId]);
$complaint = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$complaint) {
  exit('Complaint not found');
}


?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Complaint Details</title>
<link rel="stylesheet" href="/Municipality/css/complaintDetails.css">
<link rel="stylesheet" href="/Municipality/css/material-symbols.css">
<link rel="stylesheet" href="/Municipality/css/toast.css?v=4">
<link rel="stylesheet" href="/Municipality/css/loader.css">
<script src="/Municipality/includes/loader.js"></script>
<script src="/Municipality/includes/toast.js"></script>

</head>
<body>
<main class="container">
<div class="complaintDetails_page">

    <!-- HEADER -->
    <section class="page-header">
        <div>
            <div class="page-header-title">
                <h1><?= htmlspecialchars($complaint['issue_name']) ?></h1>
                <span class="badge  <?= ucfirst($complaint['status']) ?>">
                    <span class="material-symbols-outlined">radio_button_checked</span>
                    <?= ucfirst(str_replace('_', ' ', $complaint['status'])) ?>
                </span>
            </div>
            <p class="meta">
                <span class="material-symbols-outlined">calendar_today</span>
                Created <?= date('M d, Y', strtotime($complaint['created_at'])) ?>
            </p>
        </div>
    </section>

    <!-- CONTENT -->
    <section class="content">

        <!-- LEFT -->
        <div class="left">

            <!-- DESCRIPTION -->
            <div class="description_card">
                <div class="description_card_text">
                    <h3>Issue Description</h3>
                    <p><?= nl2br(htmlspecialchars($complaint['description'])) ?></p>
                </div>
                <div class="description_card_user">
                    <img src="/Municipality/images/userImg.png" alt="Reporter">
                    <div>
                        <h5><?= htmlspecialchars($complaint['reporter'] ?? 'Unknown') ?></h5>
                        <p>Citizen Reporter</p>
                    </div>
                </div>
            </div>

            <!-- MEDIA -->
            <div class="media">

                <div class="image-card">
                    <h4>Attached Evidence</h4>
                    <?php if ($complaint['image_url']): ?>
                        <a href="<?= htmlspecialchars(
                          $complaint['image_url'],
                        ) ?>" download>Download</a>
                    <?php else: ?>
                        <a>No attachment</a>
                    <?php endif; ?>
                </div>

                <div class="map-card">
                    <h4><span class="material-symbols-outlined">location_on</span> Location</h4>
                    <?php if ($complaint['latitude'] && $complaint['longitude']): ?>
                        <a href="https://www.google.com/maps?q=<?= $complaint[
                          'latitude'
                        ] ?>,<?= $complaint['longitude'] ?>" target="_blank">
                            Open Maps
                        </a>
                    <?php else: ?>
                        <span>No location provided</span>
                    <?php endif; ?>
                </div>

            </div>
      <div class="ai-button_con">
      <button onclick="loadComplaintAndAI('<?= $complaintId ?>');" class="ai-button">
        <span class="material-symbols-outlined ai-icon">
          auto_awesome
        </span>
        Use AI Recommendations
      </button>
      </div>
  <div class="card"></div>
        </div>

        <!-- RIGHT -->
        <div class="right">
            <form method="POST" action="/Municipality/backend/updateComplaint.php" class="update_card_con">
                <div class="update-card-header">
                    <h4>
                        <span class="material-symbols-outlined">edit_square</span>
                        Update Ticket
                    </h4>
                </div>

                <div class="update_card">
                     <input type="text" hidden value=<?= $complaint['id'] ?> name="id" />
                    <!-- STATUS --> 
                    <div class="status">
                        <label>Current Status</label>
                        <select name="status">
                            <?php
                            $statuses = ['pending', 'in_progress', 'completed', 'rejected'];
                            foreach ($statuses as $statusOption): ?>
                                <option value="<?= $statusOption ?>" <?= $complaint['status'] ===
                                          $statusOption
                                            ? 'selected'
                                            : '' ?>>
                                    <?= ucfirst(str_replace('_', ' ', $statusOption)) ?>
                                </option>
                            <?php endforeach;
                            ?>
                        </select>
                    </div>

                    <!-- VISIBILITY -->
                    <div class="visibility">
                        <label>Public visibility</label>
                        <label class="switch">
                            <input type="checkbox" name="visibility" <?= $complaint[
                              'visibility'
                            ] === 'visible'
                              ? 'checked'
                              : '' ?>>
                            <span class="slider"></span>
                        </label>
                    </div>

                    <!-- INTERNAL NOTE -->
                    <textarea name="note" placeholder="Add internal note"></textarea>

                    <!-- SUBMIT -->
                    <button type="submit" class="btn-primary full">Save Changes</button>

                </div>
            </form>
        </div>

    </section>

</div>
</main>
<script>
    function ucfirst(str) {
    if (!str) return '';
    return str.charAt(0).toUpperCase() + str.slice(1);
}
    // ================== submit form =================
  document.querySelector(".update_card_con").addEventListener("submit", async(e)=>{
   e.preventDefault();
   const form =e.target;
   const formData = new FormData(form);
   try{
   const response= await fetch('/Municipality/backend/updateComplaint.php',{
      method: 'POST',
      body: formData
    });
    const result= await response.json();
    if (result.success) 
   { 
    const complaint = result.complaint;
      // ----------------- Update status badge -----------------
      const badge = document.querySelector('.page-header .badge');
      badge.className = 'badge ' + ucfirst(complaint.status);
      badge.innerHTML = `<span class="material-symbols-outlined">radio_button_checked</span> ${complaint.status.replace('_', ' ')}`;

      openToast(result.message,"#22c55e","#ffffff");
    } else {
      openToast('Error: ' + result.message,"#fee2e2","#991b1b");
    }
   }
   catch (err) {
    console.error(err);
    openToast('An error occurred while updating the complaint.',"#fee2e2","#991b1b");
  }
  });
</script>
</body>
</html>
<script>
async function loadComplaintAndAI(id) {
  const card = document.querySelector('.card');
  card.classList.add("magic-skeleton");
  const btn =document.querySelector(".ai-button");
  btn.disabled = true;
  try {
    const res = await fetch(`/Municipality/backend/useComplaintAI.php?id=${id}`);
    const data = await res.json();

    

    if (data.success) {
      const complaint = data.complaint;
      const ai = data.aiAdvice;

      // Generate the full card HTML dynamically
      card.innerHTML = `
        <!-- Header -->
        <div class="card-header">
          <div class="header-left">
            <span class="material-symbols-outlined icon-primary">auto_awesome</span>
            <h2>AI Recommendations</h2>
          </div>
          <div class="confidence">${ai.confidence} Confidence</div>
        </div>

        <!-- Body -->
        <div class="card-body">

          <button class="item">
            <div class="item-left">
              <div class="item-icon">
                <span class="material-symbols-outlined">check_circle</span>
              </div>
              <div class="m">
                <div class="item-label">Issue Match</div>
                <div class="item-value">${ai.issue_match}</div>
              </div>
            </div>
            <span class="material-symbols-outlined arrow">arrow_forward_ios</span>
          </button>

          <button class="item">
            <div class="item-left">
              <div class="item-icon">
                <span class="material-symbols-outlined">hourglass_top</span>
              </div>
              <div class="m">
                <div class="item-label">Suggested Status</div>
                <span class="pill pill-amber"><span class="dot"></span>${ai.suggested_status}</span>
              </div>
            </div>
            <span class="material-symbols-outlined arrow">arrow_forward_ios</span>
          </button>

          <button class="item">
            <div class="item-left">
              <div class="item-icon">
                <span class="material-symbols-outlined">priority_high</span>
              </div>
              <div class="m">
                <div class="item-label">Suggested Importance</div>
                <span class="pill pill-red"><span class="dot"></span>${ai.suggested_importance}</span>
              </div>
            </div>
            <span class="material-symbols-outlined arrow">arrow_forward_ios</span>
          </button>

          <div class="item">
            <div class="item-left">
              <div class="item-icon">
                <span class="material-symbols-outlined">comment</span>
              </div>
              <div class="m">
                <div class="item-label">Reason</div>
                <span>${ai.reason}</span>
              </div>
            </div>
            <span class="material-symbols-outlined arrow">arrow_forward_ios</span>
          </div>

        </div>

        <!-- Footer -->
        <div class="card-footer">
          <div class="footer-content">
            <span class="material-symbols-outlined icon-primary">lightbulb</span>
            <p>Based on text analysis: Complaint describes a dangerous pothole affecting multiple lanes during rush hour traffic.</p>
          </div>
        </div>
      `;
        card.classList.remove("magic-skeleton");
  btn.style.display="none";
    } else {
      card.innerHTML = `<p style="color:red;">Failed to load AI advice: ${data.message}</p>`;
    }
  } catch (err) {
    console.error(err);
    card.innerHTML = `<p style="color:red;">An error occurred while loading AI advice.</p>`;
  }

}


</script>