<?php
session_start();
header('Content-Type: application/json; charset=utf-8');

// Kết nối cơ sở dữ liệu
$conn = new mysqli('localhost', 'root', '', 'shopfastfood');

// Kiểm tra kết nối
if ($conn->connect_error) {
    echo json_encode(['success' => false, 'message' => 'Kết nối thất bại: ' . $conn->connect_error]);
    exit;
}

// Nhận dữ liệu JSON từ yêu cầu POST
$data = json_decode(file_get_contents('php://input'), true);
$userId = $data['userId'] ?? null;
$address = $data['address'] ?? '';

// Kiểm tra dữ liệu đầu vào
if (!$userId || empty($address)) {
    echo json_encode(['success' => false, 'message' => 'Thiếu thông tin người dùng hoặc địa chỉ.']);
    exit;
}

try {
    $conn->begin_transaction();

    // Truy xuất giỏ hàng từ CSDL theo mã khách hàng
    $stmt = $conn->prepare("SELECT MaSanPham, SoLuong, Tonggia FROM giohang WHERE MaKhachHang = ?");
    $stmt->bind_param('i', $userId);
    $stmt->execute();
    $result = $stmt->get_result();

    $cartItems = [];
    $totalAmount = 0;

    while ($row = $result->fetch_assoc()) {
        $cartItems[] = $row;
        $totalAmount += $row['Tonggia'];
    }

    if (empty($cartItems)) {
        throw new Exception("Giỏ hàng trống.");
    }

    // Thêm đơn hàng vào bảng donhang
    $stmt = $conn->prepare(
        "INSERT INTO donhang (MaKhachHang, NgayDat, TongTien, thongtingiaohang) 
         VALUES (?, NOW(), ?, ?)"
    );
    $stmt->bind_param('ids', $userId, $totalAmount, $address);
    $stmt->execute();
    $orderId = $stmt->insert_id;

    // Thêm sản phẩm vào bảng chitietdonhang
    $stmt = $conn->prepare(
        "INSERT INTO chitietdonhang (MaDonHang, MaSanPham, SoLuong, GiaTaiThoiDiemMua) 
         VALUES (?, ?, ?, ?)"
    );

    foreach ($cartItems as $item) {
        $stmt->bind_param('iiid', $orderId, $item['MaSanPham'], $item['SoLuong'], $item['Tonggia']);
        $stmt->execute();
    }

    // Xóa giỏ hàng của người dùng
    $stmt = $conn->prepare("DELETE FROM giohang WHERE MaKhachHang = ?");
    $stmt->bind_param('i', $userId);
    $stmt->execute();

    $conn->commit();
    echo json_encode(['success' => true, 'message' => 'Đơn hàng đã được đặt thành công!']);
} catch (Exception $e) {
    $conn->rollback();
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
} finally {
    if (isset($stmt)) $stmt->close();
    $conn->close();
}
?>
