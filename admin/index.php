<?php require_once __DIR__ . "/../config/constants.php"; ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - WowFood</title>
    <link rel="stylesheet" href="<?php echo SITEURL; ?>css/admin.css">
</head>

<body>
    <div class="menu text-center">
        <div class="wrapper">
            <ul>
                <li><a href="<?php echo SITEURL; ?>admin/index.php">Dashboard</a></li>
                <li><a href="<?php echo SITEURL; ?>">Trang chủ</a></li>
            </ul>
        </div>
    </div>


    <!-- main content section starts-->
    <div class="main-content">
        <div class="wrapper">
            <h1>Bảng điều khiển</h1>

            <div class="col-4 text-center">

                <?php 
        
        $sql = "SELECT * FROM tbl_category";

        $res = mysqli_query($conn,$sql);

        $count = mysqli_num_rows($res);
            ?>

                <h1><?php echo $count;?></h1>
                <br />
                Danh mục
            </div>

            <div class="col-4 text-center">

                <?php
          $sql2 = "SELECT * FROM tbl_food";

          $res2 = mysqli_query($conn,$sql2);

          $count2 = mysqli_num_rows($res2);
          ?>
                <h1><?php echo $count2 ;?></h1>
                <br />
                Món ăn
            </div>

            <div class="col-4 text-center">
                <?php 
        
        $sql3 = "SELECT * FROM tbl_order";

        $res3 = mysqli_query($conn,$sql3);

        $count3 = mysqli_num_rows($res3);
            ?>
                <h1><?php echo $count3 ;?></h1>
                <br />
                Tổng đơn hàng
            </div>

            <div class="col-4 text-center">
                <?php
                    $sql4 = "SELECT SUM(total) AS total FROM tbl_order WHERE status='Delivered'";

                    $res4 = mysqli_query($conn,$sql4);
            
                    $row4 = mysqli_fetch_assoc($res4);

                    $total_revenue = $row4['total'];
                     
                     ?>
                <h1><?php echo $total_revenue ;?></h1>
                <br />
                Doanh thu
            </div>
            <div class="clearfix"></div>




        </div>
    </div>
    <!-- main content section ends-->

    <div class="footer">
        <div class="wrapper">
            <p class="text-center">© <?php echo date('Y'); ?> WowFood Admin</p>
        </div>
    </div>
</body>

</html>