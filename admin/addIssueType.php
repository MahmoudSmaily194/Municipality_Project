<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
<link rel="stylesheet" href="/Municipality/css/addIssueType.css?v=3">
</head>
<body>
    <div class="issueTypeModal_page_con">
  <div class="issueTypeModal_page">
    <div class="issueTypeModal_header">
      <h1>Add New Issue Type</h1>
    </div>

    <div class="issueTypeModal_body">
      <div class="issueTypeModal_inpts">
        <label for="title">Issue Type Name</label>
        <div class="issuetypeInpt">
          <div>
            <input
              type="text"
              placeholder="Enter issue type name"
              id="title"
              maxlength="50"
              required
            />
            <p class="error">This issue type already exists.</p>
          </div>
          <button>Add Issue Type</button>
        </div>
      </div>

      <div class="issueTypeModal_issues">
        <h4>Existing Issue Types</h4>

        <div class="issueTypeModal_issue">
          <div>
            <p>Garbage Collection</p>
          </div>
         <img class="issueTypeModal_trash_icon" src="/Municipality/images/trash-can.svg">
        </div>
      </div>
    </div>
  </div>

 

</body>
</html>