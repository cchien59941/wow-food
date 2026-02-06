<?php
require_once('../config/constants.php');
require_once('login-check.php');
?>

<html>

<head>
    <title>food oder</title>

    <link rel="stylesheet" href="../css/admin.css">
</head>

<body>

    <div class="menu text-center">
        <div class="wrapper">
            <ul>
                <li><a href="index.php">Trang chủ</a></li>
                <li><a href="manage-admin.php">Quản trị viên</a></li>
                <li><a href="#">Người dùng</a></li>
                <li><a href="manage-category.php">Danh mục</a></li>
                <li><a href="#">Món ăn</a></li>
                <li><a href="#">Đơn hàng</a></li>
                <li><a href="manage-payment.php">Thanh toán</a></li>
                <li><a href="refund.php">Hoàn tiền</a></li>
                <li><a href="#" id="chatLinkAdmin" style="position: relative;">
                        Chat
                        <span id="chatBadgeAdmin" class="chat-badge" style="display: none;">0</span>
                    </a>
                </li>
                <li><a href="#" onclick="confirmLogout('logout.php'); return false;">Đăng xuất</a></li>
            </ul>


            <script>
            function confirmLogout(logoutUrl) {
                Swal.fire({
                    title: 'Bạn có chắc chắn muốn đăng xuất?',
                    text: 'Bạn sẽ phải đăng nhập lại để tiếp tục sử dụng',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Đăng xuất',
                    cancelButtonText: 'Hủy'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = logoutUrl;
                    }
                });
            }
            </script>
        </div>
    </div>