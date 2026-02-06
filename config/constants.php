<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!defined('SITEURL')) define('SITEURL', 'http://localhost/wow-food/');

// if (!defined('SITEURL')) define('SITEURL', 'https://aliyah-evaporative-interrogatively.ngrok-free.dev/wow-food/'); // link deploy sử dụng tạm thời không xóa 


$host = "localhost";
$username = "root";
$port = 3306; 
$password = ""; 
$dbname = "food-order";

if (!isset($conn) || !($conn instanceof mysqli)) {
    $conn = new mysqli($host, $username, $password, $dbname, $port);
    if ($conn->connect_error) {
        die("Kết nối thất bại: " . $conn->connect_error);
    }
    $conn->set_charset("utf8mb4");
}

if (!defined('GHN_API_BASE')) define('GHN_API_BASE', 'https://dev-online-gateway.ghn.vn/shiip/public-api');
if (!defined('GHN_MASTER_DATA_URL')) define('GHN_MASTER_DATA_URL', GHN_API_BASE . '/master-data');
if (!defined('GHN_TOKEN')) define('GHN_TOKEN', '');
if (!defined('GHN_SHOP_ID')) define('GHN_SHOP_ID', 0);
if (!defined('GHN_FROM_DISTRICT_ID')) define('GHN_FROM_DISTRICT_ID', 0);
if (!defined('GHN_FROM_WARD_CODE')) define('GHN_FROM_WARD_CODE', '');
if (!defined('GHN_DEFAULT_WEIGHT_GRAM')) define('GHN_DEFAULT_WEIGHT_GRAM', 500);

// MoMo
if (!defined('MOMO_ENDPOINT')) define('MOMO_ENDPOINT', 'https://test-payment.momo.vn');
if (!defined('MOMO_PARTNER_CODE')) define('MOMO_PARTNER_CODE', 'MOMONPMB20210629');
if (!defined('MOMO_ACCESS_KEY')) define('MOMO_ACCESS_KEY', 'Q2XhhSdgpKUlQ4Ky');
if (!defined('MOMO_SECRET_KEY')) define('MOMO_SECRET_KEY', 'k6B53GQKSjktZGJBK2MyrDa7w9S6RyCf');
if (!defined('MOMO_REDIRECT_URL')) define('MOMO_REDIRECT_URL', SITEURL . 'user/momo-return.php');
if (!defined('MOMO_IPN_URL')) define('MOMO_IPN_URL', SITEURL . 'api/momo-ipn.php');

// VNPay (Sandbox)
if (!defined('VNPAY_TMN_CODE')) define('VNPAY_TMN_CODE', '6SWCSTD3');
if (!defined('VNPAY_HASH_SECRET')) define('VNPAY_HASH_SECRET', '0WDB0RA5OSLBGB2E14QQMHKYBKB3DAYU');
if (!defined('VNPAY_URL')) define('VNPAY_URL', 'https://sandbox.vnpayment.vn/paymentv2/vpcpay.html');
if (!defined('VNPAY_RETURN_URL')) define('VNPAY_RETURN_URL', SITEURL . 'user/vnpay-return.php');
if (!defined('VNPAY_IPN_URL')) define('VNPAY_IPN_URL', SITEURL . 'api/vnpay-ipn.php');

// Chuyển khoản ngân hàng / VietQR (ủy nhiệm chi) – BIDV
if (!defined('BANK_ACQ_ID')) define('BANK_ACQ_ID', 970418);
if (!defined('BANK_ACCOUNT_NO')) define('BANK_ACCOUNT_NO', '0983224809');
if (!defined('BANK_ACCOUNT_NAME')) define('BANK_ACCOUNT_NAME', 'WowFood');
if (!defined('BANK_NAME')) define('BANK_NAME', 'BIDV');
// URL ảnh VietQR: https://img.vietqr.io/image/{acqId}-{accountNo}-compact2.jpg?amount=...&addInfo=...&accountName=...
if (!defined('VIETQR_IMG_BASE')) define('VIETQR_IMG_BASE', 'https://img.vietqr.io/image/');
?>