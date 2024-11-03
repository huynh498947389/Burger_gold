<?php
session_start();
$conn = new mysqli('localhost', 'root', '', 'shopfastfood');
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}
if (!isset($_SESSION['MaNhanVien'])) {
    header('Location:login_admin.php');
    exit(); // Dừng thực thi mã
}
$sql = "SELECT MaLienHe, TenKhachHang, Email, NoiDung, NgayGui FROM lienhe ORDER BY NgayGui DESC";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Phản hồi khách hàng</title>
    <link rel="stylesheet" href="../admin/css/phanhoi.css">
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
         <h1>Danh sách phản hồi từ khách hàng</h1>
    </div>
    
    <div class="container">
        <!-- Bảng danh sách phản hồi -->
        <div class="table-container">
           
            <table>
                <thead>
                    <tr>
                        <th>Tên Khách Hàng</th>
                        <th>Email</th>
                        <th>Nội Dung</th>
                        <th>Ngày Gửi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo "<tr onclick='showDetail(\"{$row['TenKhachHang']}\", \"{$row['Email']}\", \"{$row['NoiDung']}\", \"{$row['NgayGui']}\")'>
                                    <td>{$row['TenKhachHang']}</td>
                                    <td>{$row['Email']}</td>
                                    <td>" . substr($row['NoiDung'], 0, 30) . "...</td>
                                    <td>{$row['NgayGui']}</td>
                                  </tr>";
                        }
                    } else {
                        echo "<tr><td colspan='4'>Không có phản hồi nào</td></tr>";
                    }
                    $conn->close();
                    ?>
                </tbody>
            </table>
        </div>

        <!-- Khu vực hiển thị chi tiết -->
        <div class="detail-container">
            <div class="detail-content">
                <div class="detail-item">
                    <strong>Tên Khách Hàng:</strong>
                    <span id="detail-name">Chưa chọn</span>
                </div>
                <div class="detail-item">
                    <strong>Email:</strong>
                    <span id="detail-email">Chưa chọn</span>
                </div>
                <div class="detail-item">
                    <strong>Nội Dung:</strong>
                    <span id="detail-content">Chưa chọn</span>
                </div>
                <div class="detail-item">
                    <strong>Ngày Gửi:</strong>
                    <span id="detail-date">Chưa chọn</span>
                </div>
            </div>
        </div>
    </div>
    </div>
    <script>
        function showDetail(name, email, content, date) {
            document.getElementById('detail-name').innerText = name;
            document.getElementById('detail-email').innerText = email;
            document.getElementById('detail-content').innerText = content;
            document.getElementById('detail-date').innerText = date;
        }
    </script>
</body>
</html>
