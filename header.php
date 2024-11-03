<style>
    /* Modal tổng thể */
.usmodal {
    display: none; /* Ẩn modal mặc định */
    position: absolute;
    z-index: 1000;
    background-color: rgb(0 0 0 / 83%);
    color: #ddd;
    width: 300px;
    padding: 20px;
    border-radius: 12px;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2); /* Đổ bóng nổi */
    right: 10px; /* Dịch modal vào một chút từ lề phải */
    top: 70px; /* Dịch modal xuống dưới để không đè lên header */
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    transition: opacity 0.3s ease, transform 0.3s ease; /* Hiệu ứng xuất hiện mượt */
    opacity: 0;
    transform: translateY(-10px); /* Đặt modal lên trên ban đầu */
}

/* Khi modal hiển thị (thêm class này bằng JS) */
.usmodal.show {
    opacity: 1;
    transform: translateY(0); /* Dịch về đúng vị trí */
}


/* Tiêu đề và các đoạn thông tin */
.usmodal-content h2 {
    margin: 0 0 15px 0;
    font-size: 20px;
    font-weight: bold;
    border-bottom: 1px solid #333;
    padding-bottom: 10px;
}

.usmodal-content p {
    margin: 10px 0;
    font-size: 16px;
}

/* Nút đăng xuất */
#logoutBtn {
    width: 100%;
    background-color:#fc8f00;
    color: white;
    border: none;
    border-radius: 8px;
    padding: 10px;
    font-size: 16px;
    cursor: pointer;
    transition: background-color 0.3s;
    margin-top: 15px;
}

#logoutBtn:hover {
    background-color: #e60000;
}

</style>

<header>
    <div class="logo">
        <img src="images/logo.webp" alt="Logo cửa hàng">
        <h2>Burger Gold</h2>
    </div>
    <nav>
        <a href="index.php">Trang chủ</a>
        <a href="gioithieu.php">Giới thiệu</a>
        <a href="lienhe.php">Liên hệ</a>

        <?php if (isset($_SESSION['user_id'])): ?>
            <!-- Nếu đã đăng nhập, hiển thị nút Giỏ hàng và Thông tin tài khoản -->
            <div class="cart">
            <a href="#" class="giohang">🛒 Giỏ hàng</a>
            </div>
            <div class="user-info">
                <a href="#" class="thongtintaikhoan" id="btnUserInfo"><?php echo $_SESSION['user_name']; ?></a>
            </div>
        <?php else: ?>
            <!-- Nếu chưa đăng nhập, chỉ hiển thị nút Đăng nhập -->
            <a href="login.php" class="login">Đăng nhập</a>
        <?php endif; ?>
    </nav>
</header>
<!-- Popup Modal -->
<div id="userModal" class="usmodal">
    <div class="usmodal-content">
        <h2>Thông tin tài khoản</h2>
        <p><strong>Tên người dùng:</strong> <?php echo $_SESSION['user_name'] ?? 'Không xác định'; ?></p>
        <p><strong>Số điện thoại :</strong> <?php echo $_SESSION['sdt'] ?? 'Không có thông tin'; ?></p>
        <p><strong>Email         :</strong> <?php echo $_SESSION['Email'] ?? 'Không có thông tin'; ?></p>
        <p><strong>Địa chỉ :</strong> <?php echo $_SESSION['diachi'] ?? 'Không có thông tin'; ?></p>
        <!-- Nút Đăng xuất -->
        <button id="logoutBtn">Đăng xuất</button>
    </div>
</div>

<!-- Phần Modal Giỏ Hàng -->
<div id="cartModal" class="modal">
    <div class="modal-content">
        <span id ="close-cart"  class ="close-cart">&times;</span>
        <h2>Giỏ Hàng Của Bạn</h2>
        <div id="cartItems">
            <p>Đang tải giỏ hàng...</p>
        </div>
        <div class="cart-summary" id="cartSummary">Tổng tiền thanh toán : 0 VND</div>
        <div class="shipping-address">
            <label for="addressInput">Địa chỉ + SĐT giao hàng:</label>
            <input type="text" id="addressInput" class="address-input" placeholder="Nhập địa chỉ giao hàng của bạn..." />
        </div>
        <button id="orderBtn" class="order-btn" disabled>Giao hàng</button>
    </div>
</div>


<script>
    // Lấy các phần tử HTML cần thiết
    const btnUserInfo = document.getElementById('btnUserInfo');
        const modal = document.getElementById('userModal');
        const closeBtn = document.getElementsByClassName('close')[0];

        // Khi nhấn vào nút Thông tin tài khoản, hiển thị modal
        if (btnUserInfo) {
            btnUserInfo.onclick = function() {              
                userModal.classList.toggle('show');
                userModal.style.display = userModal.classList.contains('show') ? 'block' : 'none';
            };
        }

        // Khi nhấn vào nút Đăng xuất, hủy session và tải lại trang
    logoutBtn.onclick = function() {
        fetch('logout.php') // Gọi tới logout.php để hủy session
            .then(() => {
                // Sau khi hủy session, tải lại trang để cập nhật giao diện
                window.location.reload();
            })
            .catch(error => console.error('Đăng xuất không thành công:', error));
    }
