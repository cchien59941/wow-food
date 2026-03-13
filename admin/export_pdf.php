<?php
include('../config/constants.php');
require('../src/dompdf/vendor/autoload.php');
use Dompdf\Dompdf;
$dompdf = new Dompdf();
$html = '
<meta charset="UTF-8">
<style>
    body{font-family: DejaVu Sans;}
    table{border-collapse:collapse;}
    tr th {text-align: center;}
    tr td {text-align: center;}
</style>
    <h2 style="text-align: center;">Đơn hàng đã bán</h2>
<table border="1" width="100%" cellpadding="5">
    <tr>
        <th>ID</th>
        <th>Món ăn</th>
        <th>Giá</th>
        <th>Số lượng</th>
        <th>Tổng tiền</th>
    </tr>';
$counter = 1;
$sql = "SELECT * FROM tbl_order";
$res = mysqli_query($conn,$sql);
while($row = mysqli_fetch_assoc($res))
{
    $html .= "<tr>
    <td>".$counter."</td>
    <td>".$row['food']."</td>
    <td>".$row['price']."</td>
    <td>".$row['qty']."</td>
    <td>".$row['total']."</td>
    </tr>";
    $counter++;
}
$tongtien = "SELECT SUM(total) as tong FROM tbl_order";
$res_tong = mysqli_query($conn, $tongtien);
$row_tong = mysqli_fetch_assoc($res_tong);

$html .= "<tr>
        <td colspan='4' align='center' ><b>Tổng doanh thu</b></td>
        <td><b>".$row_tong['tong']."</b></td>
    </tr>
</table>";
$dompdf->loadHtml($html);
$dompdf->setPaper('A4','portrait');
$dompdf->render();
$dompdf->stream("donhang.pdf",["Attachment"=>0]);
exit();
?>