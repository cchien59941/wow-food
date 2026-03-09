<?php

require_once('../config/constants.php');

require_once('partials/login-check.php');

if (isset($_POST['submit'])) {
    $errors = [];

    $title = trim($_POST['title'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $price_raw = $_POST['price'] ?? '';
    $category = intval($_POST['category'] ?? 0);


    $featured_raw = $_POST['featured'] ?? '';
    $active_raw = $_POST['active'] ?? '';
    $featured = $featured_raw === 'Yes' ? 'Yes' : ($featured_raw === 'No' ? 'No' : '');
    $active = $active_raw === 'Yes' ? 'Yes' : ($active_raw === 'No' ? 'No' : '');

    if ($title === '' || mb_strlen($title) < 3) {
        $errors[] = "Tên món phải có ít nhất 3 ký tự.";
    }

    if ($description === '' || mb_strlen($description) < 10) {
        $errors[] = "Mô tả phải có ít nhất 10 ký tự.";
    }

    if ($price_raw === '' || !is_numeric($price_raw)) {
        $errors[] = "Vui lòng nhập giá hợp lệ.";
    }

    $price = floatval($price_raw);
    if ($price < 0) {
        $errors[] = "Giá không được nhỏ hơn 0.";
    }

    if ($category <= 0) {
        $errors[] = "Vui lòng chọn danh mục.";
    }

    if ($featured === '') {
        $errors[] = "Vui lòng chọn 'Nổi bật' (Có/Không).";
    }

    if ($active === '') {
        $errors[] = "Vui lòng chọn 'Hoạt động' (Có/Không).";
    }


    $image_name = "";
    if (isset($_FILES['image']['name']) && $_FILES['image']['name'] !== '') {
        $original_name = $_FILES['image']['name'];
        $image_parts = explode('.', $original_name);
        $ext = strtolower(end($image_parts));


        $allowed = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'avif'];
        if (!in_array($ext, $allowed, true)) {
            $errors[] = "Định dạng ảnh không hợp lệ. Chỉ cho phép: " . implode(', ', $allowed) . ".";
        } else {
            $image_name = "Food-name-" . rand(0, 9999) . '.' . $ext;
            $source_path = $_FILES['image']['tmp_name'];
            $destination_path = "../image/food/" . $image_name;
            $upload = move_uploaded_file($source_path, $destination_path);
            if ($upload == false) {
                $errors[] = "Tải hình ảnh thất bại.";
            }
        }
    }

    if (!empty($errors)) {

        $_SESSION['form_errors'] = $errors;

        $_SESSION['form_old'] = [
            'title' => $title,
            'description' => $description,
            'price' => $price_raw,
            'category' => $category,
            'featured' => $featured_raw,
            'active' => $active_raw,
        ];
        header('location:add-food.php');
        exit();
    }


    $sql2 = "INSERT INTO tbl_food (title, `description`, price, image_name, category_id, featured, active)
             VALUES (?, ?, ?, ?, ?, ?, ?)";

    $stmt = mysqli_prepare($conn, $sql2);
    if ($stmt) {
        mysqli_stmt_bind_param(
            $stmt,
            "ssdssss",
            $title,
            $description,
            $price,
            $image_name,
            $category,
            $featured,
            $active
        );
        $res2 = mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
    } else {
        $res2 = false;
    }

    if ($res2 == TRUE) {
        $_SESSION['add'] = "<div class='success'>Thêm món ăn thành công!</div>";
        header('location:manage-food.php');
        exit();
    } else {
        $_SESSION['add'] = "<div class='error'>Thêm món ăn thất bại!</div>";
        header('location:add-food.php');
        exit();
    }
}


include('partials/menu.php');
?>
<div class="main-content">
    <div class="wrapper">
        <h1 style="text-align:center;">Thêm món ăn</h1>

        <br><br>
        <form action="" method="post" enctype="multipart/form-data"
            style="background:white;padding:30px;border-radius:8px;box-shadow:0 3px 10px rgba(0,0,0,0.1);width:700px;margin:auto;">

            <table class="tbl-30" style="width:100%;border-collapse:separate;border-spacing:0 12px;">

                <tr>
                    <td style="width:160px;font-weight:bold;">Tên món:</td>
                    <td>
                        <input type="text" id="check_title" name="title" placeholder="Tên món ăn"
                            value="<?php echo htmlspecialchars($_SESSION['form_old']['title'] ?? ''); ?>"
                            style="width:100%;padding:8px;border:1px solid #ccc;border-radius:5px;">
                    </td>
                </tr>

                <tr>
                    <td></td>
                    <td><span id="warning_title" style="color:red;font-size:13px;"></span></td>
                </tr>

                <tr>
                    <td style="font-weight:bold;">Mô tả:</td>
                    <td>
                        <textarea name="description" id="check_description" rows="5" placeholder="Mô tả món ăn"
                            style="width:100%;padding:8px;border:1px solid #ccc;border-radius:5px;resize:none;"><?php echo htmlspecialchars($_SESSION['form_old']['description'] ?? ''); ?></textarea>
                    </td>
                </tr>

                <tr>
                    <td></td>
                    <td><span id="warning_description" style="color:red;font-size:13px;"></span></td>
                </tr>

                <tr>
                    <td style="font-weight:bold;">Giá:</td>
                    <td>
                        <input type="number" id="check_price" name="price" placeholder="Giá món ăn" min="0"
                            value="<?php echo htmlspecialchars($_SESSION['form_old']['price'] ?? ''); ?>"
                            style="width:100%;padding:8px;border:1px solid #ccc;border-radius:5px;">
                    </td>
                </tr>

                <tr>
                    <td></td>
                    <td><span id="warning_price" style="color:red;font-size:13px;"></span></td>
                </tr>

                <tr>
                    <td style="font-weight:bold;">Chọn hình ảnh:</td>
                    <td>
                        <input type="file" name="image" style="padding:5px;">
                    </td>
                </tr>

                <tr>
                    <td style="font-weight:bold;">Danh mục:</td>
                    <td>
                        <select name="category" style="width:100%;padding:8px;border:1px solid #ccc;border-radius:5px;">
                            <?php
                            $sql = "SELECT * FROM `tbl_category` WHERE active='Yes'";
                            $res = mysqli_query($conn, $sql);
                            $count = mysqli_num_rows($res);
                            $old_category = intval($_SESSION['form_old']['category'] ?? 0);
                            if ($count > 0) {
                                while ($row = mysqli_fetch_assoc($res)) {
                                    $id = $row['id'];
                                    $cat_title = $row['title'];
                                    $selected = ($id == $old_category) ? 'selected' : '';
                                    ?>
                                    <option value="<?php echo $id; ?>" <?php echo $selected; ?>>
                                        <?php echo htmlspecialchars($cat_title); ?>
                                    </option>
                                    <?php
                                }
                            } else {
                                ?>
                                <option value="0">Không tìm thấy danh mục</option>
                                <?php
                            }
                            ?>
                        </select>
                    </td>
                </tr>

                <tr>
                    <td style="font-weight:bold;">Nổi bật:</td>
                    <td>
                        <?php $old_featured = $_SESSION['form_old']['featured'] ?? ''; ?>
                        <label style="margin-right:10px;">
                            <input type="radio" name="featured" value="Yes" <?php echo ($old_featured === 'Yes') ? 'checked' : ''; ?>> Có
                        </label>

                        <label>
                            <input type="radio" name="featured" value="No" <?php echo ($old_featured === 'No') ? 'checked' : ''; ?>> Không
                        </label>
                    </td>
                </tr>

                <tr>
                    <td style="font-weight:bold;">Hoạt động:</td>
                    <td>
                        <?php $old_active = $_SESSION['form_old']['active'] ?? ''; ?>

                        <label style="margin-right:10px;">
                            <input type="radio" name="active" value="Yes" <?php echo ($old_active === 'Yes') ? 'checked' : ''; ?>> Có
                        </label>

                        <label>
                            <input type="radio" name="active" value="No" <?php echo ($old_active === 'No') ? 'checked' : ''; ?>> Không
                        </label>
                    </td>
                </tr>

                <tr>
                    <td colspan="2" style="text-align:center;padding-top:10px;">
                        <input type="submit" name="submit" value="Thêm món ăn"
                            style="background:#2ed573;color:white;padding:10px 20px;border:none;border-radius:5px;font-weight:bold;cursor:pointer;">
                    </td>
                </tr>

            </table>
        </form>

        <?php

        unset($_SESSION['form_old']);


        if (isset($_SESSION['form_errors']) && !empty($_SESSION['form_errors'])) {
            $errors = $_SESSION['form_errors'];
            unset($_SESSION['form_errors']);
            $msg = implode("\\n", $errors);
            echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>";
            echo "<script>
                Swal.fire({
                    icon: 'warning',
                    title: 'Thiếu/không hợp lệ dữ liệu',
                    text: '" . addslashes($msg) . "',
                    confirmButtonText: 'OK'
                });
            </script>";
        }
        ?>
    </div>
</div>

<div class="footer">
    <div class="wrapper">
        <p class="text-center"> 2023 All rights resered, Some restaurant. Developed By - <a href="#">5 anh em </a></p>
    </div>
</div>

</body>



</html>