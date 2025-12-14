<?php
session_start();
require_once 'functions.php';

header('Content-Type: application/json; charset=utf-8');

$action = $_POST['action'] ?? '';

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Require user to be logged in for cart operations
if (!isset($_SESSION['user'])) {
    echo json_encode(['success' => false, 'message' => 'Vui lòng đăng nhập để sử dụng giỏ hàng.', 'cart_count' => 0], JSON_UNESCAPED_UNICODE);
    exit;
}

function jsonResponse($success, $message, $extra = []) {
    $resp = array_merge(['success' => $success, 'message' => $message, 'cart_count' => getCartCount()], $extra);
    echo json_encode($resp, JSON_UNESCAPED_UNICODE);
    exit;
}

switch ($action) {
    case 'add':
        $product_id = intval($_POST['product_id'] ?? 0);
        $quantity = max(1, intval($_POST['quantity'] ?? 1));

        $product = getProductById($product_id);
        if (!$product) {
            jsonResponse(false, 'Sản phẩm không tồn tại.');
        }

        // find existing
        $found = false;
        foreach ($_SESSION['cart'] as &$item) {
            if ($item['product_id'] == $product_id) {
                $item['quantity'] += $quantity;
                $found = true;
                break;
            }
        }
        unset($item);

        if (!$found) {
            $_SESSION['cart'][] = ['product_id' => $product_id, 'quantity' => $quantity];
        }

        jsonResponse(true, 'Đã thêm sản phẩm vào giỏ hàng.');
        break;

    case 'update':
        $product_id = intval($_POST['product_id'] ?? 0);
        $quantity = intval($_POST['quantity'] ?? 0);

        if ($quantity < 1) {
            // remove if quantity less than 1
            foreach ($_SESSION['cart'] as $k => $item) {
                if ($item['product_id'] == $product_id) {
                    unset($_SESSION['cart'][$k]);
                    $_SESSION['cart'] = array_values($_SESSION['cart']);
                    break;
                }
            }
            jsonResponse(true, 'Đã xoá sản phẩm khỏi giỏ hàng.');
        }

        $updated = false;
        foreach ($_SESSION['cart'] as &$item) {
            if ($item['product_id'] == $product_id) {
                $item['quantity'] = $quantity;
                $updated = true;
                break;
            }
        }
        unset($item);

        if ($updated) {
            jsonResponse(true, 'Cập nhật số lượng thành công.');
        }
        jsonResponse(false, 'Sản phẩm không có trong giỏ hàng.');
        break;

    case 'remove':
        $product_id = intval($_POST['product_id'] ?? 0);
        foreach ($_SESSION['cart'] as $k => $item) {
            if ($item['product_id'] == $product_id) {
                unset($_SESSION['cart'][$k]);
                $_SESSION['cart'] = array_values($_SESSION['cart']);
                jsonResponse(true, 'Đã xoá sản phẩm khỏi giỏ hàng.');
            }
        }
        jsonResponse(false, 'Sản phẩm không có trong giỏ hàng.');
        break;

    case 'clear':
        $_SESSION['cart'] = [];
        jsonResponse(true, 'Đã xóa toàn bộ giỏ hàng.');
        break;

    default:
        jsonResponse(false, 'Hành động không hợp lệ.');
}
