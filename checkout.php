<?php
session_start();
require_once 'functions.php';

// Require login
if (!isset($_SESSION['user'])) {
    $_SESSION['flash_message'] = ['type' => 'error', 'text' => 'Vui lòng đăng nhập để tiếp tục.'];
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

// Handle order creation
$flash = null;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $payment_method = trim($_POST['payment_method'] ?? '');
    
    if (empty($cart_items)) {
        $flash = ['type' => 'error', 'text' => 'Giỏ hàng trống. Vui lòng thêm sản phẩm.'];
    } elseif (!$payment_method) {
        $flash = ['type' => 'error', 'text' => 'Vui lòng chọn phương thức thanh toán.'];
    } else {
        // Create order
        $order = createOrder(
            $_SESSION['user']['id'],
            $_SESSION['user']['email'],
            $_SESSION['cart'],
            $cart_total,
            'pending'
        );
        
        // Clear cart
        $_SESSION['cart'] = [];
        
        $flash = ['type' => 'success', 'text' => 'Đơn hàng #' . $order['id'] . ' đã được tạo thành công! Admin sẽ xác nhận trong 24h.'];
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thanh Toán - Jour Les Tours</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        .checkout-container { max-width: 1000px; margin: 40px auto; padding: 0 20px; }
        .checkout-content { display: grid; grid-template-columns: 1fr 1fr; gap: 30px; margin-top: 20px; }
        .checkout-section { background: #fff; padding: 20px; border-radius: 8px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); }
        .checkout-section h3 { font-size: 18px; color: #FF69B4; margin-bottom: 15px; border-bottom: 2px solid #FFB6C1; padding-bottom: 10px; }
        .order-item { padding: 12px 0; border-bottom: 1px solid #eee; display: flex; justify-content: space-between; align-items: center; }
        .order-item:last-child { border-bottom: none; }
        .order-item-name { flex: 1; }
        .order-item-qty { color: #999; font-size: 14px; margin: 0 10px; }
        .order-item-price { font-weight: bold; color: #FF69B4; }
        .order-summary { background: #FFF0F5; padding: 15px; border-radius: 5px; margin: 15px 0; }
        .summary-row { display: flex; justify-content: space-between; padding: 8px 0; }
        .summary-row.total { font-weight: bold; font-size: 16px; border-top: 2px solid #FFB6C1; padding-top: 10px; color: #FF69B4; }
        .payment-options { margin-top: 15px; }
        .payment-option { margin: 10px 0; }
        .payment-option input[type="radio"] { margin-right: 10px; }
        .payment-option label { cursor: pointer; font-size: 15px; }
        .form-group { margin-bottom: 15px; }
        .form-group label { display: block; margin-bottom: 5px; font-weight: 500; }
        .form-group input, .form-group textarea { width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px; font-family: inherit; }
        .btn-large { width: 100%; padding: 12px; font-size: 16px; border: none; border-radius: 5px; cursor: pointer; transition: 0.3s; }
        .btn-checkout { background: #FF69B4; color: #fff; margin-top: 15px; }
        .btn-checkout:hover { background: #FF1493; }
        .btn-cancel { background: #999; color: #fff; margin-top: 10px; text-decoration: none; display: inline-block; text-align: center; }
        .btn-cancel:hover { background: #666; }
        .flash { padding: 15px; border-radius: 5px; margin-bottom: 15px; }
        .flash.success { background: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
        .flash.error { background: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
        .empty-message { text-align: center; color: #999; padding: 40px; }
        @media (max-width: 768px) {
            .checkout-content { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body>
    <?php require_once __DIR__ . '/partials/header.php'; ?>

    <div class="checkout-container">
        <h2 style="color: #FF69B4; text-align: center;">Thanh Toán Đơn Hàng</h2>

        <?php if ($flash): ?>
            <div class="flash <?php echo $flash['type']; ?>">
                <?php echo htmlspecialchars($flash['text']); ?>
                <?php if ($flash['type'] === 'success'): ?>
                    <div style="margin-top: 15px;">
                        <a href="index.php" class="btn btn-primary" style="display: inline-block; padding: 10px 20px;">Quay Lại Trang Chủ</a>
                    </div>
                <?php endif; ?>
            </div>
        <?php endif; ?>

        <?php if (empty($cart_items) && !$flash): ?>
            <div class="empty-message">
                <p style="font-size: 16px; margin-bottom: 15px;">Giỏ hàng của bạn đang trống</p>
                <a href="index.php" class="btn btn-primary" style="display: inline-block; padding: 10px 20px;">Tiếp Tục Mua Sắm</a>
            </div>
        <?php elseif (!empty($cart_items)): ?>
            <div class="checkout-content">
                <!-- Order Summary -->
                <div class="checkout-section">
                    <h3>📋 Tóm Tắt Đơn Hàng</h3>
                    
                    <div style="max-height: 400px; overflow-y: auto;">
                        <?php foreach ($cart_items as $item): ?>
                            <div class="order-item">
                                <div class="order-item-name">
                                    <strong><?php echo htmlspecialchars($item['name']); ?></strong><br>
                                    <small style="color: #999;"><?php echo htmlspecialchars($item['category']); ?></small>
                                </div>
                                <div class="order-item-qty"><?php echo $item['quantity']; ?>x</div>
                                <div class="order-item-price"><?php echo $item['price_formatted']; ?>₫</div>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <div class="order-summary">
                        <div class="summary-row">
                            <span>Tạm Tính:</span>
                            <span><?php echo $cart_total_formatted; ?>₫</span>
                        </div>
                        <div class="summary-row">
                            <span>Phí Vận Chuyển:</span>
                            <span>Miễn Phí</span>
                        </div>
                        <div class="summary-row total">
                            <span>Tổng Cộng:</span>
                            <span><?php echo $cart_total_formatted; ?>₫</span>
                        </div>
                    </div>
                </div>

                <!-- Payment Form -->
                <div class="checkout-section">
                    <h3>💳 Phương Thức Thanh Toán</h3>
                    
                    <form method="post">
                        <div class="form-group">
                            <label><strong>Chọn Phương Thức Thanh Toán</strong></label>
                            <div class="payment-options">
                                <div class="payment-option">
                                    <input type="radio" id="cash" name="payment_method" value="cash" required>
                                    <label for="cash">💰 Thanh Toán Tiền Mặt (COD - Khi Nhận Hàng)</label>
                                </div>
                                <div class="payment-option">
                                    <input type="radio" id="transfer" name="payment_method" value="transfer" disabled>
                                    <label for="transfer" style="color: #999;">🏦 Chuyển Khoản Ngân Hàng (Sắp Có)</label>
                                </div>
                                <div class="payment-option">
                                    <input type="radio" id="ewallet" name="payment_method" value="ewallet" disabled>
                                    <label for="ewallet" style="color: #999;">📱 Ví Điện Tử (Sắp Có)</label>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label><strong>Ghi Chú Đơn Hàng (Tuỳ Chọn)</strong></label>
                            <textarea name="notes" rows="4" placeholder="Ví dụ: Giao vào giờ hành chính, để ở đó, vv..."></textarea>
                        </div>

                        <div style="background: #fffacd; padding: 12px; border-radius: 5px; margin: 15px 0; border-left: 4px solid #FFD700;">
                            <strong>⚠️ Lưu Ý:</strong>
                            <ul style="margin-left: 20px; margin-top: 8px; font-size: 14px;">
                                <li>Hãy kiểm tra lại địa chỉ giao hàng</li>
                                <li>Bạn sẽ nhận được email xác nhận đơn hàng</li>
                                <li>Admin sẽ liên hệ xác nhận trong vòng 24h</li>
                            </ul>
                        </div>

                        <button type="submit" class="btn-large btn-checkout">
                            ✓ Xác Nhận Đặt Hàng (<?php echo $cart_total_formatted; ?>₫)
                        </button>
                        <a href="cart.php" class="btn-large btn-cancel">← Quay Lại Giỏ Hàng</a>
                    </form>
                </div>
            </div>
        <?php endif; ?>
    </div>

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
                    </div>
                </div>
                <div class="footer-column">
                    <h3 class="footer-title">Thông tin khác</h3>
                    <div class="footer-info">
                        <p><a href="#">Chính sách đổi trả</a></p>
                        <p><a href="#">Hướng dẫn đặt hàng</a></p>
                    </div>
                </div>
            </div>
            <div class="footer-bottom">
                <p>&copy; 2024 Jour Les Tours. Tất cả quyền được bảo lưu.</p>
            </div>
        </div>
    </footer>
</body>
</html>
