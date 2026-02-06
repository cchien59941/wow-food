<?php
include('../config/constants.php');

if (!isset($_SESSION['user_id']) || empty($_SESSION['user_id'])) {
    $_SESSION['redirect_after_login'] = SITEURL . 'user/checkout.php';
    header('location:' . SITEURL . 'user/login.php');
    exit;
}

$sizes = [];
$side_dishes = [];
$res = @$conn->query("SELECT id, name, price_add FROM tbl_size ORDER BY sort_order ASC, id ASC");
if ($res && $res->num_rows > 0) {
    while ($row = $res->fetch_assoc()) $sizes[$row['id']] = ['name' => $row['name'], 'price_add' => (float) $row['price_add']];
}
if (empty($sizes)) {
    $sizes = [1 => ['name' => 'Nhỏ', 'price_add' => 0], 2 => ['name' => 'Vừa', 'price_add' => 5], 3 => ['name' => 'Lớn', 'price_add' => 10]];
}
$res = @$conn->query("SELECT id, name, price FROM tbl_side_dish ORDER BY sort_order ASC, id ASC");
if ($res && $res->num_rows > 0) {
    while ($row = $res->fetch_assoc()) $side_dishes[$row['id']] = ['name' => $row['name'], 'price' => (float) $row['price']];
}
if (empty($side_dishes)) {
    $side_dishes = [1 => ['name' => 'Trứng ốp la', 'price' => 8], 2 => ['name' => 'Nem rán', 'price' => 10], 3 => ['name' => 'Khoai tây chiên', 'price' => 12], 4 => ['name' => 'Salad', 'price' => 6], 5 => ['name' => 'Nước ngọt', 'price' => 5], 6 => ['name' => 'Trà đá', 'price' => 3]];
}

$cart_items = [];
$cart_total = 0;
if (isset($_SESSION['cart']) && is_array($_SESSION['cart']) && count($_SESSION['cart']) > 0) {
    foreach ($_SESSION['cart'] as $cart_row) {
        $food_id = (int) ($cart_row['food_id'] ?? 0);
        $qty = max(1, (int) ($cart_row['qty'] ?? 1));
        $note = isset($cart_row['note']) ? trim($cart_row['note']) : '';
        $size_id = (int) (isset($cart_row['size_id']) ? $cart_row['size_id'] : 1);
        $side_dish_ids = (isset($cart_row['side_dish_ids']) && is_array($cart_row['side_dish_ids'])) ? $cart_row['side_dish_ids'] : [];
        $stmt = $conn->prepare("SELECT id, title, price, image_name FROM tbl_food WHERE id = ? AND active = 'Yes'");
        $stmt->bind_param("i", $food_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $food = $result->fetch_assoc();
        $stmt->close();
        if ($food) {
            $base_price = (float) $food['price'];
            $size_add = isset($sizes[$size_id]) ? $sizes[$size_id]['price_add'] : 0;
            $side_total = 0;
            foreach ((array) $side_dish_ids as $sid) {
                $side_total += isset($side_dishes[$sid]) ? $side_dishes[$sid]['price'] : 0;
            }
            $unit_price = $base_price + $size_add + $side_total;
            $subtotal = $unit_price * $qty;
            $cart_total += $subtotal;
            $cart_items[] = [
                'title' => $food['title'],
                'image_name' => $food['image_name'],
                'size_name' => isset($sizes[$size_id]) ? $sizes[$size_id]['name'] : 'Nhỏ',
                'side_names' => array_map(function($id) use ($side_dishes) { return isset($side_dishes[$id]) ? $side_dishes[$id]['name'] : ''; }, (array) $side_dish_ids),
                'unit_price' => $unit_price,
                'qty' => $qty,
                'note' => $note,
                'subtotal' => $subtotal
            ];
        }
    }
}

if (empty($cart_items)) {
    header('location:' . SITEURL . 'user/cart.php');
    exit;
}

$user_row = null;
$stmt = $conn->prepare("SELECT full_name, email, phone, address FROM tbl_user WHERE id = ?");
$stmt->bind_param("i", $_SESSION['user_id']);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows > 0) $user_row = $result->fetch_assoc();
$stmt->close();

