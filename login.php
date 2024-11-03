<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng nhập - Burger Gold</title>

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap');

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            background-color: #F8F9FA;
            font-family: 'Poppins', sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .login-container {
            background-color: white;
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1);
            max-width: 400px;
            width: 100%;
            text-align: center;
        }

        .logo {
            margin-bottom: 20px;
        }

        .logo img {
            max-width: 120px;
            height: auto;
        }

        h2 {
            margin-bottom: 10px;
            color: #333;
            font-weight: 600;
        }

        .error {
            color: red;
            margin-bottom: 10px;
        }

        label {
            display: block;
            text-align: left;
            margin: 10px 0 5px;
            font-weight: 400;
            color: #555;
        }

        input {
            width: 100%;
            padding: 12px;
            margin-bottom: 15px;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 1em;
        }

        input[type="submit"] {
            background-color: #FFC107;
            color: white;
            border: none;
            cursor: pointer;
            font-size: 1.2em;
            transition: background-color 0.3s;
        }

        input[type="submit"]:hover {
            background-color: #FF6F61;
        }

        /* CSS cho Nút Hủy */
        .cancel-btn {
            background-color: #6c757d; /* Màu xám nhạt */
            color: white;
            border: none;
            cursor: pointer;
            font-size: 1em;
            padding: 10px 15px;
            border-radius: 4px;
            width: 100%; /* Đặt chiều rộng bằng với nút Đăng nhập */
            margin-top: 10px;
            transition: background-color 0.3s ease;
        }

        .cancel-btn:hover {
            background-color: #5a6268; /* Màu xám đậm hơn khi hover */
        }

        .register-link {
            margin-top: 10px;
            font-size: 0.9em;
            color: #555;
        }

        .register-link a {
            color: #4CAF50;
            text-decoration: none;
            font-weight: bold;
        }

        .register-link a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

    <div class="login-container">
        <div class="logo">
            <img src="images/logo.webp" alt="Burger Gold Logo">
        </div>

        <h2>Đăng nhập</h2>

        <?php if (isset($_GET['error'])): ?>
            <div class="error">Sai tài khoản hoặc mật khẩu</div>
        <?php endif; ?>

        <form action="phplogin.php" method="POST">
            <label for="tendangnhap">Tên đăng nhập:</label>
            <input type="text" id="tendangnhap" name="tendangnhap" placeholder="Nhập tên đăng nhập của bạn" required>

            <label for="password">Mật khẩu:</label>
            <input type="password" id="password" name="password" placeholder="Nhập mật khẩu của bạn" required>

            <input type="submit" value="Đăng nhập">
        </form>

        <button class="cancel-btn" onclick="window.location.href='index.php'">Hủy</button>
        <div class="register-link">
            Chưa có tài khoản? <a href="dangky.php">Đăng ký ngay</a>
        </div>
        <div class="register-link">
            Bạn là quản trị viên <a style="color: blue;" href="admin/login_admin.php">Đăng nhập </a>
        </div>
    </div>

</body>
</html>
