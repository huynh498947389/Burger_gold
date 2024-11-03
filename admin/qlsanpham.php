<?php
        session_start();
            // Kết nối đến cơ sở dữ liệu
            $conn = new mysqli('localhost', 'root', '', 'shopfastfood');
            if ($conn->connect_error) {
                die("Kết nối thất bại: " . $conn->connect_error);
            }
            if (!isset($_SESSION['MaNhanVien'])) {
                header('Location:login_admin.php');
                exit(); // Dừng thực thi mã
            }

            // Kiểm tra xem có từ khóa tìm kiếm không
            $keyword = isset($_GET['keyword']) ? trim($_GET['keyword']) : '';

            // Tạo truy vấn SQL có điều kiện tìm kiếm
            if ($keyword) {
                $sql = "SELECT * FROM sanpham 
                        WHERE TenSanPham LIKE ? 
                        OR DanhMuc LIKE ? 
                        OR MoTa LIKE ?";
                $stmt = $conn->prepare($sql);
                $searchTerm = "%$keyword%";
                $stmt->bind_param("sss", $searchTerm, $searchTerm, $searchTerm);
                $stmt->execute();
                $result = $stmt->get_result();
            } else {
                $sql = "SELECT * FROM sanpham";
                $result = $conn->query($sql);
            }
            if (isset($_POST['submit'])) {
                $TenSanPham = $_POST['TenSanPham'];
                $Gia = $_POST['Gia'];
                $MoTa = $_POST['MoTa'];
                $DanhMuc = $_POST['DanhMuc'];
                $TrangThai = $_POST['TrangThai'];

                // Xử lý upload ảnh
                $HinhAnh = $_FILES['HinhAnh']['name'];
                $targetDir = "Imgsanpham/";  // Thư mục để lưu ảnh
                $targetFile = $targetDir . basename($HinhAnh);
                $imageLink = $targetFile; // Đường dẫn ảnh lưu vào cơ sở dữ liệu

                // Kiểm tra trùng lặp sản phẩm
                $stmt = $conn->prepare("SELECT * FROM sanpham WHERE TenSanPham = ?");
                $stmt->bind_param("s", $TenSanPham);
                $stmt->execute();
                $result = $stmt->get_result();

                if ($result->num_rows > 0) {
                    echo "<script>alert('Sản phẩm đã tồn tại!');</script>";
                } else {
                    // Di chuyển file ảnh vào thư mục uploads/
                    if (move_uploaded_file($_FILES['HinhAnh']['tmp_name'], "../".$targetFile)) {
                        // Thêm sản phẩm mới vào cơ sở dữ liệu
                        $stmt = $conn->prepare("INSERT INTO sanpham (TenSanPham, Gia, MoTa, DanhMuc, TrangThai, HinhAnh) 
                                                VALUES (?, ?, ?, ?, ?, ?)");
                        $stmt->bind_param("sdssss", $TenSanPham, $Gia, $MoTa, $DanhMuc, $TrangThai, $imageLink);

                        if ($stmt->execute()) {
                            echo "<script>alert('Thêm sản phẩm thành công!');</script>";
                            echo "<script>window.location.href = window.location.href;</script>"; // Refresh trang
                        } else {
                            echo "<script>alert('Có lỗi xảy ra khi thêm sản phẩm: " . $stmt->error . "');</script>";
                        }
                    } else {
                        echo "<script>alert('Không thể tải ảnh lên!');</script>";
                    }
                }
            }

            if (isset($_GET['action']) && $_GET['action'] == 'get_product' && isset($_GET['id'])) {
                $id = $_GET['id'];

                // Lấy sản phẩm từ cơ sở dữ liệu
                $stmt = $conn->prepare("SELECT * FROM sanpham WHERE MaSanPham = ?");
                $stmt->bind_param("i", $id);
                $stmt->execute();
                $result = $stmt->get_result();
                $product = $result->fetch_assoc();

                if ($product) {
                    echo json_encode($product); // Trả về dữ liệu sản phẩm dưới dạng JSON
                } else {
                    echo json_encode(['error' => 'Sản phẩm không tồn tại']);
                }
                exit();
            }

            if (isset($_POST['update'])) {
                $id = $_POST['MaSanPham'];
                $TenSanPham = $_POST['TenSanPham'];
                $Gia = $_POST['Gia'];
                $MoTa = $_POST['MoTa'];
                $DanhMuc = $_POST['DanhMuc'];
                $TrangThai = $_POST['TrangThai'];

                // Kiểm tra nếu có ảnh mới được upload
                if ($_FILES['HinhAnh']['name']) {
                    $HinhAnh = $_FILES['HinhAnh']['name'];
                    $targetFile = "Imgsanpham/" . basename($HinhAnh);
                    move_uploaded_file($_FILES['HinhAnh']['tmp_name'], "../".$targetFile);
                } else {
                    // Nếu không có ảnh mới, giữ nguyên ảnh hiện tại
                    $targetFile = $_POST['currentImage'];
                }

                // Cập nhật thông tin sản phẩm trong cơ sở dữ liệu
                $stmt = $conn->prepare("UPDATE sanpham SET TenSanPham=?, Gia=?, MoTa=?, DanhMuc=?, TrangThai=?, HinhAnh=? WHERE MaSanPham=?");
                $stmt->bind_param("sdssssi", $TenSanPham, $Gia, $MoTa, $DanhMuc, $TrangThai, $targetFile, $id);

                if ($stmt->execute()) {
                    echo "<script>alert('Cập nhật sản phẩm thành công!'); window.location.href = 'qlsanpham.php';</script>";
                } else {
                    echo "<script>alert('Có lỗi xảy ra: {$stmt->error}');</script>";
                }
            }



?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản Lý Sản Phẩm</title>
    <link rel="stylesheet" href="../admin/css/qlsanpham.css">
    <link rel="stylesheet" href="../admin/css/sidebar.css">
</head>
<body>
<?php include 'sidebar.php';?>
    <div class="noidung">
    
<div class="header">
        <!-- Tiêu đề bên trái -->
        <h2>Quản Lý Sản Phẩm</h2>

        <!-- Form tìm kiếm ở giữa -->
        <form method="GET" class="search-form">
            <input type="text" name="keyword" placeholder="Tìm kiếm hoặc để trống để hiển thị lại danh sách">
            <button type="submit">Tìm kiếm</button>
        </form>

        <!-- Nút Thêm Sản Phẩm bên phải -->
        <div class="right-buttons">
                 <button type="button" class="add-btn" onclick="openPopup()">Thêm sản phẩm</button>
         </div>
    </div>

    <div class="container">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Mã SP</th>
                    <th>Tên Sản Phẩm</th>
                    <th>Danh Mục</th>
                    <th>Giá</th>
                    <th>Mô Tả</th>
                    <th>Hình ảnh</th>
                    <th>Ngày Tạo</th>
                    <th>Trạng Thái</th>
                    <th>Chỉnh Sửa</th>
                </tr>
            </thead>
            <tbody>
    <?php
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            ?>
            <tr>
                <td><?= $row['MaSanPham'] ?></td>
                <td><?= $row['TenSanPham'] ?></td>
                <td><?= $row['DanhMuc'] ?></td>
                <td><?= $row['Gia'] ?></td>
                <td><?= $row['MoTa'] ?></td>
                <td><img src="../<?= $row['HinhAnh'] ?>" alt="Hình sản phẩm"></td>
                <td><?= $row['NgayTao'] ?></td>
                <td><?= $row['trangthai'] ?></td>
                <td>
                    <button class="btn btn-primary" onclick="openEditPopup(<?= $row['MaSanPham'] ?>)">Sửa</button>
                </td>
            </tr>
            <?php
        }
    } else {
        ?>
        <tr><td colspan="9" class="text-center">Không có sản phẩm nào</td></tr>
        <?php
    }
    ?>
