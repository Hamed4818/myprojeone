<?php
session_start();

if (!isset($_SESSION['user']) || $_SESSION['user'] !== true) {
    header("Location: index.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!empty(trim($_POST['user_message']))) {
        $msg = trim($_POST['user_message']);
        // اضافه کردن پیام به فایل با یک خط جدید
        file_put_contents('user_messages.txt', $msg . "\n", FILE_APPEND);
    }
}

header("Location: index.php");
exit;
