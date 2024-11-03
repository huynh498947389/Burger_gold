<?php
// Kết nối đến cơ sở dữ liệu
session_start();
$conn = new mysqli('localhost', 'root', '', 'shopfastfood');
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}
if (!isset($_SESSION['MaNhanVien'])) {
    header('Location:login_admin.php');
    exit(); // Dừng thực thi mã
}

// Lấy ngày và loại thời gian được chọn từ form (mặc định là ngày hiện tại và báo cáo theo ngày)
$ngayDuocChon = isset($_POST['ngay']) ? $_POST['ngay'] : date('Y-m-d');
$loaiBaoCao = isset($_POST['loaiBaoCao']) ? $_POST['loaiBaoCao'] : 'ngay';

// Tạo câu truy vấn SQL theo loại báo cáo
switch ($loaiBaoCao) {
    case 'ngay':
        $sql = "SELECT MaDonHang, TongTien, NgayDat 
                FROM DonHang 
                WHERE DATE(NgayDat) = ? 
                ORDER BY NgayDat ASC";
        break;
    case 'tuan':
        $sql = "SELECT MaDonHang, TongTien, NgayDat 
                FROM DonHang 
                WHERE YEARWEEK(NgayDat, 1) = YEARWEEK(?, 1) 
                ORDER BY NgayDat ASC";
        break;
    case 'thang':
        $sql = "SELECT MaDonHang, TongTien, NgayDat 
                FROM DonHang 
                WHERE YEAR(NgayDat) = YEAR(?) AND MONTH(NgayDat) = MONTH(?) 
                ORDER BY NgayDat ASC";
        break;
    case 'nam':
        $sql = "SELECT MaDonHang, TongTien, NgayDat 
                FROM DonHang 
                WHERE YEAR(NgayDat) = YEAR(?) 
                ORDER BY NgayDat ASC";
        break;
    default:
        $sql = "SELECT MaDonHang, TongTien, NgayDat 
                FROM DonHang 
                WHERE DATE(NgayDat) = ? 
                ORDER BY NgayDat ASC";
        break;
}

// Chuẩn bị và thực thi truy vấn
$stmt = $conn->prepare($sql);
if ($loaiBaoCao == 'thang') {
    $stmt->bind_param('ss', $ngayDuocChon, $ngayDuocChon);
} else {
    $stmt->bind_param('s', $ngayDuocChon);
}
$stmt->execute();
$result = $stmt->get_result();

// Lưu dữ liệu đơn hàng để sử dụng cho biểu đồ
$labels = [];
$data = [];
while ($row = $result->fetch_assoc()) {
    $labels[] = 'Mã ĐH: ' . $row['MaDonHang'];
    $data[] = $row['TongTien'];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Báo cáo Doanh thu</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="../admin/css/thongke.css">
    <link rel="stylesheet" href="../admin/css/sidebar.css">
    <style>
    .header {
    background-color: #4CAF50;
    color: white;
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 10px 20px; /* Thêm padding để tạo khoảng cách */
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    margin-bottom: 20px;
    }
    
    .header h1 {
    margin: 0;
    font-size: 24px;
    font-weight: bold;
    }
    </style>
</head>
<body>
<?php include 'sidebar.php';?>
    <div class="noidung">
    
    <div class="header">
    <h1>Báo cáo Doanh thu</h1>
    </div>
    <!-- Form chọn ngày và loại báo cáo -->
    <form method="POST" action="thongke.php">
        <input type="date" name="ngay" value="<?php echo $ngayDuocChon; ?>">
        <select name="loaiBaoCao">
            <option value="ngay" <?php echo $loaiBaoCao == 'ngay' ? 'selected' : ''; ?>>Theo ngày</option>
            <option value="tuan" <?php echo $loaiBaoCao == 'tuan' ? 'selected' : ''; ?>>Theo tuần</option>
            <option value="thang" <?php echo $loaiBaoCao == 'thang' ? 'selected' : ''; ?>>Theo tháng</option>
            <option value="nam" <?php echo $loaiBaoCao == 'nam' ? 'selected' : ''; ?>>Theo năm</option>
        </select>
        <button type="submit">Xem báo cáo</button>
    </form>

    <div class="content-container">
        <table>
            <thead>
                <tr>
                    <th>Mã Đơn Hàng</th>
                    <th>Ngày Đặt</th>
                    <th>Doanh Thu (VND)</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $tongDoanhThu = 0;
                if (!empty($labels)) {
                    foreach ($labels as $index => $label) {
                        echo "<tr>";
                        echo "<td>" . $label . "</td>";
                        echo "<td>" . $ngayDuocChon . "</td>";
                        echo "<td>" . number_format($data[$index], 2) . " VND</td>";
                        echo "</tr>";
                        $tongDoanhThu += $data[$index];
                    }
                } else {
                    echo "<tr><td colspan='3'>Không có dữ liệu cho thời gian này</td></tr>";
                }
                ?>
            </tbody>
        </table>

        <div class="chart-container">
            <canvas id="revenueChart"></canvas>
        </div>
    </div>

    <div class="total-revenue">
    <h2>
        Tổng doanh thu: <?php echo number_format($tongDoanhThu, 2) . " VND"; ?>
    </h2>
</div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const ctx = document.getElementById('revenueChart').getContext('2d');
        const revenueChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: <?php echo json_encode($labels); ?>,
                datasets: [{
                    label: 'Doanh thu (VND)',
                    data: <?php echo json_encode($data); ?>,
                    backgroundColor: 'rgba(76, 175, 80, 0.5)',
                    borderColor: '#4CAF50',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    </script>
     </div>
</body>
</html>

<?php $conn->close(); ?>