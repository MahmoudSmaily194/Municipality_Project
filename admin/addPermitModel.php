<?php 
require_once '/xampp/htdocs/Municipality/backend/config/db.php';

try{
 $sql1 = $pdo->query("
        SELECT id,name
        FROM permits_categories 
        ORDER BY created_at DESC
    ");
     $permitsCateg = $sql1->fetchAll(PDO::FETCH_ASSOC);
}
catch (PDOException $e) {
    $permitsCateg = [];
    echo "Error: " . $e->getMessage();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="/Municipality/css/addPermitModel.css?v=9">
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
            <?php if (!empty($permitsCateg)): ?>
          <?php foreach ($permitsCateg as $Permitcateg): ?>
              <option value="<?=$Permitcateg['id'] ?>"><?= $Permitcateg['name'] ?></option>
            <?php endforeach ?>
            <?php endif ?>
          </select>

          <div class="serviceModel_status_btns">
            <input id="active" type="radio" name="active" value="Active" hidden>
            <label class="event_active activeLabel" for="active">Active</label>
            <input id="inactive" type="radio" name="active" value="inActive" hidden>
            <label class="inActiveLabel" for="inactive">Inactive</label>
          </div>

          <div class="serviceModel_upload_photo">
            <div class="news_upload_image" role="button" tabindex="0">
              <span class="remove-image" title="Remove image">✖</span>
          <h3>Upload Image</h3>
          <p>Drag & drop an image here or click to select</p>
          <label for="file">Upload</label>
          <input name="image" type="file" accept="image/*" hidden id="file" required />
      </div>
          </div>
          
        <!-- Add Required Files -->
        <label style="margin-top: 1rem;" for="">Add Requied Files</label>
        <div class="permit_required_files_con">
          <div class="permit_required_files_input">
            <input id="document_name_input" type="text" name="fileName" placeholder="File Name">
            <button type="button" onclick="createRequiredFile()">Add</button>
          </div>
          <div id="permit_required_files_con" class="permit_required_files">
            <!-- <div class="permit_required_file">
              <div class="permit_required_file_name">
                <h5>Name of the file</h5>
              </div>
              <img class="trash_icon" src="/Municipality/images/trash-can.svg">
            </div> -->
          </div>
        </div>
        <!-- Add Required Files -->
        </div>
        <input type="hidden" name="required_files" id="required_files_input">
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
let requiredFiles = [];

const uuid = () =>
  ([1e7]+-1e3+-4e3+-8e3+-1e11).replace(/[018]/g,
    c => (c ^ crypto.getRandomValues(new Uint8Array(1))[0] & 15 >> c / 4).toString(16)
  );

function createRequiredFile() {
  const input = document.getElementById("document_name_input");
  const name = input.value.trim();
  if (!name) return;

  requiredFiles.push({ id: uuid(), name });
  input.value = "";
  renderFiles();
}

function renderFiles() {
  const con = document.getElementById("permit_required_files_con");
  con.innerHTML = "";

  requiredFiles.forEach(f => {
    con.insertAdjacentHTML(
      "beforeend",
      `
      <div class="permit_required_file">
        <div class="permit_required_file_name"><h5>${f.name}</h5></div>
        <img class="trash_icon" src="/Municipality/images/trash-can.svg" onclick="deleteFile('${f.id}')">
      </div>
      `
    );
  });
   document.getElementById("required_files_input").value = JSON.stringify(requiredFiles);
}

function deleteFile(id) {
  requiredFiles = requiredFiles.filter(f => f.id !== id);
  renderFiles();
}

  const input = document.getElementById("file");
  const preview = document.querySelector(".news_upload_image");
  const removeBtn = document.querySelector(".remove-image");

  const title = preview.querySelector("h3");
  const text = preview.querySelector("p");
  const label = preview.querySelector("label");

  input.addEventListener("change", function () {
    const file = this.files[0];
    if (!file) return;

    const reader = new FileReader();

    reader.onload = function () {
      preview.style.backgroundImage = `url('${reader.result}')`;
      preview.style.backgroundSize = "cover";
      preview.style.backgroundPosition = "center";

      title.style.display = "none";
      text.style.display = "none";
      label.style.display = "none";

      removeBtn.style.display = "flex";
    };

    reader.readAsDataURL(file);
  });

  removeBtn.addEventListener("click", function (e) {
    e.stopPropagation(); // مهم

    preview.style.backgroundImage = "none";
    input.value = "";

    title.style.display = "block";
    text.style.display = "block";
    label.style.display = "inline-block";

    removeBtn.style.display = "none";
  });
</script>


</body>
</html>