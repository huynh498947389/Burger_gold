<?php
session_start();  // Bắt đầu session
$conn = new mysqli('localhost', 'root', '', 'shopfastfood');

// Kiểm tra kết nối
if ($conn->connect_error) {
    die(json_encode([
        'success' => false,
        'message' => 'Kết nối thất bại: ' . $conn->connect_error
    ]));
}

// Lấy mã giỏ hàng từ yêu cầu GET
$maGioHang = $_GET['cart_id'] ?? 0;

// Kiểm tra nếu `MaGioHang` không hợp lệ
if ($maGioHang == 0) {
    echo json_encode([
        'success' => false,
        'message' => 'Mã giỏ hàng không hợp lệ.'
    ]);
    exit;
}

// Xóa sản phẩm dựa vào `MaGioHang`
$query = "DELETE FROM giohang WHERE MaGioHang = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param('i', $maGioHang);

$response = [];
if ($stmt->execute()) {
    if ($stmt->affected_rows > 0) {
        $response['success'] = true;
        $response['message'] = 'Sản phẩm đã được xóa khỏi giỏ hàng.';
    } else {
        $response['success'] = false;
        $response['message'] = 'Không tìm thấy sản phẩm trong giỏ hàng.';
    }
} else {
    $response['success'] = false;
    $response['message'] = 'Xóa sản phẩm không thành công.';
}

// Trả về phản hồi JSON
echo json_encode($response);
$conn->close();
?>
