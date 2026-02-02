<?php
include('../config/constants.php');
include('../partials-front/menu.php');

// Load sizes & side dishes
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
$cart_count = 0;

if (isset($_SESSION['cart']) && is_array($_SESSION['cart']) && count($_SESSION['cart']) > 0) {
    foreach ($_SESSION['cart'] as $idx => $cart_row) {
        $food_id = (int) ($cart_row['food_id'] ?? 0);
        if (empty($cart_row['cart_id'])) {
            $_SESSION['cart'][$idx]['cart_id'] = uniqid('c');
        }
        $cart_id = $_SESSION['cart'][$idx]['cart_id'];
        $qty = max(1, (int) (isset($cart_row['qty']) ? $cart_row['qty'] : 1));
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
            $cart_count += $qty;
            $cart_items[] = [
                'cart_id' => $cart_id,
                'food_id' => $food['id'],
                'title' => $food['title'],
                'image_name' => $food['image_name'],
                'base_price' => $base_price,
                'size_id' => $size_id,
                'size_name' => isset($sizes[$size_id]) ? $sizes[$size_id]['name'] : 'Nhỏ',
                'side_dish_ids' => $side_dish_ids,
                'side_names' => array_map(function($id) use ($side_dishes) { return isset($side_dishes[$id]) ? $side_dishes[$id]['name'] : ''; }, (array) $side_dish_ids),
                'unit_price' => $unit_price,
                'qty' => $qty,
                'note' => $note,
                'subtotal' => $subtotal
            ];
        }
    }
}

