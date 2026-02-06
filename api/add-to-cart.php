<?php
header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/../config/constants.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST['food_id'])) {
    echo json_encode(['success' => false, 'message' => 'Dữ liệu không hợp lệ']);
    exit;
}

if (!isset($_SESSION['user_id']) || empty($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Vui lòng đăng nhập để sử dụng giỏ hàng']);
    exit;
}

$food_id = (int) $_POST['food_id'];
$quantity = isset($_POST['quantity']) ? max(1, (int) $_POST['quantity']) : 1;
$note = isset($_POST['note']) ? trim($_POST['note']) : '';
$size_id = isset($_POST['size_id']) ? (int) $_POST['size_id'] : 0;
$side_dish_ids = [];
if (isset($_POST['side_dish_ids']) && is_string($_POST['side_dish_ids'])) {
    $side_dish_ids = array_map('intval', array_filter(explode(',', $_POST['side_dish_ids'])));
} elseif (isset($_POST['side_dish_ids']) && is_array($_POST['side_dish_ids'])) {
    $side_dish_ids = array_map('intval', $_POST['side_dish_ids']);
}

// Kiểm tra món ăn
$stmt = $conn->prepare("SELECT id, title, price, image_name FROM tbl_food WHERE id = ? AND active = 'Yes'");
$stmt->bind_param("i", $food_id);
$stmt->execute();
$result = $stmt->get_result();
$food = $result->fetch_assoc();
$stmt->close();

if (!$food) {
    echo json_encode(['success' => false, 'message' => 'Món ăn không tồn tại hoặc đã ngừng bán']);
    exit;
}

// Lấy giá size (nếu có bảng)
$size_price = 0;
if ($size_id > 0) {
    $stmt = $conn->prepare("SELECT price_add FROM tbl_size WHERE id = ?");
    if ($stmt) {
        $stmt->bind_param("i", $size_id);
        $stmt->execute();
        $r = $stmt->get_result();
        if ($row = $r->fetch_assoc()) $size_price = (float) $row['price_add'];
        $stmt->close();
    }
}

// Lấy tổng giá món kèm
$side_price = 0;
if (!empty($side_dish_ids)) {
    $placeholders = implode(',', array_fill(0, count($side_dish_ids), '?'));
    $stmt = $conn->prepare("SELECT SUM(price) as total FROM tbl_side_dish WHERE id IN ($placeholders)");
    if ($stmt) {
        $types = str_repeat('i', count($side_dish_ids));
        $stmt->bind_param($types, ...$side_dish_ids);
        $stmt->execute();
        $r = $stmt->get_result();
        if ($row = $r->fetch_assoc()) $side_price = (float) $row['total'];
        $stmt->close();
    }
}

// Fallback nếu không có bảng - dùng mảng mặc định
if ($size_id > 0 && $size_price == 0) {
    $default_sizes = [1=>0, 2=>5, 3=>10];
    $size_price = $default_sizes[$size_id] ?? 0;
}
if (!empty($side_dish_ids) && $side_price == 0) {
    $default_sides = [1=>8, 2=>10, 3=>12, 4=>6, 5=>5, 6=>3, 7=>8];
    foreach ($side_dish_ids as $sid) $side_price += $default_sides[$sid] ?? 0;
}

if (!isset($_SESSION['cart'])) $_SESSION['cart'] = [];

$_SESSION['cart'][] = [
    'cart_id' => uniqid('c'),
    'food_id' => $food_id,
    'qty' => $quantity,
    'note' => $note,
    'size_id' => $size_id,
    'side_dish_ids' => $side_dish_ids
];

$total_items = 0;
foreach ($_SESSION['cart'] as $item) $total_items += $item['qty'];

echo json_encode([
    'success' => true,
    'message' => 'Đã thêm vào giỏ hàng thành công!',
    'cart_count' => $total_items
]);
