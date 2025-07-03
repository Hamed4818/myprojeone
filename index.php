<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
<meta charset="UTF-8" />
<title>سایت من</title>
<meta name="viewport" content="width=device-width, initial-scale=1" />
<style>
body {
    font-family: Tahoma, sans-serif;
    background: url('background.jpg') no-repeat center center fixed;
    background-size: cover;
    margin: 0; padding: 0;
    direction: rtl;
    display: flex;
    justify-content: center;
    align-items: center;
    min-height: 100vh;
}

.container {
    background: white;
    padding: 20px 30px;
    border-radius: 16px;
    box-shadow: 0 6px 15px rgba(0,0,0,0.1);
    max-width: 480px;
    width: 90%;
    animation: fadeIn 0.8s ease forwards;
}
h2,h3 {
    margin: 0 0 15px 0;
    text-align: center;
    color: #333;
}
input[type=password], textarea {
    width: 100%;
    padding: 12px;
    margin-bottom: 18px;
    border-radius: 10px;
    border: 1.5px solid #ccc;
    font-size: 16px;
    box-sizing: border-box;
    transition: 0.3s;
}
input[type=password]:focus, textarea:focus {
    border-color: #4a90e2;
    outline: none;
}
button {
    width: 100%;
    padding: 14px;
    background: #4a90e2;
    color: white;
    border: none;
    border-radius: 12px;
    font-size: 17px;
    cursor: pointer;
    transition: 0.3s;
}
button:hover {
    background: #357ABD;
}
.message-box {
    background: #e8f0fe;
    padding: 14px;
    border-radius: 12px;
    margin-bottom: 15px;
    color: #2d3e50;
    white-space: pre-wrap;
    word-break: break-word;
}
.image-preview {
    max-width: 100%;
    border-radius: 12px;
    margin-bottom: 15px;
}
.success {
    color: #27ae60;
    text-align: center;
    margin-bottom: 12px;
}
.error {
    color: #e74c3c;
    text-align: center;
    margin-bottom: 12px;
}
.admin-panel {
    max-height: 300px;
    overflow-y: auto;
    background: #f9f9f9;
    border: 1px solid #ccc;
    border-radius: 12px;
    padding: 10px;
    margin-bottom: 15px;
}
@keyframes fadeIn {
    from {opacity: 0; transform: translateY(20px);}
    to {opacity: 1; transform: translateY(0);}
}
</style>
</head>
<body>
<div class="container" id="app">

<!-- صفحه لاگین -->
<div id="login-page">
    <h2>👇🏻 برای ورود رمز را وارد کنید 👇🏻</h2>
    <form id="login-form" autocomplete="off" novalidate>
        <input type="password" id="password-input" placeholder="رمز عبور" autocomplete="new-password" required />
        <button type="submit">ورود</button>
    </form>
    <p class="error" id="login-error" style="display:none;"></p>
</div>

<!-- صفحه کاربر -->
<div id="user-page" style="display:none;">
    <h3>پیام خود را وارد کنید:</h3>
    <form id="user-message-form" autocomplete="off">
        <textarea id="user-message-input" placeholder="پیام شما..." required style="height:100px;"></textarea>
        <button type="submit">ارسال پیام</button>
        <p class="success" id="user-message-success" style="display:none;">✅ پیام ارسال شد.</p>
    </form>
    <div class="message-box" id="user-message"></div>
    <button id="logout-btn" style="margin-top:20px;background:#e74c3c;">خروج</button>
</div>

<!-- صفحه ادمین -->
<div id="admin-page" style="display:none;">
    <h3>پنل ادمین</h3>
    <form id="admin-form" enctype="multipart/form-data" autocomplete="off" novalidate>
        <textarea name="new_message" id="admin-message" required></textarea>
        <input type="file" name="media" id="admin-media" accept="image/*,video/*,audio/*" />
        <button type="submit" style="margin-top:10px;">ذخیره</button>
    </form>

    <h3>پیام‌های کاربران:</h3>
    <div class="admin-panel" id="user-messages-list"></div>

    <button id="admin-logout-btn" style="margin-top:20px;background:#e74c3c;">خروج از ادمین</button>
</div>

<script>
const PASSWORD_USER = '1399';
const PASSWORD_ADMIN = '8482924';

const loginPage = document.getElementById('login-page');
const userPage = document.getElementById('user-page');
const adminPage = document.getElementById('admin-page');
const loginForm = document.getElementById('login-form');
const passwordInput = document.getElementById('password-input');
const loginError = document.getElementById('login-error');
const logoutBtn = document.getElementById('logout-btn');
const adminLogoutBtn = document.getElementById('admin-logout-btn');

function showLoginPage() {
    loginPage.style.display = 'block';
    userPage.style.display = 'none';
    adminPage.style.display = 'none';
    loginError.style.display = 'none';
}

function showUserPage() {
    loginPage.style.display = 'none';
    userPage.style.display = 'block';
    adminPage.style.display = 'none';
    fetchMessage();
}

function showAdminPage() {
    loginPage.style.display = 'none';
    userPage.style.display = 'none';
    adminPage.style.display = 'block';
    fetchAdminData();
}

loginForm.addEventListener('submit', e => {
    e.preventDefault();
    const pass = passwordInput.value.trim();

    if (pass === PASSWORD_ADMIN) {
        showAdminPage();
    } else if (pass === PASSWORD_USER) {
        showUserPage();
    } else {
        loginError.textContent = 'رمز اشتباه است!';
        loginError.style.display = 'block';
    }
});

// کاربر
document.getElementById('user-message-form').addEventListener('submit', function(e) {
    e.preventDefault();
    const msg = document.getElementById('user-message-input').value.trim();
    if (!msg) return;

    const formData = new FormData();
    formData.append('action', 'user_message');
    formData.append('message', msg);

    fetch('save.php', {
        method: 'POST',
        body: formData
    })
    .then(res => res.text())
    .then(response => {
        if (response === 'ok') {
            document.getElementById('user-message-success').style.display = 'block';
            document.getElementById('user-message-input').value = '';
            fetchMessage();
            setTimeout(() => {
                document.getElementById('user-message-success').style.display = 'none';
            }, 3000);
        }
    });
});

function fetchMessage() {
    fetch('save.php?action=get_message')
    .then(res => res.text())
    .then(text => {
        document.getElementById('user-message').textContent = text;
    });
}

// ادمین
document.getElementById('admin-form').addEventListener('submit', function(e) {
    e.preventDefault();
    const formData = new FormData(this);
    formData.append('action', 'admin_save');

    fetch('save.php', {
        method: 'POST',
        body: formData
    })
    .then(res => res.text())
    .then(response => {
        if (response === 'ok') {
            alert('ذخیره شد');
            fetchAdminData();
        }
    });
});

function fetchAdminData() {
    fetch('save.php?action=get_all')
    .then(res => res.json())
    .then(data => {
        document.getElementById('admin-message').value = data.message;
        const userMessagesList = document.getElementById('user-messages-list');
        userMessagesList.innerHTML = '';
        data.user_messages.forEach(msg => {
            const div = document.createElement('div');
            div.className = 'message-box';
            div.textContent = msg;
            userMessagesList.appendChild(div);
        });
    });
}

logoutBtn.addEventListener('click', showLoginPage);
adminLogoutBtn.addEventListener('click', showLoginPage);

showLoginPage();
</script>
</div>
</body>
</html>
