<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!defined('SITEURL')) {
    define('SITEURL', 'http://localhost/wow-food/');
}

$host = "localhost";
<<<<<<< Updated upstream
=======
$port = 3306; // Port MySQL (mặc định là 3306, nếu dùng 3307 thì thay đổi)
>>>>>>> Stashed changes
$username = "root";
$password = "";
$dbname = "food-oder";


$conn = new mysqli($host, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

$conn->set_charset("utf8mb4");
?>
