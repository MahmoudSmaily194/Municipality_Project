<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Document</title>
    <link rel="stylesheet" href="/Municipality/css/contact.css?v=3" />
      <link
    rel="stylesheet"
    href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
  />

  <style>
  

  
  </style>
  </head>
  <body>
    <div class="contact_page_con">
      <div class="contact_page">
        <div class="contact_page_sec1">
          <div class="contact_page_header">
            <h1>Contact Us</h1>
            <p>
              We're here to help. Reach out to us with any questions or
              concerns.
            </p>
          </div>
          <div class="contact_generalInquiries_con">
            <div class="generalInquiries_header">
              <h2>General Inquiries</h2>
              <p>
                For general inquiries, please contact us using the information
                below:
              </p>
            </div>
            <div class="generalInquiries">
              <div class="inquiry">
                <h3>Address</h3>
                <p>Main Street, Willow Creek, CA 91234</p>
              </div>
              <div class="inquiry">
                <h3>Phone</h3>
                <p>(555) 123-4567 123</p>
              </div>
              <div class="inquiry">
                <h3>Email</h3>
                <p>info@willowcreektown.gov</p>
              </div>
            </div>
          </div>
        </div>
        <div class="councel_members_con">
          <h3>Council Members</h3>
          <div class="councel_members">
            <div>
              <img src="/Municipality/images/mahmoud.jpg" alt="" />
              <p>Member</p>
            </div>
            <div>
              <img src="/Municipality/images/mahmoud.jpg" alt="" />
              <p>Member</p>
            </div>
            <div>
              <img src="/Municipality/images/mahmoud.jpg" alt="" />
              <p>Member</p>
            </div>
            <div>
              <img src="/Municipality/images/mahmoud.jpg" alt="" />
              <p>Member</p>
            </div>
            <div>
              <img src="/Municipality/images/mahmoud.jpg" alt="" />
              <p>Member</p>
            </div>
            <div>
              <img src="/Municipality/images/mahmoud.jpg" alt="" />
              <p>Member</p>
            </div>
            <div>
              <img src="/Municipality/images/mahmoud.jpg" alt="" />
              <p>Member</p>
            </div>
            <div>
              <img src="/Municipality/images/mahmoud.jpg" alt="" />
              <p>Member</p>
            </div>
            <div>
              <img src="/Municipality/images/mahmoud.jpg" alt="" />
              <p>Member</p>
            </div>
            <div>
              <img src="/Municipality/images/mahmoud.jpg" alt="" />
              <p>Member</p>
            </div>
            <div>
              <img src="/Municipality/images/mahmoud.jpg" alt="" />
              <p>Member</p>
            </div>
            <div>
              <img src="/Municipality/images/mahmoud.jpg" alt="" />
              <p>Member</p>
            </div>
            <div>
              <img src="/Municipality/images/mahmoud.jpg" alt="" />
              <p>Member</p>
            </div>
            <div>
              <img src="/Municipality/images/mahmoud.jpg" alt="" />
              <p>Member</p>
            </div>
            <div>
              <img src="/Municipality/images/mahmoud.jpg" alt="" />
              <p>Member</p>
            </div>
          </div>
        </div>
         <div class="map-container">
            <h2>Visit Us</h2>
    <div id="map"></div>
    <button class="btn-direction" onclick="openGoogleMapsDirections()">
      Get Directions 🧭
    </button>
  </div>
   <div class="contact_form">
        <h2>Contact Us</h2>

        <form>
          <label for="name">Full Name</label>
          <input
            id="name"
            type="text"
            placeholder="Enter your full name"
            required
            maxLength="100"
          />

          <label for="email">Email Address</label>
          <input
            id="email"
            type="email"
            placeholder="Enter your email address"
            maxLength="60"
          />

          <label for="subject">Subject</label>
          <input
            id="subject"
            type="text"
            placeholder="Subject of your message"
            required
            maxLength="300"
          />

          <label for="body">Message</label>
          <textarea
            id="body"
            placeholder="Write your message here..."
            required
            maxLength="2000"
          ></textarea>

          <button type="submit">Send Message</button>
        </form>
      </div>
      </div>
    </div>
     <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

  <script>
    const position = { lat: 33.7226, lng: 35.8324 }; // Change to your coordinates

    const map = L.map("map").setView([position.lat, position.lng], 17);

    L.tileLayer(
      "https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}",
      {
        attribution:
          '&copy; <a href="https://www.esri.com/">Esri</a>, Earthstar Geographics'
      }
    ).addTo(map);

    L.marker([position.lat, position.lng])
      .addTo(map)
      .bindPopup("🏛️ Sawirah Municipality <br> Welcome to your town hall!");

    function openGoogleMapsDirections() {
      const url = `https://www.google.com/maps/dir/?api=1&destination=${position.lat},${position.lng}`;
      window.open(url, "_blank");
    }
  </script>

  </body>
</html>