function formatPrice($num) {
    return number_format($num, 0, ',', '.') . ' đ';
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Giỏ hàng - WowFood</title>
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/cart.css">
</head>
<body>
    <div class="cart-container">
        <div class="cart-header">
            <h1>Giỏ hàng</h1>
            <span class="cart-badge" id="cartCount"><?php echo $cart_count; ?> món</span>
        </div>
        <div id="cartItems">
            <?php if (empty($cart_items)): ?>
                <div class="empty-cart">
                    <div class="empty-cart-icon">🛒</div>
                    <h2>Giỏ hàng trống</h2>
                    <p>Hãy thêm món ăn từ thực đơn nhé!</p>
                    <a href="<?php echo SITEURL; ?>food.php">Xem thực đơn</a>
                </div>
            <?php else: ?>
                <?php foreach ($cart_items as $item): ?>
                <div class="cart-item" data-cart-id="<?php echo htmlspecialchars($item['cart_id']); ?>" data-base-price="<?php echo $item['base_price']; ?>" data-item-name="<?php echo htmlspecialchars($item['title']); ?>">
                    <?php if (!empty($item['image_name'])): ?>
                        <img src="<?php echo SITEURL; ?>image/food/<?php echo htmlspecialchars($item['image_name']); ?>" alt="<?php echo htmlspecialchars($item['title']); ?>" class="cart-item-image">
                    <?php else: ?>
                        <div class="cart-item-image-placeholder">Chưa có ảnh</div>
                    <?php endif; ?>
                    <div class="cart-item-info">
                        <div class="cart-item-name"><?php echo htmlspecialchars($item['title']); ?></div>
                        <div class="cart-item-price item-price" data-cart-id="<?php echo htmlspecialchars($item['cart_id']); ?>">
                            <?php echo formatPrice($item['unit_price']); ?> × <?php echo $item['qty']; ?> = <strong><?php echo formatPrice($item['subtotal']); ?></strong>
                        </div>
                        <div class="cart-collapse open">
                            <div class="cart-collapse-head" onclick="this.parentElement.classList.toggle('open')">
                                <span class="cart-collapse-icon">▼</span>
                                <label>Kích thước</label>
                            </div>
                            <div class="cart-collapse-body"><div class="cart-size-select">
                            <?php foreach ($sizes as $sid => $s): ?>
                                <span class="cart-size-opt <?php echo $sid == $item['size_id'] ? 'selected' : ''; ?>" 
                                      data-size-id="<?php echo $sid; ?>" data-size-name="<?php echo htmlspecialchars($s['name']); ?>" data-price-add="<?php echo $s['price_add']; ?>"
                                      data-cart-id="<?php echo htmlspecialchars($item['cart_id']); ?>"><?php echo htmlspecialchars($s['name']); ?> (+<?php echo formatPrice($s['price_add']); ?>)</span>
                            <?php endforeach; ?>
                            </div></div>
                        </div>
                        <div class="cart-collapse">
                            <div class="cart-collapse-head" onclick="this.parentElement.classList.toggle('open')">
                                <span class="cart-collapse-icon">▼</span>
                                <label>Món/nước kèm</label>
                            </div>
                            <div class="cart-collapse-body"><div class="cart-sides-select">
                                <?php foreach ($side_dishes as $sid => $sd): ?>
                                <div class="cart-side-item">
                                    <input type="checkbox" class="cart-side-cb" data-side-id="<?php echo $sid; ?>" data-side-name="<?php echo htmlspecialchars($sd['name']); ?>" data-price="<?php echo $sd['price']; ?>"
                                           data-cart-id="<?php echo htmlspecialchars($item['cart_id']); ?>"
                                           <?php echo in_array($sid, $item['side_dish_ids']) ? 'checked' : ''; ?>>
                                    <label><?php echo htmlspecialchars($sd['name']); ?> (+<?php echo formatPrice($sd['price']); ?>)</label>
                                </div>
                                <?php endforeach; ?>
                            </div></div>
                        </div>
                        <div class="cart-item-controls">
                            <button class="quantity-btn" onclick="changeQty('<?php echo $item['cart_id']; ?>', -1)">-</button>
                            <input type="number" class="quantity-input item-qty" data-cart-id="<?php echo htmlspecialchars($item['cart_id']); ?>" value="<?php echo $item['qty']; ?>" min="1" onchange="setQty('<?php echo $item['cart_id']; ?>', this.value)">
                            <button class="quantity-btn" onclick="changeQty('<?php echo $item['cart_id']; ?>', 1)">+</button>
                            <button class="remove-btn" onclick="removeItem('<?php echo $item['cart_id']; ?>')">Xóa</button>
                        </div>
                        <input type="text" class="note-input" placeholder="Ghi chú..." value="<?php echo htmlspecialchars($item['note']); ?>"
                               data-cart-id="<?php echo htmlspecialchars($item['cart_id']); ?>" onblur="updateNote('<?php echo $item['cart_id']; ?>', this.value)">
                    </div>
                </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
        <?php if (!empty($cart_items)): ?>
        <div class="cart-summary" id="cartSummary">
            <div class="summary-title">Chi tiết đơn hàng</div>
            <div class="summary-details" id="summaryDetails">
                <?php foreach ($cart_items as $item): 
                    $extras = array_filter([$item['size_name']]);
                    if (!empty($item['side_names'])) $extras = array_merge($extras, $item['side_names']);
                    $extrasStr = !empty($extras) ? ' (' . implode(', ', $extras) . ')' : '';
                ?>
                <div class="summary-detail-item">
                    <span class="summary-detail-name"><?php echo htmlspecialchars($item['title']); ?> <span class="summary-detail-qty">× <?php echo $item['qty']; ?></span><?php if ($extrasStr): ?><span class="summary-detail-extras"><?php echo htmlspecialchars($extrasStr); ?></span><?php endif; ?></span>
                    <span class="summary-detail-price"><?php echo formatPrice($item['subtotal']); ?></span>
                </div>
                <?php endforeach; ?>
            </div>
            <div class="summary-row summary-total">
                <span>Tổng cộng:</span>
                <span id="cartTotal"><?php echo formatPrice($cart_total); ?></span>
            </div>
            <button class="checkout-btn" onclick="Swal.fire('Thông báo', 'Chức năng thanh toán đang phát triển', 'info')">Thanh toán</button>
        </div>
        <?php endif; ?>
    </div>
    <?php include('../partials-front/footer.php'); ?>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        const SITEURL = '<?php echo SITEURL; ?>';

        function getItemData(cartId) {
            const item = document.querySelector('.cart-item[data-cart-id="' + cartId + '"]');
            if (!item) return null;
            const basePrice = parseFloat(item.dataset.basePrice) || 0;
            let sizeAdd = 0;
            const selSize = item.querySelector('.cart-size-opt.selected');
            if (selSize) sizeAdd = parseFloat(selSize.dataset.priceAdd) || 0;
            let sideTotal = 0;
            item.querySelectorAll('.cart-side-cb:checked').forEach(cb => {
                if (cb.dataset.cartId === cartId) sideTotal += parseFloat(cb.dataset.price) || 0;
            });
            const qty = parseInt(item.querySelector('.item-qty[data-cart-id="' + cartId + '"]')?.value) || 1;
            const unitPrice = basePrice + sizeAdd + sideTotal;
            const name = item.dataset.itemName || item.querySelector('.cart-item-name')?.textContent || 'Món';
            return { name, unitPrice, qty, subtotal: unitPrice * qty };
        }

        function updateItemDisplay(cartId) {
            const d = getItemData(cartId);
            if (!d) return;
            const priceEl = document.querySelector('.item-price[data-cart-id="' + cartId + '"]');
            if (priceEl) priceEl.innerHTML = formatPrice(d.unitPrice) + ' × ' + d.qty + ' = <strong>' + formatPrice(d.subtotal) + '</strong>';
            updateGrandTotal();
        }

        function updateGrandTotal() {
            let total = 0;
            const details = [];
            document.querySelectorAll('.cart-item').forEach(item => {
                const cartId = item.dataset.cartId;
                const d = getItemData(cartId);
                if (d) {
                    total += d.subtotal;
                    details.push({ name: d.name, qty: d.qty, subtotal: d.subtotal, extras: d.extras || [] });
                }
            });
            const totalEl = document.getElementById('cartTotal');
            if (totalEl) totalEl.textContent = formatPrice(total);
            const detailsEl = document.getElementById('summaryDetails');
            if (detailsEl) {
                detailsEl.innerHTML = details.map(d => {
                const name = String(d.name).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');
                const extras = (d.extras || []).length ? ' <span class="summary-detail-extras">(' + (d.extras || []).map(e => String(e).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;')).join(', ') + ')</span>' : '';
                return '<div class="summary-detail-item"><span class="summary-detail-name">' + name + ' <span class="summary-detail-qty">× ' + d.qty + '</span>' + extras + '</span><span class="summary-detail-price">' + formatPrice(d.subtotal) + '</span></div>';
            }).join('');
            }
        }

        function formatPrice(n) { return new Intl.NumberFormat('vi-VN').format(Math.round(n)) + ' đ'; }

        function changeQty(cartId, delta) {
            const input = document.querySelector('.item-qty[data-cart-id="' + cartId + '"]');
            if (!input) return;
            let val = Math.max(1, (parseInt(input.value) || 1) + delta);
            input.value = val;
            updateItemDisplay(cartId);
            saveCartItem(cartId);
        }

        function setQty(cartId, value) {
            const val = Math.max(1, parseInt(value) || 1);
            const input = document.querySelector('.item-qty[data-cart-id="' + cartId + '"]');
            if (input) input.value = val;
            updateItemDisplay(cartId);
            saveCartItem(cartId);
        }

        function saveCartItem(cartId) {
            const item = document.querySelector('.cart-item[data-cart-id="' + cartId + '"]');
            if (!item) return;
            const qty = parseInt(item.querySelector('.item-qty[data-cart-id="' + cartId + '"]')?.value) || 1;
            const note = item.querySelector('.note-input[data-cart-id="' + cartId + '"]')?.value || '';
            let sizeId = 1;
            item.querySelectorAll('.cart-size-opt.selected').forEach(o => { if (o.dataset.cartId === cartId) sizeId = o.dataset.sizeId; });
            const sideIds = Array.from(item.querySelectorAll('.cart-side-cb:checked')).filter(cb => cb.dataset.cartId === cartId).map(cb => cb.dataset.sideId);
            const fd = new FormData();
            fd.append('cart_id', cartId);
            fd.append('quantity', qty);
            fd.append('note', note);
            fd.append('size_id', sizeId);
            fd.append('side_dish_ids', sideIds.join(','));
            fetch(SITEURL + 'api/update-cart.php', { method: 'POST', body: fd })
                .then(r => r.json())
                .then(data => { if (data.success) updateItemDisplay(cartId); });
        }

        function updateNote(cartId, note) { saveCartItem(cartId); }

        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.cart-size-opt').forEach(el => {
                el.addEventListener('click', function() {
                    const cartId = this.dataset.cartId;
                    this.closest('.cart-item').querySelectorAll('.cart-size-opt').forEach(o => { o.classList.remove('selected'); });
                    this.classList.add('selected');
                    saveCartItem(cartId);
                    updateItemDisplay(cartId);
                });
            });
            document.querySelectorAll('.cart-side-cb').forEach(el => {
                el.addEventListener('change', function() {
                    const cartId = this.dataset.cartId;
                    saveCartItem(cartId);
                    updateItemDisplay(cartId);
                });
            });
        });

        function removeItem(cartId) {
            Swal.fire({ title: 'Xác nhận xóa', text: 'Bạn có chắc muốn xóa món này?', icon: 'warning', showCancelButton: true, confirmButtonColor: '#ff4757', cancelButtonColor: '#6c757d', confirmButtonText: 'Xóa' })
                .then((r) => {
                    if (r.isConfirmed) {
                        const fd = new FormData();
                        fd.append('cart_id', cartId);
                        fetch(SITEURL + 'api/remove-from-cart.php', { method: 'POST', body: fd })
                            .then(res => res.json())
                            .then(data => { if (data.success) location.reload(); });
                    }
                });
        }
    </script>
</body>
</html>
