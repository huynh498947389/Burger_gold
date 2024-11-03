<?php
require_once 'db/Ketnoi.php';
$conn = Ketnoi(); // Kết nối đến cơ sở dữ liệu

if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

// Lấy dữ liệu từ form
$fullname = $_POST['fullname'];
$username = $_POST['username'];
$phone = $_POST['phone'];
$email = $_POST['email'];
$address = $_POST['address'];
$password = $_POST['password'];


// Kiểm tra xem số điện thoại hoặc email đã tồn tại chưa
$sql_check = "SELECT * FROM KhachHang WHERE SoDienThoai = ? OR Email = ?";
$stmt_check = $conn->prepare($sql_check);
$stmt_check->bind_param("ss", $phone, $email);
$stmt_check->execute();
$result_check = $stmt_check->get_result();

if ($result_check->num_rows > 0) {
    echo "Số điện thoại hoặc email đã được sử dụng. Vui lòng thử lại!";
} else {
    // Thêm khách hàng mới vào cơ sở dữ liệu
    $sql = "INSERT INTO KhachHang (HoTen, SoDienThoai, Matkhau, DiaChi, Email, Tendangnhap, NgayTao) 
            VALUES (?, ?, ?, ?, ?, ?, NOW())";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssss", $fullname, $phone, $password, $address, $email, $username);

    if ($stmt->execute()) {
        echo "Đăng ký thành công! <a href='login.php'>Đăng nhập ngay</a>";
    } else {
        echo "Lỗi: " . $stmt->error;
    }

    $stmt->close();
}

$stmt_check->close();
$conn->close();
?>
