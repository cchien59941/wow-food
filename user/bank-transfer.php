<?php
require_once __DIR__ . '/../config/constants.php';

if (!isset($_SESSION['user_id']) || empty($_SESSION['user_id'])) {
    $_SESSION['redirect_after_login'] = SITEURL . 'user/checkout.php';
    header('Location: ' . SITEURL . 'user/login.php');
    exit;
}

$order_code = isset($_GET['order_code']) ? trim($_GET['order_code']) : '';
if ($order_code === '') {
    header('Location: ' . SITEURL . 'user/order-history.php');
    exit;
}

$stmt = $conn->prepare("SELECT id, order_code, user_id, total, status, customer_name FROM tbl_order WHERE order_code = ? LIMIT 1");
$stmt->bind_param("s", $order_code);
$stmt->execute();
$order = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$order || (int)$order['user_id'] !== (int)$_SESSION['user_id']) {
    header('Location: ' . SITEURL . 'user/order-history.php');
    exit;
}

$amount = (int) round((float)$order['total']);
$acq_id = (int) BANK_ACQ_ID;
$account_no = BANK_ACCOUNT_NO;
$account_name = BANK_ACCOUNT_NAME;
$bank_name = BANK_NAME;

// URL ảnh VietQR (compact2: QR + thông tin; compact: gọn)
$add_info = $order_code;
$vietqr_img = VIETQR_IMG_BASE . $acq_id . '-' . $account_no . '-compact2.jpg'
    . '?amount=' . $amount
    . '&addInfo=' . urlencode($add_info)
    . '&accountName=' . urlencode($account_name);

