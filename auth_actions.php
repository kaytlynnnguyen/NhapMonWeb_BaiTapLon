<?php
session_start();
require_once 'functions.php';

// Simple auth handler for register/login/logout
$action = $_POST['action'] ?? $_GET['action'] ?? '';

function redirectWithMessage($location, $message, $type = 'success') {
    $_SESSION['flash_message'] = ['type' => $type, 'text' => $message];
    header('Location: ' . $location);
    exit;
}

if ($action === 'register') {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $password_confirm = $_POST['password_confirm'] ?? '';

    if ($name === '' || $email === '' || $password === '') {
        redirectWithMessage('register.php', 'Vui lòng điền đầy đủ thông tin', 'error');
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        redirectWithMessage('register.php', 'Email không hợp lệ', 'error');
    }
    if ($password !== $password_confirm) {
        redirectWithMessage('register.php', 'Mật khẩu xác nhận không khớp', 'error');
    }
    if (findUserByEmail($email)) {
        redirectWithMessage('register.php', 'Email đã được sử dụng', 'error');
    }

    $user = createUser($name, $email, $password);
    // set session
    $_SESSION['user'] = ['id' => $user['id'], 'name' => $user['name'], 'email' => $user['email']];
    redirectWithMessage('index.php', 'Đăng ký thành công. Bạn đã đăng nhập.');

} elseif ($action === 'login') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    if ($email === '' || $password === '') {
        redirectWithMessage('login.php', 'Vui lòng nhập email và mật khẩu', 'error');
    }
    $user = verifyUser($email, $password);
    if (!$user) {
        redirectWithMessage('login.php', 'Email hoặc mật khẩu không đúng', 'error');
    }
    $_SESSION['user'] = ['id' => $user['id'], 'name' => $user['name'], 'email' => $user['email']];
    
    // Check if admin (hardcoded admin emails for demo)
    $admin_emails = ['admin@jourlestours.com', 'admin@example.com'];
    if (in_array($user['email'], $admin_emails)) {
        $_SESSION['user']['role'] = 'admin';
        redirectWithMessage('admin/', 'Đăng nhập admin thành công');
    }
    
    redirectWithMessage('index.php', 'Đăng nhập thành công');

} elseif ($action === 'logout') {
    unset($_SESSION['user']);
    redirectWithMessage('index.php', 'Bạn đã đăng xuất');

} else {
    // invalid
    redirectWithMessage('index.php', 'Hành động không hợp lệ', 'error');
}
