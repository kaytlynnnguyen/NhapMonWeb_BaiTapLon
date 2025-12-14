<?php
// Partial header - expects session started and functions.php already loaded
if (!isset($_SESSION)) {
    // do not start session here; pages should start session
}

// Ensure cart exists
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}
$cart_count = function_exists('getCartCount') ? getCartCount() : (isset($_SESSION['cart']) ? count($_SESSION['cart']) : 0);
?>
<header class="header">
    <div class="container">
        <div class="header-content">
            <div class="logo">
                <h1>Jour Les Tours</h1>
            </div>
            <nav class="nav-menu">
                <ul>
                    <li><a href="#menu">Menu tổng hợp</a></li>
                    <li><a href="#banh-man">Bánh mặn</a></li>
                    <li><a href="#banh-ngot">Bánh ngọt</a></li>
                    <li><a href="#top-ban-chay">Top bán chạy</a></li>
                </ul>
            </nav>
            <div class="header-icons">
                <?php if (isset($_SESSION['user'])): ?>
                    <div class="user-info" style="display:flex;align-items:center;gap:10px">
                        <span>Xin chào, <?php echo htmlspecialchars($_SESSION['user']['name'] ?? ''); ?></span>
                        <a href="auth_actions.php?action=logout" class="btn-small" title="Đăng xuất">
                            <!-- logout icon -->
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" style="vertical-align:middle;margin-right:6px"><path d="M16 13v-2H7V8l-5 4 5 4v-3zM20 3h-9v2h9v14h-9v2h9a2 2 0 0 0 2-2V5a2 2 0 0 0-2-2z" fill="currentColor"/></svg>
                            Đăng xuất
                        </a>
                    </div>
                    <a href="cart.php" class="cart-icon" title="Giỏ hàng">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M7 18C5.9 18 5.01 18.9 5.01 20C5.01 21.1 5.9 22 7 22C8.1 22 9 21.1 9 20C9 18.9 8.1 18 7 18ZM1 2V4H3L6.6 11.59L5.25 14.04C5.09 14.32 5 14.65 5 15C5 16.1 5.9 17 7 17H19V15H7.42C7.28 15 7.17 14.89 7.17 14.75L7.2 14.63L8.1 13H15.55C16.3 13 16.96 12.59 17.3 11.97L20.88 5.48C20.96 5.34 21 5.17 21 5C21 4.45 20.55 4 20 4H5.21L4.27 2H1ZM17 18C15.9 18 15.01 18.9 15.01 20C15.01 21.1 15.9 22 17 22C18.1 22 19 21.1 19 20C19 18.9 18.1 18 17 18Z" fill="currentColor"/>
                        </svg>
                        <span class="cart-badge"><?php echo $cart_count; ?></span>
                    </a>
                <?php else: ?>
                    <a href="login.php" class="btn-small outline" title="Đăng nhập">
                        <!-- user icon -->
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" style="vertical-align:middle;margin-right:6px"><path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z" fill="currentColor"/></svg>
                        Đăng nhập
                    </a>
                    <a href="register.php" class="btn-small outline" style="margin-left:8px" title="Đăng ký">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" style="vertical-align:middle;margin-right:6px"><path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z" fill="currentColor"/></svg>
                        Đăng ký
                    </a>
                <?php endif; ?>
                <a href="index.php" class="search-icon" title="Trang chủ">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M10 20v-6h4v6h5v-8h3L12 3 2 12h3v8z" fill="currentColor"/>
                    </svg>
                </a>
            </div>
        </div>
    </div>
</header>
