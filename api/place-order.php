<?php
header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/../config/constants.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Phương thức không hợp lệ']);
    exit;
}

if (!isset($_SESSION['user_id']) || empty($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Vui lòng đăng nhập để đặt hàng']);
    exit;
}

$customer_name = isset($_POST['customer_name']) ? trim($_POST['customer_name']) : '';
$customer_contact = isset($_POST['customer_contact']) ? trim($_POST['customer_contact']) : '';
$customer_email = isset($_POST['customer_email']) ? trim($_POST['customer_email']) : '';
$customer_address = isset($_POST['customer_address']) ? trim($_POST['customer_address']) : '';
$order_note = isset($_POST['order_note']) ? trim($_POST['order_note']) : '';
$payment_method = isset($_POST['payment_method']) ? trim($_POST['payment_method']) : 'cash';

if (empty($customer_name) || empty($customer_contact) || empty($customer_email) || empty($customer_address)) {
    echo json_encode(['success' => false, 'message' => 'Vui lòng điền đầy đủ thông tin giao hàng']);
    exit;
}

if (!isset($_SESSION['cart']) || !is_array($_SESSION['cart']) || count($_SESSION['cart']) === 0) {
    echo json_encode(['success' => false, 'message' => 'Giỏ hàng trống. Vui lòng thêm món trước khi đặt hàng.']);
    exit;
}

$sizes = [];
$side_dishes = [];
$res = @$conn->query("SELECT id, name, price_add FROM tbl_size ORDER BY sort_order ASC, id ASC");
if ($res && $res->num_rows > 0) {
    while ($row = $res->fetch_assoc()) $sizes[$row['id']] = ['name' => $row['name'], 'price_add' => (float) $row['price_add']];
}
if (empty($sizes)) {
    $sizes = [1 => ['name' => 'Nhỏ', 'price_add' => 0], 2 => ['name' => 'Vừa', 'price_add' => 5], 3 => ['name' => 'Lớn', 'price_add' => 10]];
}
$res = @$conn->query("SELECT id, name, price FROM tbl_side_dish ORDER BY sort_order ASC, id ASC");
if ($res && $res->num_rows > 0) {
    while ($row = $res->fetch_assoc()) $side_dishes[$row['id']] = ['name' => $row['name'], 'price' => (float) $row['price']];
}
if (empty($side_dishes)) {
    $side_dishes = [1 => ['name' => 'Trứng ốp la', 'price' => 8], 2 => ['name' => 'Nem rán', 'price' => 10], 3 => ['name' => 'Khoai tây chiên', 'price' => 12], 4 => ['name' => 'Salad', 'price' => 6], 5 => ['name' => 'Nước ngọt', 'price' => 5], 6 => ['name' => 'Trà đá', 'price' => 3]];
}

$cart_total = 0;
$cart_qty_total = 0;
$food_parts = [];
$first_price = 0;

foreach ($_SESSION['cart'] as $cart_row) {
    $food_id = (int) ($cart_row['food_id'] ?? 0);
    $qty = max(1, (int) ($cart_row['qty'] ?? 1));
    $size_id = (int) (isset($cart_row['size_id']) ? $cart_row['size_id'] : 1);
    $side_dish_ids = (isset($cart_row['side_dish_ids']) && is_array($cart_row['side_dish_ids'])) ? $cart_row['side_dish_ids'] : [];
    $stmt = $conn->prepare("SELECT id, title, price FROM tbl_food WHERE id = ? AND active = 'Yes'");
    $stmt->bind_param("i", $food_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $food = $result->fetch_assoc();
    $stmt->close();
    if ($food) {
        $base_price = (float) $food['price'];
        $size_add = isset($sizes[$size_id]) ? $sizes[$size_id]['price_add'] : 0;
        $side_total = 0;
        foreach ((array) $side_dish_ids as $sid) {
            $side_total += isset($side_dishes[$sid]) ? $side_dishes[$sid]['price'] : 0;
        }
        $unit_price = $base_price + $size_add + $side_total;
        $subtotal = $unit_price * $qty;
        $cart_total += $subtotal;
        $cart_qty_total += $qty;
        if ($first_price == 0) $first_price = $unit_price;
        $food_parts[] = $food['title'] . ' x' . $qty;
    }
}

$food_summary = implode(', ', $food_parts);
if (mb_strlen($food_summary) > 150) {
    $food_summary = mb_substr($food_summary, 0, 147) . '...';
}

$order_code = 'ORD' . date('Ymd') . strtoupper(substr(uniqid(), -6));

$status = in_array($payment_method, ['momo', 'vnpay', 'bank'], true) ? 'Pending Payment' : 'Ordered';
$stmt = $conn->prepare("INSERT INTO tbl_order (order_code, user_id, food, price, qty, total, order_date, status, customer_name, customer_contact, customer_email, customer_address) VALUES (?, ?, ?, ?, ?, ?, NOW(), ?, ?, ?, ?, ?)");
$stmt->bind_param("sisdidsssss", $order_code, $_SESSION['user_id'], $food_summary, $first_price, $cart_qty_total, $cart_total, $status, $customer_name, $customer_contact, $customer_email, $customer_address);

if (!$stmt->execute()) {
    $stmt->close();
    echo json_encode(['success' => false, 'message' => 'Không thể tạo đơn hàng. Vui lòng thử lại.']);
    exit;
}
$stmt->close();

// Chỉ xóa giỏ khi thanh toán tiền mặt (momo/vnpay xóa sau khi thanh toán thành công; bank chuyển khoản giữ giỏ đến khi user xem xong)
if ($payment_method !== 'momo' && $payment_method !== 'vnpay') {
    $_SESSION['cart'] = [];
}
if (isset($_SESSION['redirect_after_login'])) unset($_SESSION['redirect_after_login']);

$redirect = SITEURL . 'index.php';
if ($payment_method === 'bank') {
    $redirect = SITEURL . 'user/bank-transfer.php?order_code=' . urlencode($order_code);
} elseif (file_exists(__DIR__ . '/../user/order-history.php')) {
    $redirect = SITEURL . 'user/order-history.php';
}

echo json_encode([
    'success' => true,
    'message' => 'Đặt hàng thành công!',
    'order_code' => $order_code,
    'redirect' => $redirect
]);
