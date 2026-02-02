<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!defined('SITEURL')) define('SITEURL', 'http://localhost/wow-food/');

$host = "localhost";
$port = 3306; 
$username = "root";
$password = ""; 
$dbname = "food-oder";

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
?>