<?php
header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/../config/constants.php';

$count = 0;
if (isset($_SESSION['user_id']) && isset($_SESSION['cart']) && is_array($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as $item) {
        $count += isset($item['qty']) ? (int) $item['qty'] : 0;
    }
}

echo json_encode(['count' => $count]);
