<?php
session_start();  // Bắt đầu session
require_once 'db/Ketnoi.php';
$conn = Ketnoi();  // Kết nối cơ sở dữ liệu
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $sodienthoai = trim($_POST['tendangnhap']);
    $password = trim($_POST['password']);

    // Kiểm tra xem các trường có được nhập không
    if (empty($sodienthoai) || empty($password)) {
        header("Location: login.php?error=Vui lòng nhập đầy đủ thông tin");
        exit();
    }

    // Truy vấn cơ sở dữ liệu để kiểm tra thông tin đăng nhập
    $query = "SELECT * FROM khachhang WHERE Tendangnhap = ? AND Matkhau = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ss", $sodienthoai, $password);  // Ràng buộc tham số
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();

        // Lưu thông tin người dùng vào session
        $_SESSION['user_id'] = $user['MaKhachHang'];
        $_SESSION['user_name'] = $user['HoTen'];
        $_SESSION['sdt'] = $user['SoDienThoai'];
        $_SESSION['Email'] = $user['Email'];
        $_SESSION['diachi'] = $user['DiaChi'];




        // Chuyển hướng về trang chủ
        header("Location: index.php");
        exit();
    } else {
        // Sai thông tin đăng nhập
        header("Location: login.php?error=Số điện thoại hoặc mật khẩu không đúng");
        exit();
    }
} else {
    // Nếu không phải phương thức POST, chuyển về trang đăng nhập
    header("Location: login.php");
    exit();
}
?>
