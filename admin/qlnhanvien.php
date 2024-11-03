<?php
// Kết nối tới cơ sở dữ liệu
session_start();
$conn = new mysqli('localhost', 'root', '', 'shopfastfood');
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}
if (!isset($_SESSION['MaNhanVien'])) {
    header('Location:login_admin.php');
    exit(); // Dừng thực thi mã
}
// Lấy danh sách nhân viên
$sql = "SELECT * FROM nhanvien";
$result = $conn->query($sql);



// Xử lý xóa nhân viên
if (isset($_GET['delete'])) {
    $maNhanVien = $_GET['delete'];
    $sqlDelete = "DELETE FROM nhanvien WHERE MaNhanVien = ?";
    $stmt = $conn->prepare($sqlDelete);
    $stmt->bind_param('i', $maNhanVien);

    if ($stmt->execute()) {
        echo "<script>alert('Xóa thành công!'); window.location.href = 'qlnhanvien.php';</script>";
    } else {
        echo "<script>alert('Xóa thất bại.');</script>";
    }
}
// Xử lý thêm nhân viên
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'add') {
    $hoTen = $_POST['HoTen'];
    $soDienThoai = $_POST['SoDienThoai'];
    $taiKhoan = $_POST['TaiKhoan'];
    $matKhau = $_POST['MatKhau'];
    $ngayvaolam = $_POST['ngayvaolam'];

    // Kiểm tra xem số điện thoại hoặc tài khoản đã tồn tại hay chưa
    $sqlCheck = "SELECT * FROM nhanvien WHERE SoDienThoai = ? OR TaiKhoan = ?";
    $stmt = $conn->prepare($sqlCheck);
    $stmt->bind_param('ss', $soDienThoai, $taiKhoan);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        echo "<script>alert('Số điện thoại hoặc tài khoản đã tồn tại!');</script>";
    } else {
        // Nếu không trùng lặp, thực hiện thêm nhân viên
        $sqlInsert = "INSERT INTO nhanvien (HoTen, SoDienThoai, TaiKhoan, MatKhau, NgayVaoLam) 
                      VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sqlInsert);
        $stmt->bind_param('sssss', $hoTen, $soDienThoai, $taiKhoan, $matKhau, $ngayvaolam);

        if ($stmt->execute()) {
            echo "<script>alert('Thêm nhân viên thành công!'); window.location.href = 'qlnhanvien.php';</script>";
        } else {
            echo "<script>alert('Thêm nhân viên thất bại.');</script>";
        }
    }
}
// Xử lý cập nhật thông tin nếu có dữ liệu POST
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['MaNhanVien'])) {
    $maNhanVien = $_POST['MaNhanVien'];
    $hoTen = $_POST['HoTen'];
    $soDienThoai = $_POST['SoDienThoai'];
    $taiKhoan = $_POST['TaiKhoan'];
    $matKhau = $_POST['MatKhau'];

    // Kiểm tra xem số điện thoại hoặc tài khoản đã tồn tại cho nhân viên khác
    $sqlCheck = "SELECT * FROM nhanvien WHERE (SoDienThoai = ? OR TaiKhoan = ?) AND MaNhanVien != ?";
    $stmt = $conn->prepare($sqlCheck);
    $stmt->bind_param('ssi', $soDienThoai, $taiKhoan, $maNhanVien);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        echo "<script>alert('Số điện thoại hoặc tài khoản đã tồn tại cho nhân viên khác!');</script>";
    } else {
        // Nếu không trùng lặp, thực hiện cập nhật thông tin nhân viên
        $sqlUpdate = "
            UPDATE nhanvien 
            SET HoTen = ?, SoDienThoai = ?, TaiKhoan = ?, MatKhau = ?
            WHERE MaNhanVien = ?
        ";
        $stmt = $conn->prepare($sqlUpdate);
        $stmt->bind_param('ssssi', $hoTen, $soDienThoai, $taiKhoan, $matKhau, $maNhanVien);

        if ($stmt->execute()) {
            echo "<script>alert('Cập nhật thành công!'); window.location.href = 'qlnhanvien.php';</script>";
        } else {
            echo "<script>alert('Cập nhật thất bại.');</script>";
        }
    }
}
// Lấy từ khóa tìm kiếm từ GET, nếu có
$keyword = isset($_GET['keyword']) ? $_GET['keyword'] : '';

