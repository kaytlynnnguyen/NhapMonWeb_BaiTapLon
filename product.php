<?php
session_start();
require_once 'functions.php';

$id = intval($_GET['id'] ?? 0);
$product = getProductById($id);
if (!$product) {
    header('HTTP/1.0 404 Not Found');
    echo 'Sản phẩm không tìm thấy';
    exit;
}

$flash = null;
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'add_comment') {
    $content = trim($_POST['content'] ?? '');
    $rating = intval($_POST['rating'] ?? 0);
    if (isset($_SESSION['user'])) {
        $user_id = $_SESSION['user']['id'] ?? 0;
        $user_email = $_SESSION['user']['email'] ?? '';
    } else {
        $user_id = 0;
        $user_email = trim($_POST['email'] ?? '');
    }

    if ($content === '' || $user_email === '') {
        $flash = ['type' => 'error', 'text' => 'Vui lòng nhập email và nội dung bình luận.'];
    } else {
        createComment($user_id, $user_email, $product['id'], $content, $rating, 'pending');
        $flash = ['type' => 'success', 'text' => 'Cảm ơn! Bình luận đã được gửi, chờ duyệt.'];
    }
}

$all_comments = array_filter(getComments(), function($c) use ($product) { return ($c['product_id'] ?? 0) == $product['id']; });
$approved = array_values(array_filter($all_comments, function($c){ return ($c['status'] ?? 'pending') === 'approved'; }));

function renderStars($n) {
    $n = max(0, min(5, intval($n)));
    $out = '';
    for ($i=1;$i<=5;$i++) { $out .= $i <= $n ? '★' : '☆'; }
    return '<span class="stars">'.$out.'</span>';
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title><?php echo htmlspecialchars($product['name']); ?> - Jour Les Tours</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/comments.css">
</head>
<body>
    <?php include __DIR__ . '/partials/header.php'; ?>
    <div class="container" style="padding:20px 0;">
        <a href="index.php">← Quay lại</a>
        <h1><?php echo htmlspecialchars($product['name']); ?></h1>
        <div style="display:flex;gap:20px;align-items:flex-start;margin-top:10px;flex-wrap:wrap;">
            <div style="width:320px;flex:0 0 320px;">
                <img src="assets/product/<?php echo htmlspecialchars($product['image']); ?>" alt="" style="width:100%;border-radius:6px;">
            </div>
            <div style="flex:1;min-width:260px;">
                <div style="font-size:22px;color:#f39c12;font-weight:700"><?php echo formatPrice($product['price']); ?>₫</div>
                <p><?php echo htmlspecialchars($product['description']); ?></p>
                <?php if (isset($_SESSION['user'])): ?>
                    <div style="display:inline-block;margin-top:10px;">
                        <button type="button" class="btn btn-primary" onclick="addToCart(<?php echo $product['id']; ?>)">Thêm vào giỏ hàng</button>
                    </div>
                <?php else: ?>
                    <a href="login.php" class="btn btn-primary">Đăng nhập để mua</a>
                <?php endif; ?>
            </div>
        </div>

        <div style="margin-top:30px;">
            <h3>Bình luận (<?php echo count($approved); ?>)</h3>
            <div class="comment-list">
            <?php foreach ($approved as $c): ?>
                <div class="comment">
                    <div class="meta"><?php echo htmlspecialchars($c['user_email'] ?? ''); ?> • <?php echo date('d/m/Y H:i', strtotime($c['created_at'])); ?> • <?php echo renderStars($c['rating'] ?? 0); ?></div>
                    <div class="body"><?php echo nl2br(htmlspecialchars($c['content'])); ?></div>
                </div>
            <?php endforeach; ?>
            </div>
        </div>

        <div style="margin-top:30px;">
            <h3>Gửi bình luận</h3>
            <?php if ($flash): ?><div class="flash <?php echo $flash['type']; ?>"><?php echo htmlspecialchars($flash['text']); ?></div><?php endif; ?>
            <form method="post" class="comment-form">
                <input type="hidden" name="action" value="add_comment">
                <?php if (!isset($_SESSION['user'])): ?>
                    <label>Email</label>
                    <input type="email" name="email" required>
                <?php endif; ?>
                <label>Đánh giá</label>
                <select name="rating">
                    <option value="0">Không đánh giá</option>
                    <option value="1">1 - Tệ</option>
                    <option value="2">2 - Trung bình</option>
                    <option value="3">3 - Khá</option>
                    <option value="4">4 - Tốt</option>
                    <option value="5">5 - Xuất sắc</option>
                </select>
                <label>Nội dung</label>
                <textarea name="content" rows="5" required></textarea>
                <button type="submit">Gửi bình luận</button>
            </form>
            <div class="comment-note">Ghi chú: Bình luận sẽ được kiểm duyệt trước khi hiển thị.</div>
        </div>
    </div>
    <?php include __DIR__ . '/partials/footer.php'; ?>
    <script src="assets/js/main.js"></script>
</body>
</html>
