<?php
include('../config/constants.php');
include('../partials-front/menu.php');
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Giỏ hàng - WowFood</title>
    <link rel="stylesheet" href="../css/style.css">
    <style>
        .cart-container {
            max-width: 800px;
            margin: 100px auto 50px;
            padding: 20px;
        }
        .cart-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 2px solid #ff6b81;
        }
        .cart-header h1 {
            color: #2f3542;
            margin: 0;
        }
        .cart-badge {
            background: #ff6b81;
            color: white;
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 0.9em;
        }
        .cart-item {
            background: white;
            border-radius: 10px;
            padding: 15px;
            margin-bottom: 15px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            display: flex;
            gap: 15px;
        }
        .cart-item-image {
            width: 100px;
            height: 100px;
            object-fit: cover;
            border-radius: 8px;
        }
        .cart-item-info {
            flex: 1;
        }
        .cart-item-name {
            font-size: 1.1em;
            font-weight: bold;
            color: #2f3542;
            margin-bottom: 5px;
        }
        .cart-item-price {
            color: #ff6b81;
            font-weight: bold;
            margin-bottom: 10px;
        }
        .cart-item-controls {
            display: flex;
            gap: 10px;
            align-items: center;
            margin-bottom: 10px;
        }
        .quantity-btn {
            width: 30px;
            height: 30px;
            border: 1px solid #ddd;
            background: white;
            border-radius: 5px;
            cursor: pointer;
            font-size: 1.2em;
            transition: all 0.3s;
        }
        .quantity-btn:hover {
            background: #f0f0f0;
            border-color: #ff6b81;
        }
        .quantity-input {
            width: 50px;
            text-align: center;
            border: 1px solid #ddd;
            border-radius: 5px;
            padding: 5px;
        }
        .note-input {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 0.9em;
            margin-top: 5px;
        }
        .note-input::placeholder {
            color: #999;
        }
        .size-selection {
            margin: 10px 0;
        }
        .size-selection label {
            font-size: 0.9em;
            color: #666;
            margin-right: 10px;
            font-weight: bold;
        }
        .size-options {
            display: flex;
            gap: 10px;
            margin-top: 5px;
            flex-wrap: wrap;
        }
        .size-option {
            padding: 6px 12px;
            border: 2px solid #ddd;
            border-radius: 5px;
            background: white;
            cursor: pointer;
            font-size: 0.85em;
            transition: all 0.3s;
        }
        .size-option:hover {
            border-color: #ff6b81;
        }
        .size-option.selected {
            background: #ff6b81;
            color: white;
            border-color: #ff6b81;
        }
        .side-dishes {
            margin: 10px 0;
        }
        .side-dishes label {
            font-size: 0.9em;
            color: #666;
            margin-right: 10px;
            font-weight: bold;
        }
        .side-dishes-list {
            margin-top: 5px;
        }
        .side-dish-item {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 8px;
            padding: 5px;
        }
        .side-dish-item input[type="checkbox"] {
            width: 18px;
            height: 18px;
            cursor: pointer;
        }
        .side-dish-item label {
            font-size: 0.85em;
            color: #333;
            font-weight: normal;
            cursor: pointer;
            flex: 1;
        }
        .side-dish-price {
            font-size: 0.85em;
            color: #ff6b81;
            font-weight: bold;
        }
        .remove-btn {
            background: #ff4757;
            color: white;
            border: none;
            padding: 8px 15px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 0.9em;
            transition: all 0.3s;
        }
        .remove-btn:hover {
            background: #ff3838;
        }
        .cart-summary {
            background: white;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            position: sticky;
            bottom: 0;
            margin-top: 20px;
        }
        .summary-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
        }
        .summary-total {
            font-size: 1.3em;
            font-weight: bold;
            color: #ff6b81;
            border-top: 2px solid #eee;
            padding-top: 10px;
        }
        .checkout-btn {
            width: 100%;
            padding: 15px;
            background: #ff6b81;
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 1.1em;
            font-weight: bold;
            cursor: pointer;
            margin-top: 15px;
            transition: all 0.3s;
        }
        .checkout-btn:hover {
            background: #ff4757;
        }
        .checkout-btn:disabled {
            background: #ccc;
            cursor: not-allowed;
        }
        .empty-cart {
            text-align: center;
            padding: 50px 20px;
        }
        .empty-cart-icon {
            font-size: 5em;
            margin-bottom: 20px;
        }
        .empty-cart h2 {
            color: #666;
            margin-bottom: 10px;
        }
        .empty-cart a {
            color: #ff6b81;
            text-decoration: none;
            font-weight: bold;
        }
        @media (max-width: 768px) {
            .cart-container {
                margin: 80px auto 20px;
                padding: 10px;
            }
            .cart-item {
                flex-direction: column;
            }
            .cart-item-image {
                width: 100%;
                height: 200px;
            }
        }
    </style>