</tbody>


        </table>
    </div>
    <div id="popupForm" class="popup">
    <div class="popup-content">
        <span class="close" onclick="closePopup()">&times;</span>
        <h3>Thêm Sản Phẩm</h3>
        <form id="addProductForm" method="POST" enctype="multipart/form-data">
            <input type="text" name="TenSanPham" placeholder="Tên Sản Phẩm" required>
            <input type="number" name="Gia" placeholder="Giá" min="1" required>
            <textarea name="MoTa" placeholder="Mô tả sản phẩm" rows="4"></textarea>

            <!-- Combobox cho Danh Mục -->
            <select name="DanhMuc" required>
                <option value="Combo Khuyến Mãi">Combo Khuyến Mãi !</option>
                <option value="Các Loại Burger">Các Loại Burger</option>
                <option value="Món Ăn Kèm">Món Ăn Kèm</option>
                <option value="Đồ Uống">Đồ Uống</option>
                <option value="Nước Sốt và Phụ Gia">Nước Sốt và Phụ Gia</option>
            </select>

            <!-- Combobox cho Trạng Thái -->
            <select name="TrangThai" required>
                <option value="Kinh doanh">Kinh doanh</option>
                <option value="Ngừng kinh doanh">Ngừng kinh doanh</option>
            </select>

            <input type="file" name="HinhAnh" required>

            <button type="submit" name="submit">Lưu</button>
        </form>
    </div>
