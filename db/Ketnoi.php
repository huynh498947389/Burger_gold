<?php
function Ketnoi($host = 'localhost', $user = 'root', $password = '', $database = 'shopfastfood') {
    // Tạo kết nối
    $conn = new mysqli($host, $user, $password, $database);

    // Kiểm tra kết nối
    if ($conn->connect_error) {
        die("Kết nối thất bại: " . $conn->connect_error);
    }

    // Thiết lập charset (để tránh lỗi ký tự)
    $conn->set_charset("utf8");

    return $conn;
}



/*// Ví dụ sử dụng
$conn = connectDatabase();

// Kiểm tra kết nối thành công
if ($conn) {
    echo "Kết nối thành công!";
    // Đừng quên đóng kết nối sau khi sử dụng
    $conn->close();
}
    */

?>