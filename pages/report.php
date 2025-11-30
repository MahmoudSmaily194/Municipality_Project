<?php
require_once '/xampp/htdocs/Municipality/backend/config/db.php';
  if (!defined('IS_LOGGEDIN')) {
      header('Location: /Municipality/admin/login.php');
      exit;
  }
  try{
 $sql1 = $pdo->query("
        SELECT id,issue_name
        FROM complaint_issues 
        ORDER BY created_at DESC
        ");
     $issueTypes= $sql1->fetchAll(PDO::FETCH_ASSOC);
}
catch (PDOException $e) {
    $issueTypes = [];
    echo "Error: " . $e->getMessage();
}

 ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Report Form</title>
  <link
    rel="stylesheet"
    href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
  />
  <link rel="stylesheet" href="/Municipality/css/report.css?v=2">
  <style>
    /* Quick CSS for layout */
    .loca_btns {
      display: flex;
      gap: 1rem;
      margin-top: 0.5rem;
    }
    .mylocation_btn {
      flex: 1;
      padding: 0.5rem;
      cursor: pointer;
    }
    .submit_btn {
      margin-top: 1rem;
      padding: 0.7rem;
      width: 100%;
      cursor: pointer;
    }
    #map {
      width: 100%;
      height: 300px;
      margin-top: 1rem;
    }
  </style>
</head>
<body>
  <div class="report_page_con">
    <div class="report_page">
      <h1>Report Form</h1>
      <div class="report_form">
        <form id="reportForm">
          <!-- Issue Type -->
          <label for="issueType">Issue Type</label>
          <div class="form-control">
            <select id="issueType" name="issueType" required>
              <option value="">Select issue type</option>
                <?php if (!empty($issueTypes)): ?>
                <?php foreach ($issueTypes as $issueType): ?>
                <option value="<?php $issueType['id'] ?> "><?= $issueType['issue_name'] ?></option>
                <?php endforeach ?>
                <?php endif ?>
            </select>
          </div>

          <!-- Description -->
          <label for="description">Description</label>
          <textarea
            id="description"
            name="description"
            placeholder="Describe your complaint..."
            maxlength="1000"
            required
          ></textarea>

          <!-- Upload Image -->
          <div class="uploadImg_con">
            <label for="imageUpload">Upload Image</label>
            <input type="file" id="imageUpload" name="imageUpload" accept="image/*" hidden/>
          </div>

          <!-- Location Selector Map -->
          <div class="location_selector">
            <div id="map"></div>
            <div class="loca_btns">
              <button type="button" id="useLocationBtn" class="mylocation_btn">Use My Location</button>
              <button type="button" id="saveLocationBtn" class="mylocation_btn">Save Location</button>
            </div>
          </div>

          <!-- Submit Button -->
          <button class="submit_btn" type="submit">Submit</button>
        </form>
      </div>
    </div>
  </div>

  <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
  <script>
    // Initialize map
    const map = L.map('map').setView([33.6863, 35.909], 13);

    // Add OpenStreetMap tiles
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
      attribution: '&copy; OpenStreetMap contributors'
    }).addTo(map);

    // Fix default marker icon
    delete L.Icon.Default.prototype._getIconUrl;
    L.Icon.Default.mergeOptions({
      iconRetinaUrl: 'https://unpkg.com/leaflet@1.9.4/dist/images/marker-icon-2x.png',
      iconUrl: 'https://unpkg.com/leaflet@1.9.4/dist/images/marker-icon.png',
      shadowUrl: 'https://unpkg.com/leaflet@1.9.4/dist/images/marker-shadow.png',
    });

    let marker = null;
    let selectedLocation = null;

    // Click on map to set marker
    map.on('click', function(e) {
      selectedLocation = [e.latlng.lat, e.latlng.lng];
      if (marker) {
        marker.setLatLng(selectedLocation);
      } else {
        marker = L.marker(selectedLocation).addTo(map);
      }
    });

    // Use My Location button
    document.getElementById('useLocationBtn').addEventListener('click', () => {
      if (!navigator.geolocation) {
        alert('Geolocation is not supported by your browser.');
        return;
      }
      navigator.geolocation.getCurrentPosition(
        (position) => {
          selectedLocation = [position.coords.latitude, position.coords.longitude];
          map.setView(selectedLocation, 13);
          if (marker) {
            marker.setLatLng(selectedLocation);
          } else {
            marker = L.marker(selectedLocation).addTo(map);
          }
        },
        (err) => {
          console.error(err);
          alert('Unable to retrieve your location.');
        }
      );
    });

    // Save Location button
    document.getElementById('saveLocationBtn').addEventListener('click', () => {
      if (!selectedLocation) {
        alert('Please select a location first.');
        return;
      }
      alert('Location saved: ' + selectedLocation.join(', '));
    });

    // Submit form via AJAX
    document.getElementById('reportForm').addEventListener('submit', async (e) => {
      e.preventDefault();

      if (!selectedLocation) {
        alert('Please select a location on the map.');
        return;
      }

      const form = e.target;
      const formData = new FormData(form);
      formData.append('latitude', selectedLocation[0]);
      formData.append('longitude', selectedLocation[1]);

      try {
        const response = await fetch('/Municipality/backend/submit_report.php', {
          method: 'POST',
          body: formData
        });

        const result = await response.json();

        if (result.success) {
          alert(result.message);
          form.reset();
          if (marker) map.removeLayer(marker);
          selectedLocation = null;
        } else {
          alert('Error: ' + result.message);
        }

      } catch (err) {
        console.error(err);
        alert('An error occurred while submitting the complaint.');
      }
    });
  </script>
  <script>
    
  </script>
</body>
</html>
