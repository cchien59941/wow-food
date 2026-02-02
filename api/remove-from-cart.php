<?php
header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/../config/constants.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Dữ liệu không hợp lệ']);
    exit;
}

$cart_id = isset($_POST['cart_id']) ? trim($_POST['cart_id']) : '';

if (isset($_SESSION['cart']) && is_array($_SESSION['cart'])) {
    $_SESSION['cart'] = array_values(array_filter($_SESSION['cart'], function($item) use ($cart_id) {
        return (isset($item['cart_id']) ? $item['cart_id'] : '') !== $cart_id;
    }));
}

echo json_encode(['success' => true, 'message' => 'Đã xóa món khỏi giỏ hàng']);
