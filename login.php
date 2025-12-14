<?php
session_start();
require_once 'functions.php';

$flash = $_SESSION['flash_message'] ?? null;
unset($_SESSION['flash_message']);

?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Đăng Nhập - Jour Les Tours</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <style>.auth-container{max-width:420px;margin:40px auto;padding:20px;border:1px solid #eee;background:#fff;border-radius:8px}.auth-container h2{margin-bottom:16px}.flash{padding:12px;border-radius:6px;margin-bottom:12px}.flash.success{background:#e6fffa;color:#064e3b}.flash.error{background:#ffe6e6;color:#6b0b0b}</style>
</head>
<body>
    <?php require_once __DIR__ . '/partials/header.php'; ?>
    <div class="container auth-container">
        <h2>Đăng Nhập</h2>
        <?php if ($flash): ?>
            <div class="flash <?php echo htmlspecialchars($flash['type'] ?? 'success'); ?>"><?php echo htmlspecialchars($flash['text']); ?></div>
        <?php endif; ?>
        <form action="auth_actions.php" method="post">
            <input type="hidden" name="action" value="login">
            <div>
                <label>Email</label>
                <input type="email" name="email" required class="input" style="width:100%;padding:8px;margin:6px 0">
            </div>
            <div>
                <label>Mật khẩu</label>
                <input type="password" name="password" required class="input" style="width:100%;padding:8px;margin:6px 0">
            </div>
            <div style="margin-top:12px">
                <button type="submit" class="btn btn-primary">Đăng nhập</button>
                <a href="register.php" style="margin-left:12px">Chưa có tài khoản? Đăng ký</a>
            </div>
        </form>
    </div>
</body>
</html>
