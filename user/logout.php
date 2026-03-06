<?php
require_once __DIR__ . '/../config/constants.php';

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

$_SESSION = [];

if (ini_get('session.use_cookies')) {
    $params = session_get_cookie_params();
    setcookie(
        session_name(),
        '',
        time() - 42000,
        $params['path'] ?? '/',
        $params['domain'] ?? '',
        (bool) ($params['secure'] ?? false),
        (bool) ($params['httponly'] ?? true)
    );
}


session_destroy();


session_start();
session_regenerate_id(true);
$_SESSION['login-success'] = 'Đăng xuất thành công!';


$redirectUrl = SITEURL . 'user/login.php';

if (!headers_sent()) {
    header('Location: ' . $redirectUrl);
    exit;
}


