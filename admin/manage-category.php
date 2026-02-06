<?php
include('../config/constants.php');
require_once('partials/login-check.php');
require_once('partials/menu.php');
?>

<div class="main-content">
    <div class="wrapper">
        <h1 style="margin-bottom: 10px;">Quản lý danh mục</h1>
        <p style="color:#747d8c;margin-bottom:25px;">Quản lý các danh mục món ăn trong hệ thống.</p>

        <a href="" style="display:inline-block;margin-bottom:20px;padding:8px 16px;border-radius:999px;
                  background:#1e90ff;color:white;font-size:13px;font-weight:500;text-decoration:none;">
            + Thêm danh mục
        </a>

        <div style="background:#ffffff;border-radius:12px;padding:18px 20px;
                    box-shadow:0 4px 14px rgba(0,0,0,0.06);
                    border:1px solid #ecf0f1;overflow-x:auto;">

            <table style="width:100%;border-collapse:separate;border-spacing:0;font-size:14px;">
                <thead>
                    <tr style="background:#f8f9fb;">
                        <th style="padding:12px 10px;border-bottom:1px solid #e0e6ed;">STT</th>
                        <th style="padding:12px 10px;border-bottom:1px solid #e0e6ed;text-align:left;">Tên danh mục</th>
                        <th style="padding:12px 10px;border-bottom:1px solid #e0e6ed;">Hình ảnh</th>
                        <th style="padding:12px 10px;border-bottom:1px solid #e0e6ed;">Nổi bật</th>
                        <th style="padding:12px 10px;border-bottom:1px solid #e0e6ed;">Hoạt động</th>
                        <th style="padding:12px 10px;border-bottom:1px solid #e0e6ed;text-align:center;">Thao tác</th>
                    </tr>
                </thead>

                <tbody>
                    <tr style="border-bottom:1px solid #f0f2f5;">
                        <td style="padding:10px 8px;">1</td>
                        <td style="padding:10px 8px;font-weight:600;">Bánh mì</td>
                        <td style="padding:10px 8px;">
                            <img src="../image/category/Food_Category_65.jpg" width="90" style="border-radius:8px;">
                        </td>
                        <td style="padding:10px 8px;">
                            <span style="color:green;font-weight:600;">Yes</span>
                        </td>
                        <td style="padding:10px 8px;">
                            <span style="color:green;font-weight:600;">Yes</span>
                        </td>
                        <td style="padding:10px 8px;text-align:center;">
                            <a href="" style="display:inline-block;padding:6px 12px;border-radius:999px;
                                      background:#ecf0f1;color:#2c3e50;
                                      font-size:12px;text-decoration:none;margin-right:4px;">
                                Cập nhật
                            </a>
                            <a href="" style="display:inline-block;padding:6px 12px;border-radius:999px;
                                      background:#ff6b81;color:white;
                                      font-size:12px;text-decoration:none;">
                                Xóa
                            </a>
                        </td>
                    </tr>

                    <tr style="border-bottom:1px solid #f0f2f5;">
                        <td style="padding:10px 8px;">2</td>
                        <td style="padding:10px 8px;font-weight:600;">Xúc Xích</td>
                        <td style="padding:10px 8px;">
                            <img src="../image/category/Food_Category_296.jpg" width="90" style="border-radius:8px;">
                        </td>
                        <td style="padding:10px 8px;">
                            <span style="color:#57606f;">No</span>
                        </td>
                        <td style="padding:10px 8px;">
                            <span style="color:green;font-weight:600;">Yes</span>
                        </td>
                        <td style="padding:10px 8px;text-align:center;">
                            <a href=""
                                style="display:inline-block;padding:6px 12px;border-radius:999px;
                                      background:#ecf0f1;color:#2c3e50;font-size:12px;text-decoration:none;margin-right:4px;">
                                Cập nhật
                            </a>
                            <a href="" style="display:inline-block;padding:6px 12px;border-radius:999px;
                                      background:#ff6b81;color:white;font-size:12px;text-decoration:none;">
                                Xóa
                            </a>
                        </td>
                    </tr>

                    <tr>
                        <td style="padding:10px 8px;">3</td>
                        <td style="padding:10px 8px;font-weight:600;">Cơm rang</td>
                        <td style="padding:10px 8px;">
                            <img src="../image/category/Food_Category_157.avif" width="90" style="border-radius:8px;">
                        </td>
                        <td style="padding:10px 8px;">
                            <span style="color:green;font-weight:600;">Yes</span>
                        </td>
                        <td style="padding:10px 8px;">
                            <span style="color:red;font-weight:600;">No</span>
                        </td>
                        <td style="padding:10px 8px;text-align:center;">
                            <a href=""
                                style="display:inline-block;padding:6px 12px;border-radius:999px;
                                      background:#ecf0f1;color:#2c3e50;font-size:12px;text-decoration:none;margin-right:4px;">
                                Cập nhật
                            </a>
                            <a href="" style="display:inline-block;padding:6px 12px;border-radius:999px;
                                      background:#ff6b81;color:white;font-size:12px;text-decoration:none;">
                                Xóa
                            </a>
                        </td>
                    </tr>

                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include('partials/footer.php'); ?>