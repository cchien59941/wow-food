<?php
ob_start();
require_once __DIR__ . '/../config/constants.php';
ob_end_clean();
header('Content-Type: application/json; charset=utf-8');

$unread_count = 0;
if (!empty($_SESSION['user_id']) && isset($conn) && $conn instanceof mysqli) {
    $user_id = (int) $_SESSION['user_id'];
    $stmt = $conn->prepare("SELECT COUNT(*) AS c FROM tbl_chat WHERE user_id = ? AND sender_type = 'admin' AND (is_read = 0 OR is_read IS NULL)");
    if ($stmt) {
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $row = $stmt->get_result()->fetch_assoc();
        if ($row) $unread_count = (int) $row['c'];
        $stmt->close();
    }
}

echo json_encode(['success' => true, 'unread_count' => $unread_count]);
