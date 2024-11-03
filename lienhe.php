<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Burger Gold</title>
    <link rel="stylesheet" href="css/head_footer.css" >
    <link rel="stylesheet" href="css/lienhe.css" >
    <style>
        .sidebar {
    width: 250px;
    background-color: #fff;
    border-radius: 10px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    padding: 20px;
    height: 55vh;
    position: fixed;
    margin-top: 65px;
    top: 0;
    left: 0;
    overflow-y: auto;
}

.sidebar h3 {
    margin-bottom: 20px;
    font-weight: bold;
    color: #333;
}

.sidebar ul {
    list-style: none;
    padding: 0;
}

.sidebar ul li a {
text-decoration: none; /* Loại bỏ gạch chân */
color: #333;
font-size: 16px;
font-weight: bold;
padding: 5px;
display: block;
margin-bottom: 20px;
transition: background-color 0.3s, color 0.3s;
}

.sidebar ul li a:hover {
background-color: #ffcc00;
color: #fff;
}

    </style>
</head>
<body>
    <?php
        include 'header.php';
    ?>
    <div class="container">
    <div class="sidebar">
        <h3>DANH MỤC</h3>
        <ul>
            <li><a href="index.php?danhmuc=Tất cả">Tất cả</a></li>
            <li><a href="index.php?danhmuc=Combo Khuyến Mãi">Combo Khuyến Mãi!</a></li>
            <li><a href="index.php?danhmuc=Các Loại Burger">Các Loại Burger</a></li>
            <li><a href="index.php?danhmuc=Món Ăn Kèm">Món Ăn Kèm</a></li>
            <li><a href="index.php?danhmuc=Đồ Uống">Đồ Uống</a></li>
            <li><a href="index.php?danhmuc=Nước Sốt và Phụ Gia">Nước Sốt và Phụ Gia</a></li>
        </ul>
    </div>
    <?php
        include 'pages/lienhe.php';
    ?>
    
    <?php
        include 'footer.php';
    ?>