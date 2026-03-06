<?php include("partials/menu.php"); ?>


<!-- main content section starts-->
<div class="main-content">
    <div class="wrapper">
        <h1>BẢNG ĐIỀU KHIỂN</h1>

        <div class="col-4 text-center">

            <?php

            $sql = "SELECT * FROM tbl_category";

            $res = mysqli_query($conn, $sql);

            $count = mysqli_num_rows($res);
            ?>

            <h1><?php echo $count; ?></h1>
            <br />
            Danh mục
        </div>

        <div class="col-4 text-center">

            <?php
            $sql2 = "SELECT * FROM tbl_food";

            $res2 = mysqli_query($conn, $sql2);

            $count2 = mysqli_num_rows($res2);
            ?>
            <h1><?php echo $count2; ?></h1>
            <br />
            Món ăn
            
        </div>

        <div class="col-4 text-center">
            <?php

            $sql3 = "SELECT * FROM tbl_order";

            $res3 = mysqli_query($conn, $sql3);

            $count3 = mysqli_num_rows($res3);
            ?>
            <h1><?php echo $count3; ?></h1>
            <br />
            Tổng đơn hàng
        </div>

        <div class="col-4 text-center">
            <?php

            $sql4 = "SELECT SUM(total) AS total FROM tbl_order WHERE status='Delivered'";
            $res4 = mysqli_query($conn, $sql4);
            $row4 = mysqli_fetch_assoc($res4);
            $total_revenue = $row4 && $row4['total'] !== null ? (float)$row4['total'] : 0;

            ?>
            <h1><?php echo number_format($total_revenue, 0, ',', '.'); ?> </h1>USD
            <br />
            Doanh thu
        </div>
        <div class="clearfix"></div>    
    </div>
    <div class="grid">
        <div class="card">
            <div class="card-header">
                <div>DOANH THU</div>
                <span>
                    <select id="filter">
                        <option value="week">Tuần này</option>
                        <option value="month">Tháng này</option>
                        <option value="all">Toàn thời gian</option>
                    </select>
                    <button type="button" id="inbc">In báo cáo</button>
                </span>
            </div>
            <div class="chart-wrap"><canvas id="barChart"></canvas></div>
        </div>
        <div class="card">
            <div class="card-header">
                <div>ĐỒ ĂN BÁN CHẠY</div>
            </div>
            <div class="chart-wrap"><canvas id="pieChart"></canvas></div>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const labels = {
        week: ['T2','T3','T4','T5','T6','T7','CN'],
        month: ['Tuần 1','Tuần 2','Tuần 3','Tuần 4'],
        all: ['T1','T2','T3','T4','T5','T6','T7','T8','T9','T10','T11','T12']
    };
    const values = {
        week: [120, 150, 180, 200, 170, 220, 250],
        month: [700, 850, 900, 950],
        all: [500, 600, 700, 800, 900, 1000, 1100, 1200, 1300, 1400, 1500, 1600]
    };
    const barChart = new Chart(document.getElementById('barChart'), {
        type: 'bar',
        data: {
            labels: labels.week,
            datasets: [{ data: values.week, backgroundColor: '#36a2eb' }]
        },
        options: { plugins: { legend: { display: false } }, maintainAspectRatio: false }
    });
    document.getElementById('filter').onchange = function() {
        var key = this.value;
        barChart.data.labels = labels[key] || labels.week;
        barChart.data.datasets[0].data = values[key] || values.week;
        barChart.update();
    };
    new Chart(document.getElementById('pieChart'), {
        type: 'doughnut',
        data: {
            labels: ['Chí Phèo','Harry Potter','Vợ Nhặt','Sherlock','Angels'],
            datasets: [{
                data: [5,4,3,3,2],
                backgroundColor: ['#ff6384','#36a2eb','#ffce56','#4bc0c0','#9966ff'],
                borderWidth: 2
            }]
        },
        options: {
            maintainAspectRatio: false,
            cutout: '60%',
            plugins: { legend: { position: 'bottom' } }
        }
    });
</script>
<?php include("partials/footer.php"); ?> 