function formatPrice($n) {
    return number_format($n, 0, ',', '.') . ' đ';
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chuyển khoản ngân hàng - WowFood</title>
    <link rel="stylesheet" href="<?php echo SITEURL; ?>css/style.css">
    <link rel="stylesheet" href="<?php echo SITEURL; ?>css/checkout.css">
    <link href="https://fonts.googleapis.com/css2?family=Be+Vietnam+Pro:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        .bank-transfer-page { max-width: 560px; margin: 40px auto 60px; padding: 0 16px; }
        .bank-transfer-card { background: #fff; border-radius: 16px; box-shadow: 0 4px 20px rgba(0,0,0,0.08); overflow: hidden; margin-bottom: 24px; }
        .bank-transfer-card h2 { margin: 0; padding: 20px 24px; font-size: 1.15rem; color: #2f3542; border-bottom: 1px solid #eee; display: flex; align-items: center; gap: 10px; }
        .bank-transfer-card .body { padding: 24px; }
        .bank-qr-wrap { text-align: center; padding: 20px 0; background: #f8f9fa; border-radius: 12px; margin-bottom: 24px; }
        .bank-qr-wrap img { max-width: 260px; height: auto; border-radius: 8px; }
        .bank-qr-note { font-size: 0.9rem; color: #57606f; margin-top: 12px; }
        .uat-table { width: 100%; border-collapse: collapse; font-size: 0.95rem; }
        .uat-table th { text-align: left; padding: 10px 0; color: #57606f; font-weight: 500; width: 140px; }
        .uat-table td { padding: 10px 0; color: #2f3542; }
        .uat-table .value { font-weight: 600; word-break: break-all; }
        .copy-btn { margin-top: 4px; padding: 6px 12px; font-size: 0.85rem; background: #ff6b81; color: #fff; border: none; border-radius: 6px; cursor: pointer; }
        .copy-btn:hover { background: #ff4757; }
        .bank-transfer-footer { text-align: center; margin-top: 24px; }
        .bank-transfer-footer a { display: inline-block; margin: 0 6px; padding: 12px 20px; background: #ff6b81; color: #fff; text-decoration: none; border-radius: 8px; }
        .bank-transfer-footer a.secondary { background: #95a5a6; }
        .order-code-big { font-size: 1.25rem; color: #ff6b81; font-weight: 700; }
    </style>
</head>
<body>
<?php include(__DIR__ . '/../partials-front/menu.php'); ?>

<div class="checkout-page">
    <div class="checkout-breadcrumb">
        <a href="<?php echo SITEURL; ?>">Trang chủ</a>
        <span class="sep">/</span>
        <a href="<?php echo SITEURL; ?>user/checkout.php">Thanh toán</a>
        <span class="sep">/</span>
        <span class="current">Chuyển khoản ngân hàng</span>
    </div>

    <h1 class="checkout-title">
        <span class="checkout-title-icon">🏦</span>
        Chuyển khoản ngân hàng / VietQR
    </h1>

    <div class="bank-transfer-page">
        <div class="bank-transfer-card">
            <h2>📱 Quét mã VietQR</h2>
            <div class="body">
                <p class="order-code-big">Mã đơn hàng: <?php echo htmlspecialchars($order_code); ?></p>
                <p style="margin: 8px 0 16px; color: #57606f;">Số tiền chuyển: <strong><?php echo formatPrice($amount); ?></strong></p>
                <div class="bank-qr-wrap">
                    <img src="<?php echo htmlspecialchars($vietqr_img); ?>" alt="VietQR" id="vietqrImg">
                    <p class="bank-qr-note">Mở app ngân hàng → Quét QR → Kiểm tra số tiền & nội dung → Xác nhận chuyển khoản.</p>
                </div>
            </div>
        </div>

        <div class="bank-transfer-card">
            <h2>📋 chuyển khoản thủ công </h2>
            <div class="body">
                <p style="color: #57606f; margin-bottom: 16px;">Nếu không quét được QR, chuyển khoản theo thông tin bên dưới. <strong>Ghi đúng nội dung chuyển khoản</strong> để đối soát.</p>
                <table class="uat-table">
                    <tr>
                        <th>Ngân hàng</th>
                        <td class="value"><?php echo htmlspecialchars($bank_name); ?></td>
                    </tr>
                    <tr>
                        <th>Số tài khoản</th>
                        <td>
                            <span class="value" id="copyAccountNo"><?php echo htmlspecialchars($account_no); ?></span>
                            <button type="button" class="copy-btn" onclick="copyText('copyAccountNo')">Sao chép</button>
                        </td>
                    </tr>
                    <tr>
                        <th>Chủ tài khoản</th>
                        <td>
                            <span class="value" id="copyAccountName"><?php echo htmlspecialchars($account_name); ?></span>
                            <button type="button" class="copy-btn" onclick="copyText('copyAccountName')">Sao chép</button>
                        </td>
                    </tr>
                    <tr>
                        <th>Số tiền</th>
                        <td class="value"><?php echo formatPrice($amount); ?></td>
                    </tr>
                    <tr>
                        <th>Nội dung CK</th>
                        <td>
                            <span class="value" id="copyAddInfo"><?php echo htmlspecialchars($add_info); ?></span>
                            <button type="button" class="copy-btn" onclick="copyText('copyAddInfo')">Sao chép</button>
                        </td>
                    </tr>
                </table>
            </div>
        </div>

        <div class="bank-transfer-footer">
            <a href="<?php echo SITEURL; ?>user/order-history.php">Xem đơn hàng</a>
            <a href="<?php echo SITEURL; ?>" class="secondary">Về trang chủ</a>
        </div>
    </div>
</div>

<?php include(__DIR__ . '/../partials-front/footer.php'); ?>
<script>
function copyText(id) {
    var el = document.getElementById(id);
    var text = el.textContent || el.innerText;
    if (navigator.clipboard && navigator.clipboard.writeText) {
        navigator.clipboard.writeText(text).then(function() {
            alert('Đã sao chép: ' + text);
        });
    } else {
        var ta = document.createElement('textarea');
        ta.value = text;
        document.body.appendChild(ta);
        ta.select();
        document.execCommand('copy');
        document.body.removeChild(ta);
        alert('Đã sao chép: ' + text);
    }
}
</script>
</body>
</html>