// Tạo câu truy vấn SQL động dựa trên từ khóa tìm kiếm
$sql = "SELECT * FROM nhanvien";
if (!empty($keyword)) {
    $sql .= " WHERE HoTen LIKE ? OR SoDienThoai LIKE ? OR TaiKhoan LIKE ?";
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
    <title>Quản Lý Nhân Viên</title>
    <link rel="stylesheet" href="../admin/css/sidebar.css">
    <link rel="stylesheet" href="../admin/css/qlnhanvien.css">
</head>
<body>
<?php include 'sidebar.php';?>
<div class="noidung">
<div class="header">
    <h1>Quản Lý Nhân Viên</h1>

    <form method="GET"class="search-form">
        <input type="text" name="keyword" placeholder="Nhập hoặc để trống"> 
        <button type="submit">Tìm kiếm</button>      
    </form>
    <div class="right-buttons">    
        <button type="button" class="add-btn" onclick="showAddForm()">Thêm Nhân Viên Mới</button>
    </div>
</div>
</form>
    <table>
        <thead>
            <tr>
                <th>Mã NV</th>
                <th>Họ Tên</th>
                <th>Số ĐT</th>
                <th>Tài Khoản</th>
                <th>Mật Khẩu</th>
                <th>Ngày Vào Làm</th>
                <th>Chỉnh Sửa</th>
                <th>Xóa</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr data-id="<?php echo $row['MaNhanVien']; ?>">
                    <td><?php echo $row['MaNhanVien']; ?></td>
                    <td><?php echo $row['HoTen']; ?></td>
                    <td><?php echo $row['SoDienThoai']; ?></td>
                    <td><?php echo $row['TaiKhoan']; ?></td>
                    <td><?php echo $row['MatKhau']; ?></td>
                    <td><?php echo $row['NgayVaoLam']; ?></td>
                    <td>
                        <button class="edit-btn" onclick="showEditForm(<?php echo $row['MaNhanVien']; ?>)">Sửa</button>
                    </td>
                    <td>
                        <a href="?delete=<?php echo $row['MaNhanVien']; ?>" class="delete-btn" onclick="return confirm('Bạn có chắc chắn muốn xóa?')">Xóa</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>


<!-- Modal Thêm Nhân Viên -->
<div id="addModal" class="modal">
    <div class="modal-content">
        <span class="close-btn" onclick="closeAddModal()">&times;</span>
        <form method="POST">
            <input type="hidden" name="action" value="add">
            
            <label for="HoTen">Họ Tên:</label>
            <input type="text" name="HoTen" id="AddHoTen" required>

            <label for="SoDienThoai">Số Điện Thoại:</label>
            <input type="text" name="SoDienThoai" id="AddSoDienThoai" required>

            <label for="TaiKhoan">Tài Khoản:</label>
            <input type="text" name="TaiKhoan" id="AddTaiKhoan" required>

            <label for="MatKhau">Mật Khẩu:</label>
            <input type="password" name="MatKhau" id="AddMatKhau" required>

            <label for="Ngayvaolam">Ngày vào làm:</label>
                <input type="date" name="ngayvaolam" id="ngayvaolam" required>

            <button type="submit">Thêm Nhân Viên</button>
        </form>
    </div>
</div>

    <div id="editModal" class="modal">
        <div class="modal-content">
            <span class="close-btn" onclick="closeModal()">&times;</span>
            <form method="POST">
                <input type="hidden" name="MaNhanVien" id="MaNhanVien">
                <label for="HoTen">Họ Tên:</label>
                <input type="text" name="HoTen" id="HoTen" required>

                <label for="SoDienThoai">Số Điện Thoại:</label>
                <input type="text" name="SoDienThoai" id="SoDienThoai" required>

                <label for="TaiKhoan">Tài Khoản:</label>
                <input type="text" name="TaiKhoan" id="TaiKhoan" required>

                <label for="MatKhau">Mật Khẩu:</label>
                <input type="password" name="MatKhau" id="MatKhau" required>


                <button type="submit">Lưu</button>
            </form>
        </div>
    </div>
            

    <script>
        function showEditForm(maNhanVien) {
            const row = document.querySelector(`tr[data-id="${maNhanVien}"]`);
            document.getElementById('MaNhanVien').value = maNhanVien;
            document.getElementById('HoTen').value = row.cells[1].innerText;
            document.getElementById('SoDienThoai').value = row.cells[2].innerText;
            document.getElementById('TaiKhoan').value = row.cells[3].innerText;
            document.getElementById('MatKhau').value = row.cells[4].innerText;
            document.getElementById('editModal').style.display = 'block';
        }

        function closeModal() {
            document.getElementById('editModal').style.display = 'none';
        }
        function showAddForm() {
            document.getElementById('addModal').style.display = 'block';
        }

        function closeAddModal() {
            document.getElementById('addModal').style.display = 'none';
        }
    </script>
</div>
</body>
</html>

<?php $conn->close(); ?>
