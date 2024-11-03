<?php
session_start(); // Bắt đầu session
session_unset(); // Xóa tất cả dữ liệu session
session_destroy(); // Hủy session hiện tại

// Chuyển hướng về trang đăng nhập
header('Location: login_admin.php');
exit();
?>