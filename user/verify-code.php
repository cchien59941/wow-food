<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Xác minh tài khoản - WowFood</title>
    <link rel="stylesheet" href="../css/style.css">
    <style>
        .verify-container {
            max-width: 500px;
            margin: 100px auto;
            padding: 30px;
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        .verify-container h1 {
            text-align: center;
            color: #2f3542;
            margin-bottom: 20px;
        }
        .verify-info {
            text-align: center;
            margin-bottom: 30px;
            padding: 15px;
            background-color: #f1f2f6;
            border-radius: 5px;
        }
        .verify-info strong {
            color: #ff6b81;
        }
        .code-input {
            width: 100%;
            padding: 15px;
            font-size: 24px;
            text-align: center;
            letter-spacing: 10px;
            border: 2px solid #ddd;
            border-radius: 5px;
            margin-bottom: 20px;
            box-sizing: border-box;
        }
        .code-input:focus {
            outline: none;
            border-color: #ff6b81;
        }
        .verify-form input[type="submit"] {
            width: 100%;
            padding: 12px;
            background-color: #ff6b81;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 1rem;
            cursor: pointer;
            margin-bottom: 10px;
        }
        .verify-form input[type="submit"]:hover {
            background-color: #ff4757;
        }
        .resend-link {
            text-align: center;
            margin-top: 20px;
        }
        .resend-link form {
            display: inline;
        }
        .resend-link button {
            background: none;
            border: none;
            color: #ff6b81;
            cursor: pointer;
            text-decoration: underline;
            font-size: 1rem;
        }
        .resend-link button:hover {
            color: #ff4757;
        }
        .error {
            color: red;
            text-align: center;
            margin-bottom: 15px;
            padding: 10px;
            background-color: #ffe6e6;
            border-radius: 5px;
        }
        .success {
            color: green;
            text-align: center;
            margin-bottom: 15px;
            padding: 10px;
            background-color: #e6ffe6;
            border-radius: 5px;
        }
    </style>
</head>
<body>
    <?php include('../partials-front/menu.php'); ?>
    
    <div class="verify-container">
        <h1>🔐 Xác minh tài khoản</h1>
        
        <div class="verify-info">
            <p>📧 Mã xác minh đã được gửi đến email Gmail:</p>
            <p style="font-size: 0.9em; color: #666; margin-top: 10px;">
                Vui lòng kiểm tra hộp thư đến (và cả thư mục Spam) và nhập mã 6 chữ số để hoàn tất đăng ký
            </p>
        </div>

        <form action="" method="POST" class="verify-form">
            <input type="text" 
                   name="code" 
                   class="code-input" 
                   placeholder="000000" 
                   maxlength="6" 
                   pattern="[0-9]{6}" 
                   required 
                   autocomplete="off"
                   autofocus>
            <input type="submit" name="verify_code" value="Xác minh" class="btn-primary">
        </form>
        
        <div class="resend-link">
            <p>Không nhận được mã? 
                <form method="POST" style="display: inline;">
                    <button type="submit" name="resend_code">Gửi lại mã</button>
                </form>
            </p>
        </div>
    </div>
    
    <?php include('../partials-front/footer.php'); ?>
    
    <script>
        document.querySelector('.code-input').addEventListener('input', function(e) {
            this.value = this.value.replace(/[^0-9]/g, '');
        });
    </script>
</body>
</html>