<?php
session_start();
require_once '/xampp/htdocs/Municipality/backend/config/db.php';
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST'){
    if (!isset($_POST['title'], $_POST['eventDate'], $_POST['description'], $_POST['location'])) {
        die("All fields are required");
    }
    $title = trim($_POST['title']);
    $eventDate = trim($_POST['eventDate']);
    $description = trim($_POST['description']);
    $location = trim($_POST['location']);
    $createdBy=$_SESSION['user_id'];
    if (empty($title) || empty($eventDate) || empty($description) || empty($location)) {
          echo json_encode([
        'success' => false,
        'message' => 'All fields must not be empty'
    ]);
    exit;
    }
    
    $imagePath = null;

    if (isset($_FILES['imageUpload']) && $_FILES['imageUpload']['error'] === UPLOAD_ERR_OK) {
        $fileTmpPath = $_FILES['imageUpload']['tmp_name'];
        $fileName = $_FILES['imageUpload']['name'];
        $fileExt = pathinfo($fileName, PATHINFO_EXTENSION);
        $allowedExts = ['jpg','jpeg','png','gif'];

        if (in_array(strtolower($fileExt), $allowedExts)) {
            $newFileName = uniqid('event_', true) . '.' . $fileExt;
            $uploadDir = '/xampp/htdocs/Municipality/uploads/';
            if (!file_exists($uploadDir)) mkdir($uploadDir, 0777, true);
            $destPath = $uploadDir . $newFileName;

            if (move_uploaded_file($fileTmpPath, $destPath)) {
                $imagePath = '/Municipality/uploads/' . $newFileName;
            } else {
                echo json_encode([
                'success' => false,
                'message' => 'Failed to move uploaded file.'
                 ]);
                exit;
            }
        } else {
            echo json_encode([
            'success' => false,
            'message' => 'Invalid file type. Only JPG, JPEG, PNG, GIF allowed.'
                ]);
            exit;            
        }
    }
    else{
         echo json_encode([
            'success' => false,
            'message' => 'Image was not uploaded'
                ]);
            exit;  
    }

    // Generate UUID
    function uuidv4(){
        $data = random_bytes(16);
        $data[6] = chr((ord($data[6]) & 0x0f) | 0x40); // version 4
        $data[8] = chr((ord($data[8]) & 0x3f) | 0x80); // variant
        return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
    }

    $id = uuidv4();
    function createSlug($string) {
    $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $string)));
    return $slug;
    }
    $slug = createSlug($title);
    try {
        $sql = "INSERT INTO events (id, title, slug, date, description, location, image_url, created_by)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$id, $title, $slug, $eventDate, $description, $location, $imagePath, $createdBy]);
        if ($stmt->rowCount() > 0) {
                echo json_encode([
                'success' => true,
                'message' => 'Event was added sucessfully'
                 ]);
                exit;
        }
    } catch (PDOException $e) {
        echo json_encode([
        'success' => false,
        'message' => "Error: " . $e->getMessage()
        ]);
        exit;
    }
}
?>