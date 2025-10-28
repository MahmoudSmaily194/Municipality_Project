<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Settings</title>
<link rel="stylesheet" href="settings.css">
</head>
<body>

<div class="main-container">

    <div class="header">
        <h2>Settings</h2>
    </div>

    <div class="settings-box">

        <form action="#" method="POST" enctype="multipart/form-data">

            <h3 class="section-title">Municipality Information</h3>

            <div class="form-group">
                <label>Municipality Name</label>
                <input type="text" placeholder="e.g. Lebanon Municipality" required>
            </div>

            <div class="form-group">
                <label>Upload Logo</label>
                <input type="file" accept="image/*">
            </div>

            <hr>

            <h3 class="section-title">Admin Account</h3>

            <div class="form-group">
                <label>Admin Username</label>
                <input type="text" placeholder="Enter admin username" required>
            </div>

            <div class="form-group">
                <label>New Password</label>
                <input type="password" placeholder="Enter new password">
            </div>

            <div class="form-group">
                <label>Confirm Password</label>
                <input type="password" placeholder="Confirm password">
            </div>

            <button class="save-btn">Save Settings</button>

        </form>
    </div>

</div>
<style>
    body {
    margin: 0;
    background: #fff;
    font-family: 'Segoe UI', sans-serif;
}

.main-container {
    padding: 30px 60px;
}

.header h2 {
    font-size: 32px;
    color: #1976ff;
    font-weight: 700;
}

.settings-box {
    margin-top: 20px;
    padding: 25px;
    border: 1px solid #dce7ff;
    border-radius: 12px;
    background: #fafcff;
}

.section-title {
    font-size: 22px;
    font-weight: 600;
    color: #1976ff;
    margin-bottom: 15px;
}

.form-group {
    margin-bottom: 18px;
}

.form-group label {
    display: block;
    margin-bottom: 6px;
    font-weight: 600;
    color: #333;
}

input[type="text"],
input[type="password"],
input[type="file"] {
    width: 100%;
    padding: 10px;
    border-radius: 8px;
    border: 1px solid #cbd7f3;
    outline: none;
}

hr {
    border: none;
    height: 1px;
    background: #dce7ff;
    margin: 25px 0;
}

.save-btn {
    background: #1976ff;
    color: white;
    padding: 12px 20px;
    border-radius: 8px;
    border: none;
    cursor: pointer;
    font-size: 16px;
}

    </style>
</body>
</html>
