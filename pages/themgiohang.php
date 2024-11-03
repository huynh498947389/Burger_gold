<?php
// Kết nối tới cơ sở dữ liệu
$conn = new mysqli('localhost', 'root', '', 'shopfastfood');
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

// Kiểm tra xem người dùng đã đăng nhập hay chưa
session_start();
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Bạn cần đăng nhập trước.']);
    exit();
}

// Lấy thông tin từ yêu cầu AJAX
$maSanPham = $_POST['product_id'];
$soLuong = 1;  // Mặc định là 1, bạn có thể tùy chỉnh theo ý muốn
$maKhachHang = $_SESSION['user_id'];

// Lấy giá của sản phẩm từ bảng sanpham
$productQuery = "SELECT Gia FROM sanpham WHERE MaSanPham = ?";
$productStmt = $conn->prepare($productQuery);
$productStmt->bind_param('i', $maSanPham);
$productStmt->execute();
$productResult = $productStmt->get_result();

if ($productResult->num_rows == 0) {
    echo json_encode(['success' => false, 'message' => 'Sản phẩm không tồn tại.']);
    exit();
}

// Lấy giá sản phẩm
$product = $productResult->fetch_assoc();
$giaSanPham = $product['Gia'];

// Kiểm tra xem sản phẩm đã có trong giỏ hàng chưa
$query = "SELECT SoLuong, Tonggia FROM giohang WHERE MaSanPham = ? AND MaKhachHang = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param('ii', $maSanPham, $maKhachHang);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    // Nếu sản phẩm đã tồn tại, cập nhật số lượng và tổng giá
    $row = $result->fetch_assoc();
    $soLuongMoi = $row['SoLuong'] + $soLuong;
    $tongGiaMoi = $soLuongMoi * $giaSanPham;

    $updateQuery = "UPDATE giohang SET SoLuong = ?, Tonggia = ? WHERE MaSanPham = ? AND MaKhachHang = ?";
    $updateStmt = $conn->prepare($updateQuery);
    $updateStmt->bind_param('idii', $soLuongMoi, $tongGiaMoi, $maSanPham, $maKhachHang);

    if ($updateStmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Cập nhật giỏ hàng thành công.']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Không thể cập nhật sản phẩm trong giỏ hàng.']);
    }
} else {
    // Nếu sản phẩm chưa có trong giỏ hàng, thêm mới
    $tongGia = $soLuong * $giaSanPham;

    $insertQuery = "INSERT INTO giohang (MaKhachHang, MaSanPham, SoLuong, Tonggia, TrangThai) 
                    VALUES (?, ?, ?, ?, 'Dang dat')";
    $insertStmt = $conn->prepare($insertQuery);
    $insertStmt->bind_param('iiid', $maKhachHang, $maSanPham, $soLuong, $tongGia);

    if ($insertStmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Thêm sản phẩm vào giỏ hàng thành công.']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Không thể thêm sản phẩm vào giỏ hàng.']);
    }
}

// Đóng kết nối
$conn->close();
?>
