<?php
session_start();
if (!isset($_SESSION['admin']) || $_SESSION['admin'] !== true) {
    header('Location: index.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $message = trim($_POST['new_message'] ?? '');
    if ($message !== '') {
        file_put_contents('message.txt', $message);
    }

    if (isset($_FILES['media']) && $_FILES['media']['error'] === UPLOAD_ERR_OK) {
        $allowed = ['jpg','jpeg','png','gif','mp4','webm','mp3','ogg'];
        $ext = strtolower(pathinfo($_FILES['media']['name'], PATHINFO_EXTENSION));
        if (in_array($ext, $allowed)) {
            $uploadDir = __DIR__ . '/uploads/';
            if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);
            $filename = 'media_' . time() . '.' . $ext;
            move_uploaded_file($_FILES['media']['tmp_name'], $uploadDir . $filename);
            file_put_contents('file_path.txt', 'uploads/' . $filename);
        }
    }
    header('Location: index.php');
    exit;
}
?>
