<?php
require_once '/xampp/htdocs/Municipality/backend/config/db.php';
try {
  $sql = $pdo->query("
    SELECT id, issue_name
    FROM complaint_issues
    ORDER BY created_at DESC
  ");
  $issueTypes = $sql->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
  $issueTypes = [];
}

include '/xampp/htdocs/Municipality/includes/toast.php';
?>
<!DOCTYPE html>
<html lang="en" class="light">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Report Form - Smart Municipality</title>

  <!-- Fonts -->
  <link href="https://fonts.googleapis.com/css2?family=Public+Sans:wght@100..900&display=swap" rel="stylesheet" />
  <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet" />

  <!-- Leaflet -->
  <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />

  <!-- CSS -->
  <link rel="stylesheet" href="/Municipality/css/report.css">
  <link rel="stylesheet" href="/Municipality/css/toast.css">

  <script src="/Municipality/includes/toast.js"></script>
</head>

<body>
<div class="app">

  <main class="container">
    <section class="card">

      <!-- Header -->
      <header class="card-header">
        <h1>Report <span>Form</span></h1>
        <p>Submit a new request to the municipality to improve our city.</p>
      </header>

      <form id="reportForm" class="card-body">

        <!-- Issue Type -->
        <div class="form-group">
          <label>Issue Type</label>
          <div class="select-wrapper">
            <select name="issueType" required>
              <option value="" disabled selected>Select an issue type</option>
              <?php foreach ($issueTypes as $issue): ?>
                <option value="<?= $issue['id'] ?>">
                  <?= htmlspecialchars($issue['issue_name']) ?>
                </option>
              <?php endforeach; ?>
            </select>
            <span class="material-symbols-outlined">expand_more</span>
          </div>
        </div>

        <!-- Description -->
        <div class="form-group">
          <label>Description</label>
          <textarea
            name="description"
            placeholder="Describe your complaint in detail..."
            maxlength="1000"
            required></textarea>
        </div>

        <!-- Upload -->
        <div class="form-group">
          <label>Upload Image <span>(optional)</span></label>

          <div class="upload-box uploadImg_con">
            <input type="file" id="imageUpload" name="imageUpload" accept="image/*" />
            <span class="remove-image">✖</span>

            <div class="upload-content">
              <label for="imageUpload" class="material-symbols-outlined upload-icon">cloud_upload</label>
              <p class="upload-title">Click to upload or drag and drop</p>
              <p class="upload-hint">PNG, JPG up to 5MB</p>
            </div>
          </div>
        </div>

        <!-- Location -->
        <div class="form-group">
          <label>Location</label>

          <div id="map" class="map"></div>

          <div class="map-buttons">
            <button type="button" id="useLocationBtn" class="primary-soft">
              <span class="material-symbols-outlined">my_location</span>
              Use My Location
            </button>
            <button type="button" id="saveLocationBtn" class="secondary">
              <span class="material-symbols-outlined">bookmark</span>
              Save Location
            </button>
          </div>
        </div>

        <!-- Submit -->
        <footer class="card-footer">
          <button class="submit" type="submit">
            Submit Report
            <span class="material-symbols-outlined">send</span>
          </button>

          <p class="legal">
            By submitting, you agree to the
            <a href="#">Terms of Service</a> and
            <a href="#">Privacy Policy</a>.
          </p>
        </footer>

      </form>
    </section>
  </main>

</div>

<!-- Leaflet JS -->
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<script>
/* =======================
   MAP LOGIC
======================= */
const map = L.map('map').setView([33.6863, 35.909], 13);

L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
  attribution: '&copy; OpenStreetMap contributors'
}).addTo(map);

delete L.Icon.Default.prototype._getIconUrl;
L.Icon.Default.mergeOptions({
  iconRetinaUrl: 'https://unpkg.com/leaflet@1.9.4/dist/images/marker-icon-2x.png',
  iconUrl: 'https://unpkg.com/leaflet@1.9.4/dist/images/marker-icon.png',
  shadowUrl: 'https://unpkg.com/leaflet@1.9.4/dist/images/marker-shadow.png',
});

let marker = null;
let selectedLocation = null;

map.on('click', e => {
  selectedLocation = [e.latlng.lat, e.latlng.lng];
  if (marker) marker.setLatLng(selectedLocation);
  else marker = L.marker(selectedLocation).addTo(map);
});

document.getElementById('useLocationBtn').onclick = () => {
  navigator.geolocation.getCurrentPosition(pos => {
    selectedLocation = [pos.coords.latitude, pos.coords.longitude];
    map.setView(selectedLocation, 13);
    if (marker) marker.setLatLng(selectedLocation);
    else marker = L.marker(selectedLocation).addTo(map);
  });
};

document.getElementById('saveLocationBtn').onclick = () => {
  if (!selectedLocation) {
    openToast('Please select a location first.', '#fee2e2', '#991b1b');
    return;
  }
  openToast('Location saved successfully.', '#22c55e', '#ffffff');
};

/* =======================
   IMAGE PREVIEW
======================= */
const input = document.getElementById("imageUpload");
const preview = document.querySelector(".uploadImg_con");
const removeBtn = document.querySelector(".remove-image");
const uploadIcon = document.querySelector(".upload-icon");
const uploadTitle = document.querySelector(".upload-title");
const uploadHint = document.querySelector(".upload-hint");

input.addEventListener("change", function () {
  const file = this.files[0];
  if (!file) return;

  const reader = new FileReader();
  reader.onload = () => {
    preview.style.backgroundImage = `url('${reader.result}')`;
    preview.classList.add("has-image");
    removeBtn.style.display = "flex";
    uploadIcon.style.display="none";
    uploadTitle.style.display="none";
    uploadHint.style.display="none";
  };
  reader.readAsDataURL(file);
});

removeBtn.onclick = e => {
  e.stopPropagation();
  preview.style.backgroundImage = "none";
  preview.classList.remove("has-image");
  input.value = "";
  removeBtn.style.display = "none";
  uploadIcon.style.display="inline-block";
  uploadTitle.style.display="block";
  uploadHint.style.display="block";
};

/* =======================
   FORM SUBMIT
======================= */
document.getElementById('reportForm').addEventListener('submit', async e => {
  e.preventDefault();

  if (!selectedLocation) {
    openToast('Please select a location.', '#fee2e2', '#991b1b');
    return;
  }

  const formData = new FormData(e.target);
  formData.append('latitude', selectedLocation[0]);
  formData.append('longitude', selectedLocation[1]);

  const res = await fetch('/Municipality/backend/submit_report.php', {
    method: 'POST',
    body: formData
  });

  const result = await res.json();

  if (result.success) {
    openToast(result.message, '#22c55e', '#ffffff');
    e.target.reset();
    preview.style.backgroundImage = "none";
    preview.classList.remove("has-image");
    input.value = "";
    removeBtn.style.display = "none";
    uploadIcon.style.display="inline-block";
    uploadTitle.style.display="block";
    uploadHint.style.display="block";
    if (marker) map.removeLayer(marker);
    selectedLocation = null;
  } else {
    openToast(result.message, '#fee2e2', '#991b1b');
  }
});

</script>

</body>
</html>
