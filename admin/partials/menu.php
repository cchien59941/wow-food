<?php
if (!defined('SITEURL')) {
    require_once __DIR__ . '/../../config/constants.php';
}
require_once __DIR__ . '/login-check.php';
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - wowFood</title>
    <link rel="stylesheet" href="<?php echo SITEURL; ?>css/admin.css">
</head>
<body class="admin-body">
<div class="admin-layout">
    <aside class="admin-sidebar">
        <div class="sidebar-brand">wowFood Admin</div>
        <nav class="sidebar-nav">
            <ul>
                <li><a href="index.php"><span class="menu-icon">🏠</span> Trang chủ</a></li>
                <li><a href="manage-admin.php"><span class="menu-icon">👥</span> Quản trị viên</a></li>
                <li><a href="#"><span class="menu-icon">👤</span> Người dùng</a></li>
                <li><a href="manage-category.php"><span class="menu-icon">📁</span> Danh mục</a></li>
                <li><a href="#"><span class="menu-icon">🍽️</span> Món ăn</a></li>
                <li><a href="manage-order.php"><span class="menu-icon">📦</span> Đơn hàng</a></li>
                <li><a href="manage-payment.php"><span class="menu-icon">💳</span> Thanh toán</a></li>
                <li><a href="refund.php"><span class="menu-icon">💰</span> Hoàn tiền</a></li>
                <li><a href="#" id="chatLinkAdmin" class="sidebar-link-chat"><span class="menu-icon">💬</span> Chat <span id="chatBadgeAdmin" class="chat-badge" style="display: none;">0</span></a></li>
                <li><a href="#" onclick="confirmLogout('logout.php'); return false;"><span class="menu-icon">🚪</span> Đăng xuất</a></li>
            </ul>
        </nav>
    </aside>
    <div class="admin-right">
        <header class="admin-topbar">
            <div class="topbar-left"><span class="topbar-title">Dashboard</span></div>
            <div class="topbar-right"><span class="topbar-user"><span class="topbar-user-icon">👤</span> Admin</span></div>
        </header>
        <script>
        function confirmLogout(logoutUrl) {
            if (typeof Swal !== 'undefined') {
                Swal.fire({ title: 'Bạn có chắc chắn muốn đăng xuất?', text: 'Bạn sẽ phải đăng nhập lại để tiếp tục sử dụng', icon: 'question', showCancelButton: true, confirmButtonColor: '#3085d6', cancelButtonColor: '#d33', confirmButtonText: 'Đăng xuất', cancelButtonText: 'Hủy' }).then(function(r) { if (r.isConfirmed) window.location.href = logoutUrl; });
            } else { window.location.href = logoutUrl; }
        }
        </script>
