<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
      <link rel="stylesheet" href="/Municipality/css/addEventModel.css?v=2">
</head>
<body>
    <div class="eventModel_page">
  <div class="eventModel">
    <div class="eventModel_header">
      <h1>Add Event</h1>
    </div>

    <div class="eventModel_form_con">
      <form method="post" enctype="multipart/form-data" action="/Municipality/backend/add_events.php">
        <div class="eventModel_inpts_con">
          <div class="eventModel_form_title_date_inpts">
            <div class="event_title_inp_div">
              <input
                id="title"
                type="text"
                placeholder="Enter title"
                name ="title"
                maxlength="150"
                required
              />
            </div>

            <div>
              <input
                type="datetime-local"
                id="eventDate"
                name="eventDate"
                required
              />
            </div>
          </div>

          <label for="descri">
           Description
          </label>
          <textarea
            id="descri"
            maxlength="1000"
            placeholder="Enter description"
            name="description"
            required
          ></textarea>

          <label for="loca">
         Location
          </label>
          <input
            id="loca"
            type="text"
            placeholder="Location"
            name="location"
            maxlength="200"
          />

          <div class="eventModel_upload_photo">
            
        <div class="news_upload_image" role="button" tabindex="0">
          <h3>Upload Image</h3>
          <p>Drag & drop an image here or click to select</p>
          <label for="file">Upload</label>
          <input name="imageUpload" type="file" accept="image/*" hidden id="file" />
      </div>
          </div>
        </div>

        <div class="eventModel_form_btns">
          <button class="eventModel_addEvent_btn" type="submit">
         Submit
          </button>
          <button>Close
          </button>
        </div>
      </form>
    </div>
  </div>
</div>



</body>
</html>