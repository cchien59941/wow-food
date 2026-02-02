<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}


define('SITEURL', 'http://localhost/wow-food/');


$host = "localhost";
$username = "root";
$port = 3307; 
$password = ""; 
$dbname = "food-oder";

// Kết nối với port
$conn = new mysqli($host, $username, $password, $dbname, $port);

if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

$conn->set_charset("utf8mb4");


define('GHN_API_BASE', 'https://dev-online-gateway.ghn.vn/shiip/public-api');
define('GHN_MASTER_DATA_URL', GHN_API_BASE . '/master-data');
define('GHN_TOKEN', '');
define('GHN_SHOP_ID', 0);
define('GHN_FROM_DISTRICT_ID', 0);
define('GHN_FROM_WARD_CODE', '');
define('GHN_DEFAULT_WEIGHT_GRAM', 500);
?>