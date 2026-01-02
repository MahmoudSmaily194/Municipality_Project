<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

session_start();
header('Content-Type: application/json');

// تحقق من تسجيل الدخول
if (!isset($_SESSION['email'])) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Unauthorized'
    ]);
    exit;
}

// السماح فقط بـ POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode([
        'status' => 'error',
        'message' => 'Invalid request'
    ]);
    exit;
}

require __DIR__ . '/PHPMailer/src/Exception.php';
require __DIR__ . '/PHPMailer/src/PHPMailer.php';
require __DIR__ . '/PHPMailer/src/SMTP.php';

// تنظيف البيانات
$name    = trim($_POST['name'] ?? '');
$subject = trim($_POST['subject'] ?? '');
$message = trim($_POST['message'] ?? '');
$email   = $_SESSION['email'];

if (!$name || !$subject || !$message) {
    echo json_encode([
        'status' => 'error',
        'message' => 'All fields are required'
    ]);
    exit;
}

$mail = new PHPMailer(true);

try {
    // SMTP
    $mail->isSMTP();
    $mail->Host       = 'smtp.gmail.com';
    $mail->SMTPAuth   = true;
    $mail->Username   = 'smartmunicipality0@gmail.com';
    $mail->Password   = 'vgvt uqzj yvpy wdsu';
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port       = 587;

    // العناوين
    $mail->setFrom($mail->Username, 'Smart Municipality');
    $mail->addReplyTo($email, $name);
    $mail->addAddress('smartmunicipality0@gmail.com');

    // المحتوى
    $mail->isHTML(false);
    $mail->Subject = $subject;
    $mail->Body =
        "Name: $name\n".
        "Email: $email\n\n".
        "Message:\n$message";

    $mail->send();

    echo json_encode([
        'status' => 'success',
        'message' => 'Message sent successfully'
    ]);
}catch (Exception $e) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Mailer Error: ' . $mail->ErrorInfo
    ]);
}

