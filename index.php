<?php
session_start();
// Kết nối tới cơ sở dữ liệu
$conn = new mysqli('localhost', 'root', '', 'shopfastfood');
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

// Lấy danh mục từ URL (nếu có)
$danhMuc = isset($_GET['danhmuc']) ? $_GET['danhmuc'] : 'Tất cả';

// Lấy từ khóa tìm kiếm từ URL (nếu có)
$search = isset($_GET['search']) ? $_GET['search'] : '';

// Tạo câu truy vấn khi người dùng chọn danh mục hoặc tìm kiếm
if (!empty($search)) {
    // Nếu có tìm kiếm, kết hợp tìm kiếm với danh mục
    if ($danhMuc === 'Tất cả') {
        $query = "SELECT * FROM sanpham WHERE trangthai = 'Kinh doanh' AND TenSanPham LIKE ?";
    } else {
        $query = "SELECT * FROM sanpham WHERE trangthai = 'Kinh doanh' AND DanhMuc = ? AND TenSanPham LIKE ?";
    }
    $stmt = $conn->prepare($query);
    $searchTerm = '%' . $search . '%'; // Thêm ký tự đại diện % cho LIKE
    if ($danhMuc !== 'Tất cả') {
        $stmt->bind_param('ss', $danhMuc, $searchTerm);
    } else {
        $stmt->bind_param('s', $searchTerm);
    }
} else {
    // Nếu không có tìm kiếm, hiển thị theo danh mục đã chọn
    if ($danhMuc === 'Tất cả') {
        // Hiển thị tất cả sản phẩm theo thứ tự danh mục mong muốn trên trang chủ
        $query = "SELECT * FROM sanpham WHERE trangthai = 'Kinh doanh' 
                  ORDER BY FIELD(DanhMuc, 'Combo Khuyến Mãi', 'Các Loại Burger', 'Món Ăn Kèm', 'Đồ Uống', 'Nước Sốt và Phụ Gia')";
        $stmt = $conn->prepare($query);
    } else {
        // Chỉ hiển thị sản phẩm của danh mục được chọn
        $query = "SELECT * FROM sanpham WHERE trangthai = 'Kinh doanh' AND DanhMuc = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param('s', $danhMuc);
    }
}

// Thực thi câu truy vấn
$stmt->execute();
$result = $stmt->get_result();