</head>
<body>
    <div class="cart-container">
        <div class="cart-header">
            <h1>Giỏ hàng</h1>
            <span class="cart-badge" id="cartCount">3 món</span>
        </div>
        <div id="cartItems">
            <!-- Dữ liệu mẫu để hiển thị UI -->
            <div class="cart-item" data-cart-id="1">
                <img src="<?php echo SITEURL; ?>image/category/Food_Category_88.avif" 
                     alt="Phở Bò" class="cart-item-image">
                <div class="cart-item-info">
                    <div class="cart-item-name">Cơm rang</div>
                    <div class="cart-item-price">50,000 đ</div>
                    
                    <!-- Chọn size -->
                    <div class="size-selection">
                        <label>Kích thước:</label>
                        <div class="size-options">
                            <span class="size-option" data-size="small" onclick="selectSize(this, 1)">Nhỏ (+0đ)</span>
                            <span class="size-option selected" data-size="medium" onclick="selectSize(this, 1)">Vừa (+10,000đ)</span>
                            <span class="size-option" data-size="large" onclick="selectSize(this, 1)">Lớn (+20,000đ)</span>
                        </div>
                    </div>

                    <!-- Món ăn kèm -->
                    <div class="side-dishes">
                        <label>Món ăn kèm:</label>
                        <div class="side-dishes-list">
                            <div class="side-dish-item">
                                <input type="checkbox" id="side1-1" checked>
                                <label for="side1-1">Trứng ốp la</label>
                                <span class="side-dish-price">+15,000đ</span>
                            </div>
                            <div class="side-dish-item">
                                <input type="checkbox" id="side1-2">
                                <label for="side1-2">Nem rán</label>
                                <span class="side-dish-price">+20,000đ</span>
                            </div>
                            <div class="side-dish-item">
                                <input type="checkbox" id="side1-3">
                                <label for="side1-3">Canh chua</label>
                                <span class="side-dish-price">+25,000đ</span>
                            </div>
                        </div>
                    </div>

                    <div class="cart-item-controls">
                        <button class="quantity-btn" onclick="alert('Chức năng đang phát triển')">-</button>
                        <input type="number" class="quantity-input" value="2" min="1" readonly>
                        <button class="quantity-btn" onclick="alert('Chức năng đang phát triển')">+</button>
                        <button class="remove-btn" onclick="alert('Chức năng đang phát triển')">Xóa</button>
                    </div>
                    <input type="text" class="note-input" 
                           placeholder="Ghi chú (VD: ăn cay, không cay, nhiều, ít...)" 
                           value="Không cay">
                </div>
            </div>

            <div class="cart-item" data-cart-id="2">
                <img src="<?php echo SITEURL; ?>image/category/Food_Category_65.jpg" 
                     alt="Bánh Mì" class="cart-item-image">
                <div class="cart-item-info">
                    <div class="cart-item-name">Bánh Mì</div>
                    <div class="cart-item-price">25,000 đ</div>
                    
                    <!-- Chọn size -->
                    <div class="size-selection">
                        <label>Kích thước:</label>
                        <div class="size-options">
                            <span class="size-option selected" data-size="small" onclick="selectSize(this, 2)">Nhỏ (+0đ)</span>
                            <span class="size-option" data-size="medium" onclick="selectSize(this, 2)">Vừa (+5,000đ)</span>
                            <span class="size-option" data-size="large" onclick="selectSize(this, 2)">Lớn (+10,000đ)</span>
                        </div>
                    </div>

                    <!-- Món ăn kèm -->
                    <div class="side-dishes">
                        <label>Món ăn kèm:</label>
                        <div class="side-dishes-list">
                            <div class="side-dish-item">
                                <input type="checkbox" id="side2-1">
                                <label for="side2-1">Pate</label>
                                <span class="side-dish-price">+5,000đ</span>
                            </div>
                            <div class="side-dish-item">
                                <input type="checkbox" id="side2-2" checked>
                                <label for="side2-2">Chả lụa</label>
                                <span class="side-dish-price">+10,000đ</span>
                            </div>
                            <div class="side-dish-item">
                                <input type="checkbox" id="side2-3">
                                <label for="side2-3">Trứng</label>
                                <span class="side-dish-price">+8,000đ</span>
                            </div>
                        </div>
                    </div>

                    <div class="cart-item-controls">
                        <button class="quantity-btn" onclick="alert('Chức năng đang phát triển')">-</button>
                        <input type="number" class="quantity-input" value="1" min="1" readonly>
                        <button class="quantity-btn" onclick="alert('Chức năng đang phát triển')">+</button>
                        <button class="remove-btn" onclick="alert('Chức năng đang phát triển')">Xóa</button>
                    </div>
                    <input type="text" class="note-input" 
                           placeholder="Ghi chú (VD: ăn cay, không cay, nhiều, ít...)" 
                           value="">
                </div>
            </div>

            <div class="cart-item" data-cart-id="3">
                <img src="<?php echo SITEURL; ?>image/category/Food_Category_356.jpg" 
                     alt="Cơm Gà" class="cart-item-image">
                <div class="cart-item-info">
                    <div class="cart-item-name">Gà rán</div>
                    <div class="cart-item-price">45,000 đ</div>
                    
                    <!-- Chọn size -->
                    <div class="size-selection">
                        <label>Kích thước:</label>
                        <div class="size-options">
                            <span class="size-option" data-size="small" onclick="selectSize(this, 3)">Nhỏ (+0đ)</span>
                            <span class="size-option" data-size="medium" onclick="selectSize(this, 3)">Vừa (+15,000đ)</span>
                            <span class="size-option selected" data-size="large" onclick="selectSize(this, 3)">Lớn (+30,000đ)</span>
                        </div>
                    </div>

                    <!-- Món ăn kèm -->
                    <div class="side-dishes">
                        <label>Món ăn kèm:</label>
                        <div class="side-dishes-list">
                            <div class="side-dish-item">
                                <input type="checkbox" id="side3-1" checked>
                                <label for="side3-1">Khoai tây chiên</label>
                                <span class="side-dish-price">+20,000đ</span>
                            </div>
                            <div class="side-dish-item">
                                <input type="checkbox" id="side3-2" checked>
                                <label for="side3-2">Salad</label>
                                <span class="side-dish-price">+15,000đ</span>
                            </div>
                            <div class="side-dish-item">
                                <input type="checkbox" id="side3-3">
                                <label for="side3-3">Nước ngọt</label>
                                <span class="side-dish-price">+10,000đ</span>
                            </div>
                            <div class="side-dish-item">
                                <input type="checkbox" id="side3-4">
                                <label for="side3-4">Sốt chấm</label>
                                <span class="side-dish-price">+5,000đ</span>
                            </div>
                        </div>
                    </div>

                    <div class="cart-item-controls">
                        <button class="quantity-btn" onclick="alert('Chức năng đang phát triển')">-</button>
                        <input type="number" class="quantity-input" value="1" min="1" readonly>
                        <button class="quantity-btn" onclick="alert('Chức năng đang phát triển')">+</button>
                        <button class="remove-btn" onclick="alert('Chức năng đang phát triển')">Xóa</button>
                    </div>
                    <input type="text" class="note-input" 
                           placeholder="Ghi chú (VD: ăn cay, không cay, nhiều, ít...)" 
                           value="Nhiều thịt">
                </div>
            </div>
        </div>
        <div class="cart-summary" id="cartSummary">
            <div class="summary-row">
                <span>Tổng cộng:</span>
                <span id="cartTotal">170,000 đ</span>
            </div>
            <button class="checkout-btn" onclick="alert('Chức năng thanh toán đang phát triển')">Thanh toán</button>
        </div>
    </div>
    <?php include('../partials-front/footer.php'); ?>
    <script>
       
        function selectSize(element, cartId) {
            
            const cartItem = element.closest('.cart-item');
            const sizeOptions = cartItem.querySelectorAll('.size-option');
            sizeOptions.forEach(option => {
                option.classList.remove('selected');
            });
            
            
            element.classList.add('selected');
            
           
            console.log('Đã chọn size:', element.dataset.size, 'cho món', cartId);
        }

       
        function updateTotal() {
           
            console.log('Cập nhật tổng tiền');
        }

        
        document.addEventListener('DOMContentLoaded', function() {
            const checkboxes = document.querySelectorAll('.side-dish-item input[type="checkbox"]');
            checkboxes.forEach(checkbox => {
                checkbox.addEventListener('change', function() {
                    updateTotal();
                });
            });
        });
    </script>
</body>
</html>