</script>
<script>
  
     const cartBtn = document.querySelector('.giohang');
    const cartModal = document.getElementById('cartModal');
    const closeCartBtn = document.querySelector('.close-cart');
    const cartItemsContainer = document.getElementById('cartItems');
    const orderBtn = document.getElementById('orderBtn');
    const cartSummary = document.getElementById('cartSummary');

    cartBtn.onclick = function() {
        loadCartItems();
        cartModal.style.display = 'block';
    };

    closeCartBtn.onclick = function() {
        cartModal.style.display = 'none';
    };

    window.onclick = function(event) {
        if (event.target === cartModal) {
            cartModal.style.display = 'none';
        }
    };

    function loadCartItems() {
    fetch('pages/get_cart_items.php')
        .then(response => response.json())
        .then(data => {
            if (data.length > 0) {
                let cartHTML = '';
                let totalPrice = 0;

                data.forEach(item => {
                    cartHTML += `
                        <div class="cart-item" data-id="${item.MaGioHang}">
                            <img src="${item.HinhAnh}" alt="${item.TenSanPham}">
                            <p>${item.TenSanPham} x ${item.SoLuong}</p>
                            <p>${(item.Gia * item.SoLuong).toLocaleString()} VND</p>
                            <button class="remove-btn" onclick="removeCartItem(${item.MaGioHang})">Xóa</button>
                        </div>
                    `;
                    totalPrice += item.Gia * item.SoLuong;
                });

                document.getElementById('cartItems').innerHTML = cartHTML;
                document.getElementById('cartSummary').innerHTML = `Tổng đơn hàng : ${totalPrice.toLocaleString()} VND`;
                document.getElementById('orderBtn').disabled = false;
            } else {
                document.getElementById('cartItems').innerHTML = '<p>Giỏ hàng trống.</p>';
                document.getElementById('cartSummary').innerHTML = 'Tổng đơn hàng : 0 VND';
                document.getElementById('orderBtn').disabled = true;
            }
        })
        .catch(error => {
            console.error('Lỗi khi tải giỏ hàng:', error);
            document.getElementById('cartItems').innerHTML = '<p>Không thể tải giỏ hàng.</p>';
        });
}

function removeCartItem(cartId) {
    console.log(`Xóa sản phẩm với MaGioHang: ${cartId}`); // Debug: Kiểm tra ID được truyền đúng

    fetch(`pages/remove_cart_item.php?cart_id=${cartId}`, {
        method: 'GET',
        headers: {
            'Content-Type': 'application/json',
        }
    })
    .then(response => response.json())
    .then(result => {
        console.log(result); // Debug: Kiểm tra phản hồi từ API
        if (result.success) {
            alert('Đã xóa sản phẩm khỏi giỏ hàng.');
            loadCartItems(); // Tải lại giỏ hàng sau khi xóa
        } else {
            alert(result.message || 'Xóa sản phẩm không thành công.');
        }
    })
    .catch(error => {
        console.error('Lỗi khi xóa sản phẩm:', error);
        alert('Có lỗi xảy ra, vui lòng thử lại.');
    });
}


document.getElementById('orderBtn').addEventListener('click', function () {
    const address = document.getElementById('addressInput').value.trim();

    if (address === '') {
        alert('Vui lòng nhập địa chỉ giao hàng.');
        return;
    }

    const userId = <?php echo json_encode($_SESSION['user_id'] ?? null); ?>;
    if (!userId) {
        alert('Không thể đặt hàng. Vui lòng đăng nhập lại.');
        return;
    }

    // Đóng gói dữ liệu thành JSON
    const orderData = {
        userId: userId,
        address: address
    };

    // Gửi yêu cầu POST đến submit_order.php với JSON
    fetch('pages/submit_order.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify(orderData),
    })
    .then(response => response.json())
    .then(data => {
        console.log('Dữ liệu phản hồi:', data);

        if (data.success) {
            alert(data.message);
            document.getElementById('cartModal').style.display = 'none';
            document.getElementById('cartItems').innerHTML = '<p>Giỏ hàng trống.</p>';
            document.getElementById('cartSummary').innerHTML = 'Tổng đơn hàng: 0 VND';
            document.getElementById('orderBtn').disabled = true;
            document.getElementById('addressInput').value = ''; // Sửa: Đảm bảo ô input địa chỉ được làm trống
        } else {
            alert('Lỗi: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Lỗi khi đặt hàng:', error);
        alert('Đã xảy ra lỗi khi đặt hàng. Vui lòng thử lại sau.');
    });
});


</script>
