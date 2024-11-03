<?php
session_start();
$conn = new mysqli('localhost', 'root', '', 'shopfastfood');

if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

// Lấy mã khách hàng từ session
$maKhachHang = $_SESSION['user_id'] ?? 0;

$query = "SELECT gh.MaGioHang, sp.TenSanPham, sp.Gia, sp.HinhAnh, gh.SoLuong 
          FROM giohang gh 
          JOIN sanpham sp ON gh.MaSanPham = sp.MaSanPham 
          WHERE gh.MaKhachHang = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param('i', $maKhachHang);
$stmt->execute();
$result = $stmt->get_result();

$cartItems = [];
while ($row = $result->fetch_assoc()) {
    $cartItems[] = $row;
}

echo json_encode($cartItems);
$conn->close();
?>


