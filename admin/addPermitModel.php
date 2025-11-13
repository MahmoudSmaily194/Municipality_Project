<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="/Municipality/css/addPermitModel.css?v=4">
</head>
<body>
 <div class="serviceModel_page">
  <div class="serviceModel">
    <div class="serviceModel_header">
      <h1>Add New Permit</h1>
    </div>

    <div class="serviceModel_form_con">
      <form action="/Municipality/backend/add_permit.php" method="post" enctype="multipart/form-data" >
        <div class="serviceModel_inpts_con">
          <label for="title">Permit Name</label>
          <input
            id="title"
            type="text"
            placeholder="Enter permit name"
            maxlength="150"
            name="title"
            required
          />

          <label for="description">Description</label>
          <textarea
            id="description"
            maxlength="1000"
            placeholder="Enter service description"
            name="description"
            required
          ></textarea>

          <label for="categ">Category</label>
          <select id="categ" name="category_id" required>
            <option value="">Select a category</option>
            <option value="58e3f3fe-c0ae-11f0-9d2c-d481d7fb18a2">Water Supply</option>
            <option value="2">Waste Management</option>
            <option value="3">Electricity</option>
            <option value="4">Health Services</option>
          </select>

          <div class="serviceModel_status_btns">
            <input id="active" type="radio" name="active" value="active" hidden>
            <label class="event_active activeLabel" for="active">Active</label>
            <input id="inactive" type="radio" name="active" value="inActive" hidden>
            <label class="inActiveLabel" for="inactive">Inactive</label>
          </div>

          <div class="serviceModel_upload_photo">
            <div class="news_upload_image" role="button" tabindex="0">
          <h3>Upload Image</h3>
          <p>Drag & drop an image here or click to select</p>
          <label for="file">Upload</label>
          <input name="image" type="file" accept="image/*" hidden id="file" />
      </div>
          </div>
        </div>

        <div class="serviceModel_form_btns">
          <button class="serviceModel_addService_btn" type="submit">
            Add Permit
          </button>
        </div>
      </form>
    </div>
  </div>
</div>


   <script>
   const activeInput = document.getElementById("active");
const inactiveInput = document.getElementById("inactive");
const activeLabel = document.querySelector(".event_active");
const inactiveLabel = document.querySelector(".inActiveLabel");

function updateStatus() {
  if (activeInput.checked) {
    activeLabel.classList.add("active");
    inactiveLabel.classList.remove("active");
  } else if (inactiveInput.checked) {
    inactiveLabel.classList.add("active");
    activeLabel.classList.remove("active");
  }
}

// Add event listeners
activeInput.addEventListener("change", updateStatus);
inactiveInput.addEventListener("change", updateStatus);

// Initialize on page load
updateStatus();

   </script>
</body>
</html>