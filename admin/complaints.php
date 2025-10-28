<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Complaints Management</title>
<link rel="stylesheet" href="complaints.css">
</head>
<body>

<div class="main-container">
    
    <div class="header">
        <h2>Complaints</h2>
    </div>

    <h3 class="section-title">All Received Complaints</h3>

    <table class="complaints-table">
        <thead>
            <tr>
                <th>#</th>
                <th>Citizen Name</th>
                <th>Email</th>
                <th>Issue Type</th>
                <th>Description</th>
                <th>Date Submitted</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>1</td>
                <td>Mahmoud Smaily</td>
                <td>mahmoud@mangoli.com</td>
                <td>Road Damage</td>
                <td>The road in our area needs repairing.</td>
                <td>2025-10-28</td>
                <td><span class="status pending">Pending</span></td>
                <td>
                    <button class="resolve-btn">Mark Resolved</button>
                    <button class="delete-btn">Delete</button>
                </td>
            </tr>

        </tbody>
    </table>

</div>
<style>
    body {
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

.section-title {
    margin-top: 25px;
    font-size: 22px;
    font-weight: 600;
    color: #000;
}

.complaints-table {
    width: 100%;
    margin-top: 15px;
    border-collapse: collapse;
}

.complaints-table th, .complaints-table td {
    border: 1px solid #dce7ff;
    text-align: left;
    padding: 12px;
    vertical-align: top;
}

.status {
    padding: 4px 10px;
    border-radius: 6px;
    font-size: 14px;
    font-weight: 600;
}

.status.pending {
    background: #ffe4b2;
    color: #c27a00;
}

.status.resolved {
    background: #b2ffb8;
    color: #0f7f27;
}

.resolve-btn, .delete-btn {
    padding: 6px 12px;
    border: none;
    border-radius: 6px;
    cursor: pointer;
}

.resolve-btn {
    background: #28a745;
    color: white;
}

.delete-btn {
    background: #ff4d4d;
    color: white;
}

</style>
</body>
</html>