function formatPrice($num) {
    return number_format($num, 0, ',', '.') . ' đ';
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thanh toán - WowFood</title>
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/checkout.css">
    <link href="https://fonts.googleapis.com/css2?family=Be+Vietnam+Pro:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>
<?php include('../partials-front/menu.php'); ?>

<div class="checkout-page">
    <div class="checkout-breadcrumb">
        <a href="<?php echo SITEURL; ?>">Trang chủ</a>
        <span class="sep">/</span>
        <a href="<?php echo SITEURL; ?>user/cart.php">Giỏ hàng</a>
        <span class="sep">/</span>
        <span class="current">Thanh toán</span>
    </div>

    <h1 class="checkout-title">
        <span class="checkout-title-icon">✓</span>
        Thanh toán đơn hàng
    </h1>

    <form id="checkoutForm" class="checkout-layout">
        <!-- Thanh cố định mobile: nút Đặt hàng luôn hiển thị -->
        <div class="checkout-mobile-sticky" id="checkoutMobileSticky" aria-hidden="true">
            <span class="sticky-total">Tổng: <span id="grandTotalSticky"><?php echo formatPrice($cart_total); ?></span></span>
            <button type="button" class="btn-place-order btn-place-order-sticky" id="btnPlaceOrderSticky" aria-label="Đặt hàng">
                <span class="btn-text">Đặt hàng</span>
                <span class="btn-price"><?php echo formatPrice($cart_total); ?></span>
            </button>
        </div>
        <div class="checkout-main">
            <section class="checkout-card checkout-form-card">
                <h2 class="card-heading">
                    <span class="card-icon">📍</span>
                    Thông tin giao hàng
                </h2>
                <div class="form-grid">
                    <div class="form-group full">
                        <label for="customer_name">Họ và tên <span class="required">*</span></label>
                        <input type="text" id="customer_name" name="customer_name" required
                               value="<?php echo htmlspecialchars($user_row['full_name'] ?? ''); ?>"
                               placeholder="Nguyễn Văn A">
                    </div>
                    <div class="form-group">
                        <label for="customer_contact">Số điện thoại <span class="required">*</span></label>
                        <input type="tel" id="customer_contact" name="customer_contact" required
                               value="<?php echo htmlspecialchars($user_row['phone'] ?? ''); ?>"
                               placeholder="0900 123 456">
                    </div>
                    <div class="form-group">
                        <label for="customer_email">Email <span class="required">*</span></label>
                        <input type="email" id="customer_email" name="customer_email" required
                               value="<?php echo htmlspecialchars($user_row['email'] ?? ''); ?>"
                               placeholder="email@example.com">
                    </div>
                    <div class="form-group full">
                        <label for="customer_address">Địa chỉ giao hàng <span class="required">*</span></label>
                        <textarea id="customer_address" name="customer_address" rows="3" required
                                  placeholder="Số nhà, đường, phường/xã, quận/huyện, tỉnh/thành phố"><?php echo htmlspecialchars($user_row['address'] ?? ''); ?></textarea>
                    </div>
                    <div class="form-group full">
                        <label for="order_note">Ghi chú đơn hàng</label>
                        <textarea id="order_note" name="order_note" rows="2"
                                  placeholder="Ghi chú cho cửa hàng (không bắt buộc)"></textarea>
                    </div>
                </div>
            </section>

            <section class="checkout-card payment-card">
                <h2 class="card-heading">
                    <span class="card-icon">💳</span>
                    Phương thức thanh toán
                </h2>
                <div class="payment-methods">
                    <label class="payment-option">
                        <input type="radio" name="payment_method" value="cash" checked>
                        <span class="payment-option-box">
                            <span class="payment-icon payment-icon-cash">💵</span>
                            <span class="payment-label">Tiền mặt</span>
                            <span class="payment-desc">Thanh toán khi nhận hàng (COD)</span>
                        </span>
                    </label>
                    <label class="payment-option">
                        <input type="radio" name="payment_method" value="bank">
                        <span class="payment-option-box">
                            <span class="payment-icon payment-icon-bank">🏦</span>
                            <span class="payment-label">Chuyển khoản ngân hàng</span>
                            <span class="payment-desc">VietQR / Ủy nhiệm chi – Quét mã hoặc chuyển khoản theo thông tin</span>
                        </span>
                    </label>
                    <label class="payment-option">
                        <input type="radio" name="payment_method" value="momo">
                        <span class="payment-option-box">
                            <span class="payment-icon payment-icon-momo">
                                <img src="<?php echo SITEURL; ?>image/momo.png" alt="MoMo" class="payment-icon-img" width="26" height="26" style="width:26px;height:26px;max-width:26px;max-height:26px;">
                            </span>
                            <span class="payment-label">Ví MoMo</span>
                            <span class="payment-desc">Thanh toán qua ví điện tử MoMo</span>
                        </span>
                    </label>
                    <label class="payment-option">
                        <input type="radio" name="payment_method" value="vnpay">
                        <span class="payment-option-box">
                            <span class="payment-icon payment-icon-vnpay">
                                <img src="<?php echo SITEURL; ?>image/vnpay.png" alt="VNPAY" class="payment-icon-img" width="26" height="26" style="width:26px;height:26px;max-width:26px;max-height:26px;">
                            </span>
                            <span class="payment-label">VNPAY</span>
                            <span class="payment-desc">Thanh toán thẻ ATM / QR qua cổng VNPAY</span>
                        </span>
                    </label>
                </div>
            </section>
        </div>

        <aside class="checkout-sidebar">
            <div class="checkout-card order-summary-card">
                <h2 class="card-heading">
                    <span class="card-icon">🛒</span>
                    Đơn hàng của bạn
                </h2>
                <div class="order-summary-list">
                    <?php foreach ($cart_items as $item):
                        $extras = array_filter([$item['size_name']]);
                        if (!empty($item['side_names'])) $extras = array_merge($extras, $item['side_names']);
                        $extrasStr = !empty($extras) ? ' (' . implode(', ', $extras) . ')' : '';
                    ?>
                    <div class="order-summary-item">
                        <?php if (!empty($item['image_name'])): ?>
                        <img src="<?php echo SITEURL; ?>image/food/<?php echo htmlspecialchars($item['image_name']); ?>" alt="" class="summary-item-img">
                        <?php else: ?>
                        <div class="summary-item-img summary-item-placeholder">🍽</div>
                        <?php endif; ?>
                        <div class="summary-item-info">
                            <span class="summary-item-name"><?php echo htmlspecialchars($item['title']); ?></span>
                            <span class="summary-item-meta">× <?php echo $item['qty']; ?><?php if ($extrasStr): ?> · <?php echo htmlspecialchars($extrasStr); ?><?php endif; ?></span>
                            <span class="summary-item-price"><?php echo formatPrice($item['subtotal']); ?></span>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
                <div class="order-summary-footer">
                    <div class="summary-row">
                        <span>Tạm tính</span>
                        <span><?php echo formatPrice($cart_total); ?></span>
                    </div>
                    <div class="summary-row summary-total">
                        <span>Tổng thanh toán</span>
                        <span id="grandTotal"><?php echo formatPrice($cart_total); ?></span>
                    </div>
                </div>
                <button type="submit" class="btn-place-order" id="btnPlaceOrder">
                    <span class="btn-text">Đặt hàng</span>
                    <span class="btn-price"><?php echo formatPrice($cart_total); ?></span>
                </button>
                <a href="<?php echo SITEURL; ?>user/cart.php" class="back-cart">← Quay lại giỏ hàng</a>
            </div>
        </aside>
    </form>
</div>

<?php include('../partials-front/footer.php'); ?>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
(function() {
    const SITEURL = '<?php echo SITEURL; ?>';
    const form = document.getElementById('checkoutForm');
    const btn = document.getElementById('btnPlaceOrder');
    const btnSticky = document.getElementById('btnPlaceOrderSticky');

    if (btnSticky) {
        btnSticky.addEventListener('click', function() {
            if (btn && !btn.disabled) form.requestSubmit();
        });
    }

    function syncStickyButton() {
        if (!btnSticky) return;
        btnSticky.disabled = btn ? btn.disabled : false;
        var textEl = btnSticky.querySelector('.btn-text');
        var priceEl = btnSticky.querySelector('.btn-price');
        if (btn && textEl) textEl.textContent = btn.querySelector('.btn-text').textContent;
        if (btn && priceEl) priceEl.textContent = btn.querySelector('.btn-price').textContent;
    }
    if (btn) {
        var obs = new MutationObserver(syncStickyButton);
        obs.observe(btn, { childList: true, subtree: true, attributes: true, attributeFilter: ['disabled'] });
    }

    form.addEventListener('submit', function(e) {
        e.preventDefault();
        const name = (document.getElementById('customer_name').value || '').trim();
        const contact = (document.getElementById('customer_contact').value || '').trim();
        const email = (document.getElementById('customer_email').value || '').trim();
        const address = (document.getElementById('customer_address').value || '').trim();
        const note = (document.getElementById('order_note').value || '').trim();
        const payment = document.querySelector('input[name="payment_method"]:checked');
        const paymentMethod = payment ? payment.value : 'cash';

        if (!name) { Swal.fire('Lỗi', 'Vui lòng nhập họ tên.', 'error'); return; }
        if (!contact) { Swal.fire('Lỗi', 'Vui lòng nhập số điện thoại.', 'error'); return; }
        if (!email) { Swal.fire('Lỗi', 'Vui lòng nhập email.', 'error'); return; }
        if (!address) { Swal.fire('Lỗi', 'Vui lòng nhập địa chỉ giao hàng.', 'error'); return; }

        btn.disabled = true;
        btn.querySelector('.btn-text').textContent = 'Đang xử lý...';
        if (btnSticky) { btnSticky.disabled = true; btnSticky.querySelector('.btn-text').textContent = 'Đang xử lý...'; }

        const fd = new FormData();
        fd.append('customer_name', name);
        fd.append('customer_contact', contact);
        fd.append('customer_email', email);
        fd.append('customer_address', address);
        fd.append('order_note', note);
        fd.append('payment_method', paymentMethod);

        fetch(SITEURL + 'api/place-order.php', { method: 'POST', body: fd })
            .then(r => r.json())
            .then(data => {
                if (data.success) {
                    // Thanh toán MoMo: tạo phiên và chuyển sang MoMo
                    if (paymentMethod === 'momo') {
                        const oc = data.order_code || '';
                        if (!oc) {
                            btn.disabled = false; btn.querySelector('.btn-text').textContent = 'Đặt hàng';
                            if (btnSticky) { btnSticky.disabled = false; btnSticky.querySelector('.btn-text').textContent = 'Đặt hàng'; }
                            Swal.fire('Lỗi', 'Thiếu mã đơn hàng để tạo thanh toán MoMo.', 'error');
                            return;
                        }
                        const fd2 = new FormData();
                        fd2.append('order_code', oc);
                        fetch(SITEURL + 'api/momo-create.php', { method: 'POST', body: fd2 })
                            .then(async (r2) => {
                                const text = await r2.text();
                                try { return JSON.parse(text); }
                                catch (e) { return { success:false, message:'API MoMo trả về không phải JSON', raw:text }; }
                            })
                            .then(m => {
                                if (m.success && (m.payUrl || m.deeplink)) {
                                    window.location.href = m.payUrl || m.deeplink;
                                } else {
                                    btn.disabled = false; btn.querySelector('.btn-text').textContent = 'Đặt hàng';
                                    if (btnSticky) { btnSticky.disabled = false; btnSticky.querySelector('.btn-text').textContent = 'Đặt hàng'; }
                                    Swal.fire('Lỗi', (m.message || 'Không thể tạo thanh toán MoMo.') + (m.raw ? ('\n\n' + String(m.raw).slice(0, 500)) : ''), 'error');
                                }
                            })
                            .catch(() => {
                                btn.disabled = false; btn.querySelector('.btn-text').textContent = 'Đặt hàng';
                                if (btnSticky) { btnSticky.disabled = false; btnSticky.querySelector('.btn-text').textContent = 'Đặt hàng'; }
                                Swal.fire('Lỗi', 'Không thể tạo thanh toán MoMo.', 'error');
                            });
                        return;
                    }
                    // Thanh toán VNPay: tạo URL thanh toán và chuyển sang cổng VNPAY
                    if (paymentMethod === 'vnpay') {
                        const oc = data.order_code || '';
                        if (!oc) {
                            btn.disabled = false; btn.querySelector('.btn-text').textContent = 'Đặt hàng';
                            if (btnSticky) { btnSticky.disabled = false; btnSticky.querySelector('.btn-text').textContent = 'Đặt hàng'; }
                            Swal.fire('Lỗi', 'Thiếu mã đơn hàng để tạo thanh toán VNPay.', 'error');
                            return;
                        }
                        const fd2 = new FormData();
                        fd2.append('order_code', oc);
                        fetch(SITEURL + 'api/vnpay-create.php', { method: 'POST', body: fd2 })
                            .then(r2 => r2.json())
                            .then(v => {
                                if (v.success && v.payUrl) {
                                    window.location.href = v.payUrl;
                                } else {
                                    btn.disabled = false; btn.querySelector('.btn-text').textContent = 'Đặt hàng';
                                    if (btnSticky) { btnSticky.disabled = false; btnSticky.querySelector('.btn-text').textContent = 'Đặt hàng'; }
                                    Swal.fire('Lỗi', v.message || 'Không thể tạo thanh toán VNPay.', 'error');
                                }
                            })
                            .catch(() => {
                                btn.disabled = false; btn.querySelector('.btn-text').textContent = 'Đặt hàng';
                                if (btnSticky) { btnSticky.disabled = false; btnSticky.querySelector('.btn-text').textContent = 'Đặt hàng'; }
                                Swal.fire('Lỗi', 'Không thể tạo thanh toán VNPay.', 'error');
                            });
                        return;
                    }
                    // Chuyển khoản ngân hàng (VietQR / ủy nhiệm chi): redirect sang trang hướng dẫn
                    if (paymentMethod === 'bank') {
                        if (data.redirect) {
                            window.location.href = data.redirect;
                        } else {
                            window.location.href = SITEURL + 'user/bank-transfer.php?order_code=' + encodeURIComponent(data.order_code || '');
                        }
                        return;
                    }

                    Swal.fire({
                        icon: 'success',
                        title: 'Đặt hàng thành công!',
                        html: 'Mã đơn hàng: <strong>' + (data.order_code || '') + '</strong><br>Chúng tôi sẽ liên hệ bạn sớm.',
                        confirmButtonColor: '#ff6b81'
                    }).then(() => {
                        window.location.href = data.redirect || (SITEURL + 'index.php');
                    });
                } else {
                    btn.disabled = false; btn.querySelector('.btn-text').textContent = 'Đặt hàng';
                    if (btnSticky) { btnSticky.disabled = false; btnSticky.querySelector('.btn-text').textContent = 'Đặt hàng'; }
                    Swal.fire('Lỗi', data.message || 'Không thể đặt hàng. Vui lòng thử lại.', 'error');
                }
            })
            .catch(err => {
                btn.disabled = false; btn.querySelector('.btn-text').textContent = 'Đặt hàng';
                if (btnSticky) { btnSticky.disabled = false; btnSticky.querySelector('.btn-text').textContent = 'Đặt hàng'; }
                Swal.fire('Lỗi', 'Có lỗi xảy ra. Vui lòng thử lại.', 'error');
            });
    });
})();
</script>
</body>
</html>
