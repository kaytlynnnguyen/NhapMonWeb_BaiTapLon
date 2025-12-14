<?php
// PHP Logic Section - Start
session_start();
require_once 'functions.php';

// Require login to view cart
if (!isset($_SESSION['user'])) {
    $_SESSION['flash_message'] = ['type' => 'error', 'text' => 'Vui lòng đăng nhập để xem giỏ hàng.'];
    header('Location: login.php');
    exit;
}

// Initialize cart if not exists
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Get cart items with product details
$cart_items = [];
$cart_total = 0;

foreach ($_SESSION['cart'] as $item) {
    $product = getProductById($item['product_id']);
    if ($product) {
        $item_total = $product['price'] * $item['quantity'];
        $cart_items[] = [
            'product_id' => $product['id'],
            'name' => $product['name'],
            'category' => $product['category'],
            'price' => $product['price'],
            'price_formatted' => formatPrice($product['price']),
            'image' => $product['image'],
            'quantity' => $item['quantity'],
            'subtotal' => $item_total,
            'subtotal_formatted' => formatPrice($item_total)
        ];
        $cart_total += $item_total;
    }
}

$cart_total_formatted = formatPrice($cart_total);
$cart_count = getCartCount();

// PHP Logic Section - End
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Giỏ Hàng - Jour Les Tours</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/cart.css">
</head>
<body>
    <!-- Header -->
    <?php require_once __DIR__ . '/partials/header.php'; ?>

    <!-- Cart Section -->
    <section class="cart-section">
        <div class="container">
            <div class="cart-header">
                <h2 class="page-title">Giỏ Hàng Của Bạn</h2>
                <?php if (empty($cart_items)): ?>
                    <p class="cart-empty-message">Giỏ hàng của bạn đang trống</p>
                <?php endif; ?>
            </div>

            <?php if (!empty($cart_items)): ?>
            <div class="cart-content">
                <div class="cart-items">
                    <?php foreach ($cart_items as $item): ?>
                    <div class="cart-item" data-product-id="<?php echo $item['product_id']; ?>">
                        <div class="cart-item-image">
                            <img src="assets/images/<?php echo htmlspecialchars($item['image']); ?>" alt="<?php echo htmlspecialchars($item['name']); ?>" onerror="this.style.backgroundColor='#FFF0F5';">
                        </div>
                        <div class="cart-item-details">
                            <h3 class="cart-item-name"><?php echo htmlspecialchars($item['name']); ?></h3>
                            <p class="cart-item-category"><?php echo htmlspecialchars($item['category']); ?></p>
                            <div class="cart-item-price">
                                <span class="unit-price"><?php echo $item['price_formatted']; ?>₫</span>
                                <span class="subtotal">Tổng: <?php echo $item['subtotal_formatted']; ?>₫</span>
                            </div>
                        </div>
                        <div class="cart-item-controls">
                            <div class="quantity-control">
                                <button class="qty-btn minus" onclick="updateQuantity(<?php echo $item['product_id']; ?>, <?php echo $item['quantity'] - 1; ?>)">-</button>
                                <input type="number" class="qty-input" value="<?php echo $item['quantity']; ?>" min="1" max="99" onchange="updateQuantity(<?php echo $item['product_id']; ?>, this.value)">
                                <button class="qty-btn plus" onclick="updateQuantity(<?php echo $item['product_id']; ?>, <?php echo $item['quantity'] + 1; ?>)">+</button>
                            </div>
                            <button class="btn-remove" onclick="removeFromCart(<?php echo $item['product_id']; ?>)">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M19 6.41L17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12z" fill="currentColor"/>
                                </svg>
                                Xóa
                            </button>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>

                <div class="cart-summary">
                    <div class="summary-card">
                        <h3 class="summary-title">Tóm Tắt Đơn Hàng</h3>
                        <div class="summary-row">
                            <span>Số lượng sản phẩm:</span>
                            <span id="total-items"><?php echo $cart_count; ?></span>
                        </div>
                        <div class="summary-row">
                            <span>Tạm tính:</span>
                            <span><?php echo $cart_total_formatted; ?>₫</span>
                        </div>
                        <div class="summary-row">
                            <span>Phí vận chuyển:</span>
                            <span>Miễn phí</span>
                        </div>
                        <div class="summary-divider"></div>
                        <div class="summary-row total">
                            <span>Tổng cộng:</span>
                            <span id="cart-total"><?php echo $cart_total_formatted; ?>₫</span>
                        </div>
                        <a href="checkout.php" class="btn btn-checkout">Tiến Hành Thanh Toán</a>
                        <a href="index.php" class="btn btn-continue">Tiếp Tục Mua Sắm</a>
                        <button class="btn btn-clear" onclick="clearCart()">Xóa Toàn Bộ Giỏ Hàng</button>
                    </div>
                </div>
            </div>
            <?php else: ?>
            <div class="cart-empty">
                <div class="empty-cart-icon">
                    <svg width="120" height="120" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M7 18c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2zM1 2v2h2l3.6 7.59-1.35 2.45c-.15.28-.25.61-.25.96 0 1.1.9 2 2 2h12v-2H7.42c-.14 0-.25-.11-.25-.25l.03-.12L8.1 13h7.45c.75 0 1.41-.41 1.75-1.03L21.7 4H5.21l-.94-2H1zm16 16c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2z" fill="currentColor"/>
                    </svg>
                </div>
                <h3>Giỏ hàng của bạn đang trống</h3>
                <p>Hãy thêm sản phẩm vào giỏ hàng để tiếp tục mua sắm</p>
                <a href="index.php" class="btn btn-primary">Tiếp Tục Mua Sắm</a>
            </div>
            <?php endif; ?>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="footer-content">
                <div class="footer-column">
                    <h3 class="footer-title">Thông tin liên hệ</h3>
                    <div class="footer-info">
                        <p>
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z"/>
                            </svg>
                            123 Hồng Đào, Phường 14, Quận Tân Bình, TP.HCM
                        </p>
                        <p>
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M6.62 10.79c1.44 2.83 3.76 5.14 6.59 6.59l2.2-2.2c.27-.27.67-.36 1.02-.24 1.12.37 2.33.57 3.57.57.55 0 1 .45 1 1V20c0 .55-.45 1-1 1-9.39 0-17-7.61-17-17 0-.55.45-1 1-1h3.5c.55 0 1 .45 1 1 0 1.25.2 2.45.57 3.57.11.35.03.74-.25 1.02l-2.2 2.2z"/>
                            </svg>
                            0123 456 789
                        </p>
                        <p>
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/>
                            </svg>
                            www.jourlestours.vn
                        </p>
                    </div>
                </div>
                <div class="footer-column">
                    <h3 class="footer-title">Giờ hoạt động</h3>
                    <div class="footer-info">
                        <p>
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M11.99 2C6.47 2 2 6.48 2 12s4.47 10 9.99 10C17.52 22 22 17.52 22 12S17.52 2 11.99 2zM12 20c-4.42 0-8-3.58-8-8s3.58-8 8-8 8 3.58 8 8-3.58 8-8 8zm.5-13H11v6l5.25 3.15.75-1.23-4.5-2.67z"/>
                            </svg>
                            Mở cửa từ 8h00 đến 21h00
                        </p>
                        <p>Mỗi ngày (kể cả cuối tuần và ngày lễ)</p>
                    </div>
                </div>
                <div class="footer-column">
                    <h3 class="footer-title">Mạng xã hội</h3>
                    <div class="footer-info">
                        <p>
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"/>
                            </svg>
                            Facebook: fb.com/jourlestours
                        </p>
                        <p>
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/>
                            </svg>
                            Instagram: @jourlestours
                        </p>
                    </div>
                </div>
                <div class="footer-column">
                    <h3 class="footer-title">Thông tin khác</h3>
                    <div class="footer-info">
                        <p><a href="#">Chính sách đổi trả</a></p>
                        <p><a href="#">Hướng dẫn đặt hàng</a></p>
                        <p><a href="#">Câu hỏi thường gặp</a></p>
                        <p><a href="#">Liên hệ hỗ trợ</a></p>
                    </div>
                </div>
            </div>
            <div class="footer-bottom">
                <p>&copy; 2024 Jour Les Tours. Tất cả quyền được bảo lưu.</p>
            </div>
        </div>
    </footer>

    <script src="assets/js/main.js"></script>
    <script src="assets/js/cart.js"></script>
</body>
</html>

