<?php
session_start();

$message = file_exists('message.txt') ? trim(file_get_contents('message.txt')) : 'دوست دارم';
$image_path = file_exists('image_path.txt') ? trim(file_get_contents('image_path.txt')) : '';
$is_admin = isset($_SESSION['admin']) && $_SESSION['admin'] === true;
$is_user = isset($_SESSION['user']) && $_SESSION['user'] === true;

$show_success = false; // برای نمایش پیام موفقیت ارسال پیام کاربر

// فایل ذخیره پیام‌های کاربران
$user_messages_file = 'user_messages.txt';
$user_messages = [];
if ($is_admin && file_exists($user_messages_file)) {
    $user_messages = file($user_messages_file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
}

// ارسال پیام از کاربر به ادمین
if ($is_user && $_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['user_message'])) {
    $new_user_message = trim($_POST['user_message']);
    if ($new_user_message !== '') {
        file_put_contents($user_messages_file, $new_user_message . PHP_EOL, FILE_APPEND | LOCK_EX);
        $show_success = true;
    }
}

// مدیریت فرم ورود
if (!$is_admin && !$is_user && $_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['password'])) {
    $pass = $_POST['password'];
    if ($pass === '2171388') {
        $_SESSION['admin'] = true;
        header("Location: index.php");
        exit;
    } elseif ($pass === '1399') {
        $_SESSION['user'] = true;
        header("Location: index.php");
        exit;
    } else {
        $error = "رمز اشتباه است ❌";
    }
}

// مدیریت خروج
if (isset($_POST['logout'])) {
    session_destroy();
    header("Location: index.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="fa">
<head>
<meta charset="UTF-8" />
<title>proje 1</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<style>
    * { box-sizing: border-box; }
    body {
        margin: 0; padding: 0;
        font-family: 'Vazir', sans-serif;
        direction: rtl;
        background: linear-gradient(to top right,rgb(73, 70, 70), #e0f7fa);
        display: flex;
        justify-content: center;
        align-items: center;
        min-height: 100vh;
        animation: fadeIn 1s ease-in-out;
    }
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(30px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .container {
        background: rgba(255, 255, 255, 0.25);
        backdrop-filter: blur(15px);
        border-radius: 20px;
        box-shadow: 0 8px 32px rgba(0,0,0,0.2);
        padding: 30px;
        width: 90%;
        max-width: 400px;
        text-align: center;
        animation: fadeIn 1s;
        max-height: 90vh;
        overflow-y: auto;
    }
    h2, h3 {
        color: #1a237e;
        margin-bottom: 20px;
    }
    input[type="password"], textarea, button, input[type="file"] {
        width: 100%;
        padding: 12px;
        font-size: 16px;
        margin-top: 10px;
        border-radius: 10px;
        border: none;
        outline: none;
        transition: 0.3s ease;
    }
    input[type="password"]:focus, textarea:focus, input[type="file"]:focus {
        box-shadow: 0 0 0 3px rgba(0, 123, 255, 0.3);
    }
    button {
        background-color: #2196f3;
        color: white;
        font-weight: bold;
        cursor: pointer;
    }
    button:hover {
        background-color: #1976d2;
        transform: scale(1.02);
    }
    .logout-btn {
        background-color: #f44336;
        margin-top: 15px;
        cursor: pointer;
    }
    .logout-btn:hover {
        background-color: #c62828;
    }
    .message-box {
        background-color: rgba(255, 255, 255, 0.8);
        padding: 15px;
        border-radius: 12px;
        margin-top: 15px;
        font-size: 20px;
        color: #333;
        box-shadow: inset 0 0 8px rgba(0,0,0,0.1);
        word-break: break-word;
        white-space: pre-wrap;
    }
    .image-preview {
        margin-top: 10px;
        max-width: 100%;
        border-radius: 12px;
        box-shadow: 0 0 10px rgba(0,0,0,0.1);
    }
    textarea {
        resize: none;
        height: 100px;
        font-family: inherit;
    }
    p.error {
        color: red;
        margin-top: 10px;
    }
    p.success {
        color: green;
        margin-top: 10px;
        font-weight: bold;
    }
</style>
</head>
<body>
<div class="container">

<?php if (!$is_admin && !$is_user): ?>
    <h2>برای ورود به سایت لطفا رمز خود را این زیر وارد کنید</h2>
    <form method="post">
        <input type="password" name="password" placeholder="👈🏻 رمز عبور خود را اینجا وارد کنید 👉🏻" required />
        <button type="submit">ورود به سایت 🙂</button>
    </form>
    <?php if (isset($error)): ?>
        <p class="error"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>

<?php elseif ($is_user): ?>
    <h3>این پیام مخصوص خودته 🙂👇🏻</h3>
    <div class="message-box"><?= htmlspecialchars($message) ?></div>
    <?php if ($image_path && file_exists($image_path)): ?>
        <img src="<?= htmlspecialchars($image_path) ?>" alt="تصویر پیام" class="image-preview" />
    <?php endif; ?>

    <h3>ارسال پیام به جاس فرند عزیزم 🙂</h3>
    <form method="post">
        <textarea name="user_message" placeholder="پیام خود را اینجا بنویسید..." required></textarea>
        <button type="submit">ارسال پیام</button>
    </form>
    <?php if ($show_success): ?>
        <p class="success">پیام شما با موفقیت ارسال شد ✅</p>
    <?php endif; ?>

    <form method="post">
        <button name="logout" class="logout-btn">خروج</button>
    </form>

<?php elseif ($is_admin): ?>
    <h3>پنل ادمین - ویرایش پیام و آپلود تصویر</h3>
    <form method="post" action="save_message.php" enctype="multipart/form-data">
        <textarea name="new_message" placeholder="متن پیام"><?= htmlspecialchars($message) ?></textarea>
        <input type="file" name="image" accept="image/*" />
        <button type="submit">ذخیره</button>
    </form>

    <h3>پیام‌های کاربران:</h3>
    <?php if (!empty($user_messages)): ?>
        <?php foreach ($user_messages as $msg): ?>
            <div class="message-box"><?= htmlspecialchars($msg) ?></div>
        <?php endforeach; ?>
    <?php else: ?>
        <p>هیچ پیامی وجود ندارد.</p>
    <?php endif; ?>

    <form method="post">
        <button name="logout" class="logout-btn">خروج</button>
    </form>

<?php endif; ?>

</div>
</body>
</html>
