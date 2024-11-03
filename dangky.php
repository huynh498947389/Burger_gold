<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng ký - Burger Gold</title>

    <style>
            body {
    font-family: Arial, sans-serif;
    background-color: #f4f4f4;
    margin: 0;
    padding: 0;
}

.register-container {
    width: 100%;
    max-width: 400px;
    margin: 50px auto;
    padding: 20px;
    background-color: #fff;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    border-radius: 8px;
}

.logo img {
    display: block;
    margin: 0 auto 20px;
    width: 100px; /* Điều chỉnh kích thước logo */
    height: auto;
}

h2 {
    text-align: center;
    color: #333;
}

form {
    display: flex;
    flex-direction: column;
}

label {
    margin-bottom: 5px;
    color: #555;
}

input[type="text"],
input[type="tel"],
input[type="email"],
input[type="password"] {
    padding: 10px;
    margin-bottom: 15px;
    border: 1px solid #ccc;
    border-radius: 4px;
}

input[type="submit"],
.cancel-btn {
    padding: 10px;
    background-color: #28a745;
    color: #fff;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    font-size: 16px;
    margin-top: 10px;
    width: 100%; /* Đảm bảo nút chiếm toàn bộ chiều rộng */
}

input[type="submit"]:hover {
    background-color: #218838;
}

.cancel-btn {
    background-color: #dc3545;
}

.cancel-btn:hover {
    background-color: #c82333;
}

.login-link {
    text-align: center;
    margin-top: 20px;
}

.login-link a {
    color: #007bff;
    text-decoration: none;
}

.login-link a:hover {
    text-decoration: underline;
}


    </style>
</head>
<body>

    <div class="register-container">
       

        <h2>Tạo Tài Khoản</h2>

        <form action="phpdangky.php" method="POST">
            <label for="fullname">Họ tên:</label>
            <input type="text" id="fullname" name="fullname" placeholder="Nhập họ tên" required>

            <label for="username">Tên đăng nhập:</label>
            <input type="text" id="username" name="username" placeholder="Nhập tên đăng nhập" required>

            <label for="phone">Số điện thoại:</label>
            <input type="tel" id="phone" name="phone" placeholder="Nhập số điện thoại" required>

            <label for="email">Email:</label>
            <input type="email" id="email" name="email" placeholder="Nhập email" required>

            <label for="address">Địa chỉ:</label>
            <input type="text" id="address" name="address" placeholder="Nhập địa chỉ" required>

            <label for="password">Mật khẩu:</label>
            <input type="password" id="password" name="password" placeholder="Tạo mật khẩu" required>

            <label for="confirm_password">Nhập lại mật khẩu:</label>
            <input type="password" id="confirm_password" name="confirm_password" placeholder="Nhập lại mật khẩu" required>

            <input type="submit" value="Đăng ký">
        </form>

        <button class="cancel-btn" onclick="goBack()">Hủy</button>

        <div class="login-link">
            Đã có tài khoản? <a href="login.php">Đăng nhập ngay</a>
        </div>
    </div>

    <script>
        function goBack() {
            window.history.back();
        }

        document.querySelector('form').addEventListener('submit', function (e) {
            const password = document.getElementById('password').value;
            const confirmPassword = document.getElementById('confirm_password').value;

            if (password !== confirmPassword) {
                e.preventDefault();
                alert('Mật khẩu và Nhập lại mật khẩu không khớp!');
            }
        });
    </script>

</body>
</html>
