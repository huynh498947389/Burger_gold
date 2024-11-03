<?php
// Kết nối tới cơ sở dữ liệu
$conn = new mysqli('localhost', 'root', '', 'shopfastfood');
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

// Lấy các sản phẩm trong giỏ hàng của người dùng hiện tại
$user_id = $_SESSION['user_id'];
$sql = "SELECT MaSanPham, SoLuong, TrangThai FROM giohang WHERE MaKhachHang = ?";
$stmt = $connection->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

$cart_items = [];
while ($row = $result->fetch_assoc()) {
    $cart_items[] = $row;
}

echo json_encode($cart_items);
?>
