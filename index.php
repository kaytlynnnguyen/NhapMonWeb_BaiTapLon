<?php
// PHP Logic Section - Start
session_start();
require_once 'functions.php';

// Initialize variables
$success_msg = [];
$warning_msg = [];

// Get products from functions.php
$products = getProducts();

// Prepare top sellers products (best_seller = true, limit 3)
$top_sellers = array_filter($products, function($product) {
    return $product['best_seller'] === true;
});
$top_sellers = array_slice($top_sellers, 0, 3);

// Format prices for display
foreach ($products as &$product) {
    $product['price_formatted'] = formatPrice($product['price']);
}
unset($product); // Unset reference

foreach ($top_sellers as &$product) {
    $product['price_formatted'] = formatPrice($product['price']);
}
unset($product); // Unset reference

// Get cart count
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}
$cart_count = getCartCount();

// PHP Logic Section - End
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jour Les Tours - Bánh Ngọt & Bánh Mặn Cao Cấp</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <!-- Header -->
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
                            <a href="auth_actions.php?action=logout" class="btn-small">Đăng xuất</a>
                        </div>
                        <a href="cart.php" class="cart-icon">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M7 18C5.9 18 5.01 18.9 5.01 20C5.01 21.1 5.9 22 7 22C8.1 22 9 21.1 9 20C9 18.9 8.1 18 7 18ZM1 2V4H3L6.6 11.59L5.25 14.04C5.09 14.32 5 14.65 5 15C5 16.1 5.9 17 7 17H19V15H7.42C7.28 15 7.17 14.89 7.17 14.75L7.2 14.63L8.1 13H15.55C16.3 13 16.96 12.59 17.3 11.97L20.88 5.48C20.96 5.34 21 5.17 21 5C21 4.45 20.55 4 20 4H5.21L4.27 2H1ZM17 18C15.9 18 15.01 18.9 15.01 20C15.01 21.1 15.9 22 17 22C18.1 22 19 21.1 19 20C19 18.9 18.1 18 17 18Z" fill="currentColor"/>
                            </svg>
                            <span class="cart-badge"><?php echo $cart_count; ?></span>
                        </a>
                    <?php else: ?>
                        <a href="login.php" class="btn-small outline">Đăng nhập</a>
                        <a href="register.php" class="btn-small outline" style="margin-left:8px">Đăng ký</a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </header>

    <!-- Hero Section -->
    <section class="hero">
        <div class="hero-overlay">
            <div class="container">
                <div class="hero-content">
                    <h2 class="hero-title">Jour Les Tours</h2>
                    <p class="hero-subtitle">Thưởng thức những chiếc bánh tuyệt vời được làm từ trái tim với tình yêu và sự tận tâm</p>
                    <a href="#menu" class="btn btn-primary">Khám phá menu</a>
                </div>
            </div>
        </div>
    </section>

    <!-- Top Bán Chạy Section -->
    <section id="top-ban-chay" class="section top-sellers">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title">Top Bán Chạy</h2>
                <p class="section-subtitle">Những sản phẩm được yêu thích nhất trong tháng nay</p>
            </div>
            <div class="product-grid">
                <?php foreach ($top_sellers as $product): ?>
                <div class="product-card">
                    <span class="badge best-seller">Bán chạy</span>
                    <div class="product-image">
                        <a href="product.php?id=<?php echo $product['id']; ?>">
                            <img src="assets/product/<?php echo htmlspecialchars($product['image']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>" onerror="this.style.backgroundColor='#FFF0F5';">
                        </a>
                    </div>
                    <div class="product-info">
                        <span class="product-category"><?php echo htmlspecialchars($product['category']); ?></span>
                        <h3 class="product-name"><a href="product.php?id=<?php echo $product['id']; ?>"><?php echo htmlspecialchars($product['name']); ?></a></h3>
                        <p class="product-description"><?php echo htmlspecialchars($product['description']); ?></p>
                        <div class="product-price"><?php echo $product['price_formatted']; ?>₫</div>
                        <?php if (isset($_SESSION['user'])): ?>
                            <button class="btn btn-add-cart" onclick="addToCart(<?php echo $product['id']; ?>)">Thêm vào giỏ hàng</button>
                        <?php else: ?>
                            <a href="login.php" class="btn btn-add-cart">Đăng nhập để mua</a>
                        <?php endif; ?>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <!-- Menu Tổng Hợp Section -->
    <section id="menu" class="section full-menu">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title">Menu Tổng Hợp</h2>
                <p class="section-subtitle">Khám phá toàn bộ bộ sưu tập bánh ngọt và bánh mặn của chúng tôi</p>
            </div>
            <div class="menu-layout">
                <!-- Sidebar Filter -->
                <aside class="sidebar">
                    <h3 class="filter-title">Bộ lọc</h3>
                    
                    <div class="filter-group">
                        <h4 class="filter-group-title">Loại bánh</h4>
                        <div class="radio-group">
                            <label><input type="radio" name="loai-banh" value="all" checked> Tất cả sản phẩm</label>
                            <label><input type="radio" name="loai-banh" value="ngot"> Bánh ngọt</label>
                            <label><input type="radio" name="loai-banh" value="man"> Bánh mặn</label>
                        </div>
                    </div>

                    <div class="filter-group">
                        <h4 class="filter-group-title">Dòng sản phẩm</h4>
                        <div class="radio-group">
                            <label><input type="radio" name="dong-san-pham" value="all" checked> Tất cả sản phẩm</label>
                            <label><input type="radio" name="dong-san-pham" value="macaron"> Macaron</label>
                            <label><input type="radio" name="dong-san-pham" value="banh-kem"> Bánh kem</label>
                            <label><input type="radio" name="dong-san-pham" value="mousse"> Mousse & Cake</label>
                            <label><input type="radio" name="dong-san-pham" value="croissant"> Croissant</label>
                            <label><input type="radio" name="dong-san-pham" value="custard"> Custard</label>
                            <label><input type="radio" name="dong-san-pham" value="pizza"> Pizza</label>
                            <label><input type="radio" name="dong-san-pham" value="empanada"> Empanada</label>
                            <label><input type="radio" name="dong-san-pham" value="focaccia"> Focaccia</label>
                            <label><input type="radio" name="dong-san-pham" value="quiche"> Quiche</label>
                            <label><input type="radio" name="dong-san-pham" value="sandwich"> Sandwich</label>
                            <label><input type="radio" name="dong-san-pham" value="sausage-roll"> Sausage Roll</label>
                            <label><input type="radio" name="dong-san-pham" value="patso"> Bánh Patso</label>
                        </div>
                    </div>

                    <div class="filter-group">
                        <h4 class="filter-group-title">Mức giá</h4>
                        <div class="radio-group">
                            <label><input type="radio" name="muc-gia" value="all" checked> Tất cả mức giá</label>
                            <label><input type="radio" name="muc-gia" value="under-50"> Dưới 50.000₫</label>
                            <label><input type="radio" name="muc-gia" value="50-100"> 50.000₫ - 100.000₫</label>
                            <label><input type="radio" name="muc-gia" value="100-200"> 100.000₫ - 200.000₫</label>
                            <label><input type="radio" name="muc-gia" value="over-200"> Trên 200.000₫</label>
                        </div>
                    </div>
                </aside>

                <!-- Product Grid -->
                <div class="menu-products">
                    <div class="product-grid">
                        <?php foreach ($products as $product): ?>
                        <div class="product-card" 
                             data-type="<?php echo htmlspecialchars($product['type']); ?>"
                             data-line="<?php echo htmlspecialchars($product['line']); ?>"
                             data-price="<?php echo $product['price']; ?>">
                            <?php if ($product['best_seller']): ?>
                                <span class="badge best-seller">Bán chạy</span>
                            <?php endif; ?>
                            <div class="product-image">
                                <a href="product.php?id=<?php echo $product['id']; ?>">
                                    <img src="assets/product/<?php echo htmlspecialchars($product['image']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>" onerror="this.style.backgroundColor='#FFF0F5';">
                                </a>
                            </div>
                            <div class="product-info">
                                <span class="product-category"><?php echo htmlspecialchars($product['category']); ?></span>
                                <h3 class="product-name"><a href="product.php?id=<?php echo $product['id']; ?>"><?php echo htmlspecialchars($product['name']); ?></a></h3>
                                <p class="product-description"><?php echo htmlspecialchars($product['description']); ?></p>
                                <div class="product-price"><?php echo $product['price_formatted']; ?>₫</div>
                                <?php if (isset($_SESSION['user'])): ?>
                                    <button class="btn btn-add-cart" onclick="addToCart(<?php echo $product['id']; ?>)">Thêm vào giỏ hàng</button>
                                <?php else: ?>
                                    <a href="login.php" class="btn btn-add-cart">Đăng nhập để mua</a>
                                <?php endif; ?>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
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
</body>
</html>
