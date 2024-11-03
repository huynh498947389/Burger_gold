<?php
session_start();
// Kết nối tới cơ sở dữ liệu
$conn = new mysqli('localhost', 'root', '', 'shopfastfood');
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}
if (!isset($_SESSION['MaNhanVien'])) {
    header('Location:login_admin.php');
    exit(); // Dừng thực thi mã
}

// Lấy từ khóa tìm kiếm từ form (nếu có)
$search = isset($_GET['search']) ? $_GET['search'] : '';
$showAll = isset($_GET['show_all']);  // Kiểm tra xem có nhấn nút "Hiển thị tất cả"

// Truy vấn danh sách đơn hàng
if ($showAll || empty($search)) {
    $sql = "
        SELECT dh.MaDonHang, kh.HoTen, kh.SoDienThoai, kh.DiaChi, dh.NgayDat, dh.TongTien
        FROM donhang dh
        JOIN khachhang kh ON dh.MaKhachHang = kh.MaKhachHang
        ORDER BY dh.NgayDat DESC
    ";
    $stmt = $conn->prepare($sql);
} else {
    $sql = "
        SELECT dh.MaDonHang, kh.HoTen, kh.SoDienThoai, kh.DiaChi, dh.NgayDat, dh.TongTien
        FROM donhang dh
        JOIN khachhang kh ON dh.MaKhachHang = kh.MaKhachHang
        WHERE kh.HoTen LIKE ?
        ORDER BY dh.NgayDat DESC
    ";
    $stmt = $conn->prepare($sql);
    $searchTerm = '%' . $search . '%';
    $stmt->bind_param('s', $searchTerm);
}

$stmt->execute();
$result = $stmt->get_result();

// Lấy chi tiết đơn hàng và thông tin giao hàng (nếu có mã đơn hàng được chọn)
$chiTietDonHang = [];
$orderInfo = null;

if (isset($_GET['MaDonHang'])) {
    $maDonHang = $_GET['MaDonHang'];

    // Truy vấn thông tin giao hàng và địa chỉ khách hàng
    $sqlOrder = "
        SELECT dh.thongtingiaohang, kh.DiaChi
        FROM donhang dh
        JOIN khachhang kh ON dh.MaKhachHang = kh.MaKhachHang
        WHERE dh.MaDonHang = ?
    ";
    $stmt = $conn->prepare($sqlOrder);
    $stmt->bind_param('i', $maDonHang);
    $stmt->execute();
    $orderInfo = $stmt->get_result()->fetch_assoc();

    // Truy vấn chi tiết đơn hàng
    $sqlChiTiet = "
        SELECT sp.MaSanPham, sp.TenSanPham, ct.SoLuong, ct.GiaTaiThoiDiemMua
        FROM chitietdonhang ct
        JOIN sanpham sp ON ct.MaSanPham = sp.MaSanPham
        WHERE ct.MaDonHang = ?
    ";
    $stmt = $conn->prepare($sqlChiTiet);
    $stmt->bind_param('i', $maDonHang);
    $stmt->execute();
    $chiTietDonHang = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản Lý Đơn Hàng</title>
    <link rel="stylesheet" href="../admin/css/sidebar.css">
    <link rel="stylesheet" href="../admin/css/qldonhang.css">
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
    
    .header .search-form {
    display: flex;
    align-items: center;
    gap: 10px;
    }
    
    .header input[type="text"] {
    padding: 10px;
    border: none;
    border-radius: 5px;
    width: 250px; /* Tăng chiều rộng lên 400px */
    max-width: 100%; /* Đảm bảo ô không vượt quá kích thước màn hình */
    }
    </style>
</head>
<body>
<?php include 'sidebar.php';?>

    <div class="noidung">
    <div class="header">
    <h1>Quản Lý Đơn Hàng</h1>

    <!-- Form tìm kiếm và hiển thị tất cả -->
    <form method="GET" action="qldonhang.php">
        <input type="text" name="search" placeholder="Tìm kiếm theo tên khách hàng..." value="<?php echo $search; ?>">
        <button type="submit">Tìm kiếm</button>
        <button type="submit" name="show_all" value="1">Hiển thị tất cả</button>
    </form>
    </div>

    <div class="container">
        <!-- Bảng danh sách đơn hàng -->
        <table>
            <thead>
                <tr>
                    <th>Mã Đơn Hàng</th>
                    <th>Họ Tên Khách Hàng</th>
                    <th>Số Điện Thoại</th>
                    <th>Ngày Đặt</th>
                    <th>Tổng Tiền (VND)</th>
                    <th>Chi Tiết</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $row['MaDonHang']; ?></td>
                        <td><?php echo $row['HoTen']; ?></td>
                        <td><?php echo $row['SoDienThoai']; ?></td>
                        <td><?php echo $row['NgayDat']; ?></td>
                        <td><?php echo number_format($row['TongTien'], 2); ?> VND</td>
                        <td>
                            <a href="?MaDonHang=<?php echo $row['MaDonHang']; ?>">Xem Chi Tiết</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>

        <!-- Chi tiết đơn hàng -->
        <div class="details">
            <?php if ($orderInfo && !empty($chiTietDonHang)): ?>
                <h2>Chi Tiết Đơn Hàng #<?php echo $maDonHang; ?></h2>

                <div class="info-box">
                    <p><strong>Thông Tin Giao Hàng:</strong> <?php echo htmlspecialchars($orderInfo['thongtingiaohang']); ?></p>
                </div>

                <table>
                    <thead>
                        <tr>
                            <th>Mã Sản Phẩm</th>
                            <th>Tên Sản Phẩm</th>
                            <th>Số Lượng</th>
                            <th>Giá (VND)</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($chiTietDonHang as $chiTiet): ?>
                            <tr>
                                <td><?php echo $chiTiet['MaSanPham']; ?></td>
                                <td><?php echo $chiTiet['TenSanPham']; ?></td>
                                <td><?php echo $chiTiet['SoLuong']; ?></td>
                                <td><?php echo number_format($chiTiet['GiaTaiThoiDiemMua'], 2); ?> VND</td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <h2>Chọn đơn hàng để xem chi tiết</h2>
            <?php endif; ?>
        </div>
    </div>
    </div>
</body>
</html>

<?php
// Đóng kết nối
$conn->close();
?>
