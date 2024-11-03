<?php
// Kết nối tới cơ sở dữ liệu
$conn = new mysqli('localhost', 'root', '', 'shopfastfood');
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

// Lấy danh mục từ URL (nếu có)
$danhMuc = isset($_GET['danhmuc']) ? $_GET['danhmuc'] : 'Tất cả';

// Tạo câu truy vấn dựa trên danh mục
if ($danhMuc === 'Tất cả') {
    $query = "SELECT * FROM sanpham";
} else {
    $query = "SELECT * FROM sanpham WHERE DanhMuc = ?";
}

// Chuẩn bị và thực thi câu truy vấn
$stmt = $conn->prepare($query);
if ($danhMuc !== 'Tất cả') {
    $stmt->bind_param('s', $danhMuc);
}
$stmt->execute();
$result = $stmt->get_result();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $message = trim($_POST['message']);

    // Kiểm tra các trường có được nhập đầy đủ không
    if (empty($name) || empty($email) || empty($message)) {
        echo "Vui lòng điền đầy đủ thông tin.";
        exit();
    }

    // Truy vấn SQL để thêm dữ liệu vào bảng
    $query = "INSERT INTO lienhe (TenKhachHang, Email, NoiDung) 
              VALUES (?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("sss", $name, $email, $message);

    if ($stmt->execute()) {
        echo "<script>alert('Liên hệ của bạn đã được gửi thành công!'); window.location.href = 'lienhe.php';</script>";
    } else {
        echo "<script>alert('Có lỗi xảy ra, vui lòng thử lại.');</script>";
    }

    // Đóng kết nối
    $stmt->close();
    $conn->close();
} 
?>
    <section class="contact-section" id="contact">
        <h2>Liên hệ và phản ánh với chúng tôi</h2>
        <p>
        Nếu có bất kì thắc mắc về sản phẩm hoặc chất lượng dịch vụ của cửa hàng, 
        xin vui lòng liên hệ qua email hoặc gọi trực tiếp vào số điện thoại bên dưới. 
        Hoặc quý khách có thể gửi phản ánh trực tiếp qua hộp phản ánh bên dưới!
    </p>
    <div class="contact-info">        
            <p><strong>Số điện thoại:</strong> 0399 914 942</p>
            <p><strong>Email:</strong> Nhom3.TTLTweb@burgergold.com</p>
        </div>
    <h3>Chúc quý khách có trải nghiệm phục vụ hài lòng nhất!</h3>
        
        <div class="contact-form">
            <form action="lienhe.php" method="POST">
                <label for="name">Tên của bạn:</label>
                <input type="text" id="name" name="name" required>

                <label for="email">Email của bạn:</label>
                <input type="email" id="email" name="email" required>

                <label for="message">Tin nhắn:</label>
                <textarea id="message" name="message" rows="5" required></textarea>

                <input type="submit" value="Gửi liên hệ">
            </form>
        </div>
    </section>
   