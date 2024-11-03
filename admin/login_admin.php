<?php
session_start();

// Kết nối đến cơ sở dữ liệu với MySQLi
$conn = new mysqli('localhost', 'root', '', 'shopfastfood');
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

// Kiểm tra nếu nhân viên đã đăng nhập thì chuyển hướng đến trang dashboard
if (isset($_SESSION['MaNhanVien'])) {
    header('Location: thongke.php');
    exit();
}

// Xử lý khi người dùng nhấn nút đăng nhập
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Kiểm tra và lấy dữ liệu từ form
    $TaiKhoan = isset($_POST['username']) ? $_POST['username'] : '';
    $MatKhau = isset($_POST['password']) ? $_POST['password'] : '';

    if (!empty($TaiKhoan) && !empty($MatKhau)) {
        // Chuẩn bị câu truy vấn
        $stmt = $conn->prepare("SELECT * FROM NhanVien WHERE TaiKhoan = ? AND MatKhau = ?");
        $stmt->bind_param('ss', $TaiKhoan, $MatKhau); // 'ss' cho hai chuỗi
        $stmt->execute();
        $result = $stmt->get_result();
        $nhanVien = $result->fetch_assoc();

        // Kiểm tra nếu tìm thấy nhân viên
        if ($nhanVien) {
            // Lưu thông tin vào session nếu đăng nhập thành công
            $_SESSION['MaNhanVien'] = $nhanVien['MaNhanVien'];
            $_SESSION['HoTen'] = $nhanVien['HoTen'];
            header('Location: thongke.php');
            exit();
        } else {
            echo "<script>alert('Tài khoản hoặc mật khẩu không chính xác.');</script>";
        }
    } else {
        echo "<script>alert('Vui lòng nhập đầy đủ tài khoản và mật khẩu.');</script>";
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: 'Arial', sans-serif;
            background-color: #f5f5f5;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
        }

        .login-container {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 100%;
            height: 100%;
        }

        .login-card {
            background-color: white;
            width: 360px;
            padding: 40px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
            border-radius: 12px;
            text-align: center;
        }

        .logo {
            width: 80px;
            height: 80px;
            margin: 0 auto 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            background-color: #f0f0f0;
        }

        .logo img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        h2 {
            margin-bottom: 20px;
            font-size: 24px;
            color: #333;
        }

        .input-group {
            margin-bottom: 15px;
            text-align: left;
        }

        .input-group input {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 16px;
            outline: none;
            transition: border-color 0.3s;
        }

        .input-group input:focus {
            border-color: #888;
        }

        .login-btn {
            width: 100%;
            padding: 12px;
            border: none;
            border-radius: 8px;
            background-image: linear-gradient(to right, #6a11cb, #2575fc);
            color: white;
            font-size: 18px;
            cursor: pointer;
            transition: background 0.3s;
        }

        .login-btn:hover {
            background-image: linear-gradient(to right, #2575fc, #6a11cb);
        }

        .signup-text {
            margin-top: 20px;
            font-size: 14px;
            color: #777;
        }

        .signup-text a {
            text-decoration: none;
            color: #2575fc;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-card">
            <div class="logo">
                <img src="../images/logo.webp" alt="Logo">
            </div>
            <h2>Login Admin Burger Gold</h2>
            <form method="POST" action="login_admin.php">
                <div class="input-group">
                    <input type="text" name="username" placeholder="Username" required>
                </div>
                <div class="input-group">
                    <input type="password" name="password" placeholder="Password" required>
                </div>
                <button type="submit" class="login-btn">LOGIN</button>
            </form>
            <p class="signup-text">Không có tài khoản ? <a href="../index.php">Trang chủ</a></p>
        </div>
    </div>
</body>
</html>
