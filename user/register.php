<?php 
include('../config/constants.php');
// Xử lý đăng ký trước khi output HTML
if(isset($_POST['submit'])){
    $full_name = mysqli_real_escape_string($conn, $_POST['full_name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $phone = isset($_POST['phone']) ? mysqli_real_escape_string($conn, $_POST['phone']) : '';
    $address = isset($_POST['address']) ? mysqli_real_escape_string($conn, $_POST['address']) : '';
    $verification_type = isset($_POST['verification_type']) ? $_POST['verification_type'] : 'email';
    // Validate password match
    if($password !== $confirm_password){
        $_SESSION['register'] = "Passwords do not match!";
        header('location:'.SITEURL.'user/register.php');
        exit();
    }
    
    // Validate password length
    if(strlen($password) < 6){
        $_SESSION['register'] = "Password must be at least 6 characters!";
        header('location:'.SITEURL.'user/register.php');
        exit();
    }

    $email_domain = substr(strrchr($email, "@"), 1);
    if(strtolower($email_domain) !== 'gmail.com'){
        $_SESSION['register'] = "Chỉ chấp nhận đăng ký bằng Gmail!";
        header('location:'.SITEURL.'user/register.php');
        exit();
    }
    // Check if email already exists
    $check_sql = "SELECT * FROM tbl_user WHERE email=?";
    $stmt = mysqli_prepare($conn, $check_sql);
    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    
    if(mysqli_num_rows($result) > 0){
        mysqli_stmt_close($stmt);
        $_SESSION['register'] = "Email already exists!";
        header('location:'.SITEURL.'user/register.php');
        exit();
    }
    mysqli_stmt_close($stmt);
    
    $_SESSION['pending_registration'] = [
        'full_name' => $full_name,
        'email' => $email,
        'password' => $password, // Lưu password gốc để hash sau khi xác minh
        'phone' => $phone,
        'address' => $address,
        'verification_type' => 'email' // Chỉ dùng email
    ];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Register - Food Order System</title>
    <link rel="stylesheet" href="../css/style.css">
    <style>
        .register-container {
            max-width: 500px;
            margin: 50px auto;
            padding: 30px;
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        .register-container h1 {
            text-align: center;
            color: #2f3542;
            margin-bottom: 30px;
        }
        .register-form input[type="text"],
        .register-form input[type="password"],
        .register-form input[type="email"],
        .register-form input[type="tel"],
        .register-form textarea {
            width: 100%;
            padding: 12px;
            margin-bottom: 15px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 1rem;
            box-sizing: border-box;
            font-family: Arial, Helvetica, sans-serif;
        }
        .register-form textarea {
            resize: vertical;
            min-height: 80px;
        }
        .register-form input[type="submit"] {
            width: 100%;
            padding: 12px;
            background-color: #ff6b81;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 1rem;
            cursor: pointer;
        }
        .register-form input[type="submit"]:hover {
            background-color: #ff4757;
        }
        .login-link {
            text-align: center;
            margin-top: 20px;
        }
        .login-link a {
            color: #ff6b81;
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
    
    <div class="register-container">
        <h1>Đăng ký</h1>
        
        <form action="" method="POST" class="register-form">
            <input type="text" name="full_name" placeholder="Họ tên" required>
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Mật khẩu" required minlength="6">
            <input type="password" name="confirm_password" placeholder="Xác nhận mật khẩu" required>
            <input type="tel" name="phone" placeholder="Số điện thoại (Tùy chọn)">
            <textarea name="address" placeholder="Địa chỉ (Tùy chọn)"></textarea>
            <div style="margin-bottom: 15px; padding: 10px; background-color: #e3f2fd; border-radius: 5px; font-size: 0.9em; color: #1976d2;">
                <strong>📧 Lưu ý:</strong> Mã xác minh sẽ được gửi đến email Gmail của bạn.
            </div>
            <input type="submit" name="submit" value="Đăng ký" class="btn-primary">
        </form>
        
        <div class="login-link">
            <p>Đã có tài khoản? <a href="<?php echo SITEURL; ?>user/login.php">Đăng nhập tại đây</a></p>
        </div>
    </div>
    
    <?php include('../partials-front/footer.php'); ?>

</body>
</html>
