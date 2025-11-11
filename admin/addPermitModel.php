<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="/Municipality/css/addPermitModel.css">
</head>
<body>
 <div class="serviceModel_page">
  <div class="serviceModel">
    <div class="serviceModel_header">
      <h1>Add New Permit</h1>
    </div>

    <div class="serviceModel_form_con">
      <form>
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
          <select id="categ" required>
            <option value="">Select a category</option>
            <option value="1">Water Supply</option>
            <option value="2">Waste Management</option>
            <option value="3">Electricity</option>
            <option value="4">Health Services</option>
          </select>

          <div class="serviceModel_status_btns">
            <button type="button" class="active">Active</button>
            <button type="button">Inactive</button>
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


   
</body>
</html>