</div>
<!-- Popup Sửa Sản Phẩm -->
<div id="editPopup" class="popup">
    <div class="popup-content">
        <span class="close" onclick="closeEditPopup()">&times;</span>
        <h3>Sửa Thông Tin Sản Phẩm</h3>
        <form id="editProductForm" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="MaSanPham" id="editMaSanPham">
            <input type="text" name="TenSanPham" id="editTenSanPham" required>
            <input type="number" name="Gia" id="editGia" required>
            <textarea name="MoTa" id="editMoTa"></textarea>

            <select name="DanhMuc" id="editDanhMuc" required>
                <option value="Combo Khuyến Mãi">Combo Khuyến Mãi</option>
                <option value="Các Loại Burger">Các Loại Burger</option>
                <option value="Món Ăn Kèm">Món Ăn Kèm</option>
                <option value="Đồ Uống">Đồ Uống</option>
                <option value="Nước Sốt và Phụ Gia">Nước Sốt và Phụ Gia</option>
            </select>

            <select name="TrangThai" id="editTrangThai" required>
                <option value="Kinh doanh">Kinh doanh</option>
                <option value="Ngừng kinh doanh">Ngừng kinh doanh</option>
            </select>

            <label>Ảnh hiện tại:</label><br>
            <img id="currentImagePreview" src="" alt="Ảnh sản phẩm" width="100"><br><br>

            <label>Chọn ảnh mới:</label>
            <input type="file" name="HinhAnh">
            <input type="hidden" name="currentImage" id="currentImage">

            <button type="submit" name="update">Lưu</button>
        </form>
    </div>
    </div>
    </div>
</body>
</html>
<script>
    function openPopup() {
        document.getElementById('popupForm').style.display = 'block';
    }
    function closePopup() {
        document.getElementById('popupForm').style.display = 'none';
    }
</script>
<script>
       function openEditPopup(id) {
    // Hiển thị popup
    document.getElementById('editPopup').style.display = 'block';

    // Gọi AJAX để lấy thông tin sản phẩm
    fetch(`qlsanpham.php?action=get_product&id=${id}`)
        .then(response => {
            if (!response.ok) {
                throw new Error('Lỗi mạng hoặc phản hồi không hợp lệ');
            }
            return response.json();
        })
        .then(data => {
            if (data.error) {
                alert(data.error);
                closeEditPopup();
                return;
            }

            // Điền dữ liệu vào form sửa
            document.getElementById('editMaSanPham').value = data.MaSanPham;
            document.getElementById('editTenSanPham').value = data.TenSanPham;
            document.getElementById('editGia').value = data.Gia;
            document.getElementById('editMoTa').value = data.MoTa;

            // Đặt giá trị cho combobox DanhMuc
            document.getElementById('editDanhMuc').value = data.DanhMuc;

            // Đặt giá trị cho combobox TrangThai
            document.getElementById('editTrangThai').value = data.trangthai;

            // Hiển thị đường dẫn ảnh hiện tại (nếu có)
            if (data.HinhAnh) {
                document.getElementById('currentImagePreview').src = "../" + data.HinhAnh;
            } else {
                document.getElementById('currentImagePreview').src = "";
            }

            // Lưu link ảnh hiện tại vào hidden input
            document.getElementById('currentImage').value = data.HinhAnh;
        })
        .catch(error => {
            console.error('Lỗi:', error);
            alert('Không thể tải thông tin sản phẩm!');
            closeEditPopup();
        });

}
function closeEditPopup() {
        document.getElementById('editPopup').style.display = 'none';
    }

    </script>