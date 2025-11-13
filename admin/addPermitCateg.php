<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
     <link rel="stylesheet" href="/Municipality/css/addPermitCateg.css?v=3">
</head>
<body>
    <div class="serviceCategory_page_con">
  <div class="serviceCategory_page">
    <div class="serviceCategory_header">
      <h1>Add Permit Category</h1>
    </div>
    <div class="serviceCategory_body">
      <div class="serviceCategory_inpts">
        <label for="title">Category Name</label>
        <div class="serviceCategoryInpt">
          <div>
            <input
              type="text"
              placeholder="Enter category name"
              id="title"
              required
              max="50"
            />
            <p class="error">This category already exists.</p>
          </div>
          <button>Add</button>
        </div>
      </div>

      <div class="serviceCategory_issues">
        <h4>Existing Categories</h4>

        <!-- Example repeated items -->
        <div class="serviceCategory_issue">
          <div>
            <p>Water Supply</p>
          </div>
         <img class="serviceCategory_trash_icon" src="/Municipality/images/trash-can.svg">
        </div>
      </div>
    </div>
  </div>
</div>
</body>
</html>











