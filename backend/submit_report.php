<?php
session_start();
require_once '/xampp/htdocs/Municipality/backend/config/db.php'; // Adjust path
header('Content-Type: application/json');

$response = ['success' => false, 'message' => ''];

// Check if form is submitted via POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Validate required fields
    $issue_id = $_POST['issueType'] ?? null;
    $description = $_POST['description'] ?? null;
    $latitude = $_POST['latitude'] ?? null;
    $longitude = $_POST['longitude'] ?? null;

    if (!$issue_id || !$description || !$latitude || !$longitude) {
        $response['message'] = 'Please fill in all required fields and select a location.';
        echo json_encode($response);
        exit;
    }

    // Handle image upload
    $image_url = null;
    if (isset($_FILES['imageUpload']) && $_FILES['imageUpload']['error'] === UPLOAD_ERR_OK) {
        $fileTmpPath = $_FILES['imageUpload']['tmp_name'];
        $fileName = $_FILES['imageUpload']['name'];
        $fileExt = pathinfo($fileName, PATHINFO_EXTENSION);
        $allowedExts = ['jpg','jpeg','png','gif'];

        if (in_array(strtolower($fileExt), $allowedExts)) {
            $newFileName = uniqid('complaint_', true) . '.' . $fileExt;
            $uploadDir = '/xampp/htdocs/Municipality/uploads/'; // make sure this folder exists and writable
            $destPath = $uploadDir . $newFileName;
            if (!is_uploaded_file($fileTmpPath)) {
                echo json_encode(['success'=>false,'message'=>'Temporary file not found']);
                exit;
            }

            if (!file_exists($uploadDir)) {
                echo json_encode(['success'=>false,'message'=>'Upload folder does not exist']);
                exit;
            }
            if ($_FILES['imageUpload']['error'] !== UPLOAD_ERR_OK) {
    echo json_encode(['success'=>false,'message'=>'Upload error code: ' . $_FILES['imageUpload']['error']]);
    exit;
}
            if (move_uploaded_file($fileTmpPath, $destPath)) {
                $image_url = '/Municipality/uploads/' . $newFileName;
            } else {
                $response['message'] = 'Failed to move uploaded file.';
                echo json_encode($response);
                exit;
            }
        } else {
            $response['message'] = 'Invalid file type. Only JPG, PNG, GIF allowed.';
            echo json_encode($response);
            exit;
        }
    }

    // Insert into database
    try {
        $stmt = $pdo->prepare("
            INSERT INTO complaints 
            (id, description, issue_id, image_url, latitude, longitude, created_by)
            VALUES (UUID(), :description, :issue_id, :image_url, :latitude, :longitude, :created_by)
        ");

        // For demo, use a fixed user ID, replace with logged-in user ID
        $created_by = $_SESSION['user_id'] ?? null; 

        $stmt->execute([
            ':description' => $description,
            ':issue_id' => $issue_id,
            ':image_url' => $image_url,
            ':latitude' => $latitude,
            ':longitude' => $longitude,
            ':created_by' => $created_by
        ]);

        $response['success'] = true;
        $response['message'] = 'Complaint submitted successfully.';

    } catch (PDOException $e) {
        $response['message'] = 'Database error: ' . $e->getMessage();
    }

} else {
    $response['message'] = 'Invalid request method.';
}

echo json_encode($response);
