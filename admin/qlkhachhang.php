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

    // Lấy danh sách khách hàng
    $sql = "SELECT * FROM khachhang";
    $result = $conn->query($sql);

    // Xử lý cập nhật thông tin nếu có dữ liệu POST
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['MaKhachHang'])) {
        $maKhachHang = $_POST['MaKhachHang'];
        $hoTen = $_POST['HoTen'];
        $soDienThoai = $_POST['SoDienThoai'];
        $diaChi = $_POST['DiaChi'];
        $email = $_POST['Email'];
        $tenDangNhap = $_POST['Tendangnhap'];
        $matKhau = $_POST['Matkhau'];

        // Cập nhật thông tin khách hàng
        $sqlUpdate = "
            UPDATE khachhang 
            SET HoTen = ?, SoDienThoai = ?, DiaChi = ?, Email = ?, Tendangnhap = ?, Matkhau = ? 
            WHERE MaKhachHang = ?
        ";
        $stmt = $conn->prepare($sqlUpdate);
        $stmt->bind_param('ssssssi', $hoTen, $soDienThoai, $diaChi, $email, $tenDangNhap, $matKhau, $maKhachHang);

        if ($stmt->execute()) {
            echo "<script>alert('Cập nhật thành công!'); window.location.href = 'qlkhachhang.php';</script>";
        } else {
            echo "<script>alert('Cập nhật thất bại.');</script>";
        }
    }
    // Lấy từ khóa tìm kiếm từ GET, nếu có
        $keyword = isset($_GET['keyword']) ? $_GET['keyword'] : '';

        // Tạo câu truy vấn SQL động dựa trên từ khóa tìm kiếm
        $sql = "SELECT * FROM khachhang";
        if (!empty($keyword)) {
            $sql .= " WHERE HoTen LIKE ? OR SoDienThoai LIKE ? OR Email LIKE ?";
        }

        $stmt = $conn->prepare($sql);
        if (!empty($keyword)) {
            $searchTerm = '%' . $keyword . '%';
            $stmt->bind_param('sss', $searchTerm, $searchTerm, $searchTerm);
        }
        $stmt->execute();
        $result = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản Lý Khách Hàng</title>
    <link rel="stylesheet" href="../admin/css/sidebar.css">
    <link rel="stylesheet" href="../admin/css/qlkhachhang.css">
</head>
<body>
<?php include 'sidebar.php';?>
<div class="noidung">
<div class="header">
    <h1>Quản lí khách hàng</h1>
    <form method="GET"class="search-form">
        <input type="text" name="keyword" 
               placeholder="Nhập hoặc để trống">
        <button type="submit">Tìm kiếm</button>
    </form>
</div>

    <table>
        <thead>
            <tr>
                <th>Mã KH</th>
                <th>Họ Tên</th>
                <th>Số ĐT</th>
                <th>Địa Chỉ</th>
                <th>Email</th>
                <th>Tên Đăng Nhập</th>
                <th>Mật Khẩu</th>
                <th>Ngày Tạo</th>
                <th>Chỉnh Sửa</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr data-id="<?php echo $row['MaKhachHang']; ?>">
                    <td><?php echo $row['MaKhachHang']; ?></td>
                    <td><?php echo $row['HoTen']; ?></td>
                    <td><?php echo $row['SoDienThoai']; ?></td>
                    <td><?php echo $row['DiaChi']; ?></td>
                    <td><?php echo $row['Email']; ?></td>
                    <td><?php echo $row['Tendangnhap']; ?></td>
                    <td><?php echo $row['Matkhau']; ?></td>
                    <td><?php echo $row['NgayTao']; ?></td>
                    <td>
                        <button class="edit-btn" onclick="showEditForm(<?php echo $row['MaKhachHang']; ?>)">Sửa</button>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>

    <!-- Modal (Popup) -->
    <div id="editModal" class="modal">
        <div class="modal-content">
            
            <span class="close-btn" onclick="closeModal()">&times;</span>
            <h2 style="color: red;text-align: center; ">Sửa thông tin khách hàng</h2>
            <form method="POST">
                <input type="hidden" name="MaKhachHang" id="MaKhachHang">
                <label for="HoTen">Họ Tên:</label>
                <input type="text" name="HoTen" id="HoTen" required>

                <label for="SoDienThoai">Số Điện Thoại:</label>
                <input type="text" name="SoDienThoai" id="SoDienThoai" required>

                <label for="DiaChi">Địa Chỉ:</label>
                <input type="text" name="DiaChi" id="DiaChi" required>

                <label for="Email">Email:</label>
                <input type="email" name="Email" id="Email" required>

                <label for="Tendangnhap">Tên Đăng Nhập:</label>
                <input type="text" name="Tendangnhap" id="Tendangnhap" required>

                <label for="Matkhau">Mật Khẩu:</label>
                <input type="password" name="Matkhau" id="Matkhau" required>

                <button type="submit">Lưu</button>
            </form>
        </div>
    </div>
</div>

    <script>
        function showEditForm(maKhachHang) {
            const row = document.querySelector(`tr[data-id="${maKhachHang}"]`);
            document.getElementById('MaKhachHang').value = maKhachHang;
            document.getElementById('HoTen').value = row.cells[1].innerText;
            document.getElementById('SoDienThoai').value = row.cells[2].innerText;
            document.getElementById('DiaChi').value = row.cells[3].innerText;
            document.getElementById('Email').value = row.cells[4].innerText;
            document.getElementById('Tendangnhap').value = row.cells[5].innerText;
            document.getElementById('Matkhau').value = row.cells[6].innerText;
            document.getElementById('editModal').style.display = 'block';
        }

        function closeModal() {
            document.getElementById('editModal').style.display = 'none';
        }
    </script>

</body>
</html>

<?php
// Đóng kết nối
$conn->close();
?>