// Xử lý yêu cầu AJAX để lấy chi tiết sản phẩm (nếu có)
if (isset($_GET['action']) && $_GET['action'] == 'get_product' && isset($_GET['id'])) {
    $id = $_GET['id'];

    $stmt = $conn->prepare("SELECT * FROM sanpham WHERE MaSanPham = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $product = $result->fetch_assoc();

    if ($product) {
        echo json_encode($product);
    } else {
        echo json_encode(['error' => 'Sản phẩm không tồn tại']);
    }
    exit();
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Burger Gold</title>
    <link rel="stylesheet" href="css/head_footer.css">
    <link rel="stylesheet" href="css/sanpham.css">
    <style>
   
        /* Popup nền */
        /* Popup nền */
            /* Popup nền */
            .popup {
                display: none;
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background-color: rgba(0, 0, 0, 0.6); /* Nền mờ */
                backdrop-filter: blur(4px); /* Hiệu ứng mờ nền */
                z-index: 9999;
                overflow-y: auto; /* Cuộn khi nội dung quá dài */
                padding: 20px;
            }

            /* Nội dung popup */
            .popup-content {
                background-color: #fff;
                margin: 5% auto;
                padding: 5px 20px;
                width: 90%;
                max-width: 600px;
                border-radius: 12px;
                box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
                animation: fadeIn 0.3s ease-in-out;
                position: relative; /* Để định vị chính xác nút đóng */
            }

            /* Tiêu đề của popup */
            .popup-title {
                text-align: center;
                font-size: 24px;
                font-weight: bold;
                margin-bottom: 10px;
                margin-top: 10px;
                color: #d88212;
            }

            /* Hiệu ứng xuất hiện */
            @keyframes fadeIn {
                from {
                    opacity: 0;
                    transform: translateY(-20px);
                }
                to {
                    opacity: 1;
                    transform: translateY(0);
                }
            }

            /* Nút đóng */
            .popup-content .close {
                position: absolute;
                right: 20px;
                top: 20px;
                font-size: 24px;
                cursor: pointer;
                color: #555;
                transition: color 0.2s;
            }

            .popup-content .close:hover {
                color: #000;
            }

            /* Container ảnh sản phẩm */
            .popup-image-container {
                width: 100%;
                display: flex;
                justify-content: center;
                margin-bottom: 20px;
            }

            .product-image {
                width: 300px;
                height: 300px;
                object-fit: cover;
                border-radius: 12px;
                box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            }

            /* Chi tiết sản phẩm */
            .popup-details {
                width: 100%;
                display: flex;
                flex-direction: column;
                gap: 15px;
            }

            /* Dòng chi tiết */
            .detail-row {
                display: flex;
                justify-content: space-between;
                align-items: center;
                border-bottom: 1px solid #f0f0f0;
                padding-bottom: 10px;
            }

            .detail-row label {
                font-weight: bold;
                font-size: 18px;
                color: #333;
                flex: 1;
            }

            .detail-row span,
            .detail-row p {
                flex: 2;
                font-size: 18px;
                color: #555;
                text-align:left;
                margin: 0;
                word-wrap: break-word;
                line-height: 1.6;
            }

            /* Responsive cho màn hình nhỏ */
            @media (max-width: 768px) {
                .popup-content {
                    width: 100%;
                    padding: 20px;
                }

                .detail-row {
                    flex-direction: column;
                    align-items: flex-start;
                }

                .detail-row label {
                    margin-bottom: 5px;
                }

                .detail-row span,
                .detail-row p {
                    text-align: left;
                }
            }
            .search-container {
                text-align: center;
                margin-bottom: 20px;
                margin-top: 30px;
                margin-left: 300px;
            }

            .search-container form {
                display: inline-flex;
            }

            .search-container input[type="text"] {
                padding: 10px;
                width: 300px;
                border: 1px solid #ccc;
                border-radius: 5px 0 0 5px;
                outline: none;
            }

            .search-container button {
                padding: 10px;
                background-color: #ffcc00;
                color: black;
                border: none;
                cursor: pointer;
                border-radius: 0 5px 5px 0;
            }

            .search-container button:hover {
                background-color: #ead78c;
            }
    </style>
</head>
<body>
<?php include 'header.php'; ?>
<div class="search-container">
    <form action="index.php" method="GET">
        <input type="text" name="search" placeholder="Tìm kiếm sản phẩm..." value="<?php echo isset($_GET['search']) ? $_GET['search'] : ''; ?>">
        <button type="submit">Tìm kiếm</button>
    </form>
</div>


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

    <div class="content">
        <?php if ($result->num_rows > 0): ?>
            <?php while ($row = $result->fetch_assoc()): ?>
                <div class="product-box" onclick="showProductDetails(<?php echo $row['MaSanPham']; ?>)">
                    <img src="<?php echo $row['HinhAnh']; ?>" alt="Sản phẩm">
                    <div class="product-info">
                        <h4><?php echo $row['TenSanPham']; ?></h4>
                        <h5>Giá: <?php echo number_format($row['Gia'], 0, ',', '.'); ?> VND</h5>
                        <button class="add-to-cart" 
                                onclick="addToCart(<?php echo $row['MaSanPham']; ?>, event)">
                            Thêm vào giỏ hàng
                        </button>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p>Không có sản phẩm nào.</p>
        <?php endif; ?>
    </div>
</div>

<div id="productPopup" class="popup">
    <div class="popup-content">
        <span class="close" onclick="closePopup()">&times;</span>
        <h2 class="popup-title">Chi tiết sản phẩm</h2>
        <div class="popup-image-container">
            <img id="popupImage" src="" alt="Sản phẩm" class="product-image">
        </div>
        <div class="popup-details">
            <div class="detail-row">
                <label>Tên sản phẩm:</label>
                <span id="popupName"></span>
            </div>
            <div class="detail-row">
                <label>Giá:</label>
                <span id="popupPrice"></span>
            </div>
            <div class="detail-row">
                <label>Mô tả:</label>
                <p id="popupDescription"></p>
            </div>
            <div class="detail-row">
                <label>Danh mục:</label>
                <span id="danhmuc"></span>
            </div>
            
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>

<script>
function addToCart(productId, event) {
    event.stopPropagation();

    const isLoggedIn = <?php echo isset($_SESSION['user_id']) ? 'true' : 'false'; ?>;
    if (!isLoggedIn) {
        alert("Bạn cần đăng nhập để thêm sản phẩm vào giỏ hàng.");
        window.location.href = "login.php";
        return;
    }

    const xhr = new XMLHttpRequest();
    xhr.open("POST", "pages/themgiohang.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhr.onreadystatechange = function () {
        if (xhr.readyState === 4 && xhr.status === 200) {
            const response = JSON.parse(xhr.responseText);
            alert(response.message);
        }
    };
    xhr.send("product_id=" + productId);
}

function showProductDetails(id) {
    fetch(`index.php?action=get_product&id=${id}`)
        .then(response => response.json())
        .then(data => {
            if (data.error) {
                alert(data.error);
                return;
            }

            document.getElementById('popupImage').src = data.HinhAnh;
            document.getElementById('popupName').innerText = data.TenSanPham;
            document.getElementById('popupPrice').innerText = `${new Intl.NumberFormat().format(data.Gia)} VND`;
            document.getElementById('popupDescription').innerText = data.MoTa;
            document.getElementById('danhmuc').innerText = data.DanhMuc;

            document.getElementById('productPopup').style.display = 'block';
        })
        .catch(error => {
            console.error('Lỗi:', error);
            alert('Không thể tải thông tin sản phẩm!');
        });
}

function closePopup() {
    document.getElementById('productPopup').style.display = 'none';
}
</script>

</body>
</html>

<?php
// Đóng kết nối
$conn->close();
?>
