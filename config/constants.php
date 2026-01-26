<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Site URL - Thay đổi theo domain của bạn
define('SITEURL', 'http://localhost/wow-food/');

// ============================================
// CẤU HÌNH DATABASE
// ============================================
// Nếu MySQL của bạn chạy trên port khác (ví dụ: 3307), 
// hãy thay đổi giá trị $port bên dưới
$host = "localhost";
$port = 3306; // Port MySQL (mặc định là 3306, nếu dùng 3307 thì thay đổi)
$username = "root";
$password = ""; // Nhập mật khẩu MySQL nếu có
$dbname = "food-order";

// Kết nối với port
$conn = new mysqli($host, $username, $password, $dbname, $port);

if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

$conn->set_charset("utf8mb4");

// ============================================
// GHN Shipping (cấu hình bắt buộc để tính phí ship)
// ============================================
define('GHN_API_BASE', 'https://dev-online-gateway.ghn.vn/shiip/public-api');
define('GHN_MASTER_DATA_URL', GHN_API_BASE . '/master-data');
// TODO: Điền token và shop id của GHN
define('GHN_TOKEN', '');
define('GHN_SHOP_ID', 0);
// Địa chỉ kho lấy hàng (GHN yêu cầu)
define('GHN_FROM_DISTRICT_ID', 0);
define('GHN_FROM_WARD_CODE', '');
// Khối lượng mặc định cho mỗi món (gram)
define('GHN_DEFAULT_WEIGHT_GRAM', 500);
?>