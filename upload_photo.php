<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['photo'])) {
    if (!file_exists('user_photos')) mkdir('user_photos');
    $filename = 'user_photos/user_photo_' . time() . '.jpg';
    move_uploaded_file($_FILES['photo']['tmp_name'], $filename);
}
