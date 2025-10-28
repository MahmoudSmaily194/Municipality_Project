<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Event Management</title>
<link rel="stylesheet" href="events.css">
</head>
<body>

<div class="main-container">
    
    <div class="header">
        <h2>Events</h2>
    </div>

    <div class="add-event-box">
        <h3>Add New Event</h3>
        <form action="#" method="POST" enctype="multipart/form-data">

            <div class="form-group">
                <label>Event Title</label>
                <input type="text" placeholder="Enter event title" required>
            </div>

            <div class="form-group">
                <label>Event Date</label>
                <input type="date" required>
            </div>

            <div class="form-group">
                <label>Location</label>
                <input type="text" placeholder="Enter event location" required>
            </div>

            <div class="form-group">
                <label>Description</label>
                <textarea placeholder="Enter event details..." required></textarea>
            </div>

            <div class="form-group">
                <label>Upload Image</label>
                <input type="file" accept="image/*">
            </div>

            <button class="btn-submit">Add Event</button>
        </form>
    </div>

    <h3 class="section-title">Upcoming & Past Events</h3>

    <table class="event-table">
        <thead>
            <tr>
                <th>#</th>
                <th>Event Title</th>
                <th>Date</th>
                <th>Location</th>
                <th>Image</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>1</td>
                <td>Community Clean-Up</td>
                <td>2025-10-30</td>
                <td>Main Street Park</td>
                <td><img src="https://via.placeholder.com/60"></td>
                <td>
                    <button class="edit-btn">Edit</button>
                    <button class="delete-btn">Delete</button>
                </td>
            </tr>
        </tbody>
    </table>

</div>
<style>body {
    margin: 0;
    font-family: 'Segoe UI', sans-serif;
    background: #fff;
}

.main-container {
    padding: 30px 60px;
}

.header h2 {
    font-size: 32px;
    font-weight: 700;
    color: #1976ff;
}

.add-event-box {
    margin-top: 20px;
    padding: 20px;
    border: 1px solid #dce7ff;
    border-radius: 10px;
}

.add-event-box h3 {
    margin-bottom: 15px;
    color: #1976ff;
    font-size: 22px;
    font-weight: 600;
}

.form-group {
    margin-bottom: 15px;
}

.form-group label {
    display: block;
    margin-bottom: 6px;
    font-weight: 600;
}

input[type="text"],
input[type="date"],
textarea,
input[type="file"] {
    width: 100%;
    padding: 10px;
    border-radius: 8px;
    border: 1px solid #cbd7f3;
    outline: none;
}

textarea {
    height: 120px;
}

.btn-submit {
    background: #1976ff;
    color: #fff;
    padding: 10px 18px;
    border: none;
    border-radius: 8px;
    cursor: pointer;
    font-size: 16px;
}

.section-title {
    margin-top: 35px;
    font-size: 22px;
    font-weight: 600;
}

.event-table {
    width: 100%;
    margin-top: 15px;
    border-collapse: collapse;
}

.event-table th, .event-table td {
    border: 1px solid #dce7ff;
    text-align: left;
    padding: 12px;
}

.event-table img {
    width: 60px;
    height: 60px;
    border-radius: 6px;
    object-fit: cover;
}

.edit-btn, .delete-btn {
    padding: 6px 12px;
    border: none;
    border-radius: 6px;
    cursor: pointer;
}

.edit-btn {
    background: #007bff;
    color: white;
}

.delete-btn {
    background: #ff4d4d;
    color: white;
}
</style>

</body>
</html>
