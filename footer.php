<style>
    /* Tổng thể footer */
footer {
    display: flex;
    justify-content: space-between;
    padding: 20px 40px;
    background-color: #333;
    color: #f1f1f1;
    font-family: 'Arial', sans-serif;
    border-top: 2px solid #555;
}

/* Khối thông tin */
.thongtinch, .thanhvien {
    width: 48%; /* Cân bằng khoảng cách hai khối */
}

/* Tạo đường viền mờ chia khối */
.thongtinch::after {
    content: "";
    display: block;
    width: 1px;
    height: 100%;
    background-color: #555;
    position: absolute;
    right: 0;
    top: 0;
}

/* Căn chỉnh văn bản */
.thongtinch {
    text-align: left;
    position: relative; /* Để đường chia khối hoạt động */
    padding-right: 20px;
}

.thanhvien {
    text-align: left;
    padding-left: 20px;
}

/* Định dạng các đoạn văn */
footer p {
    margin: 8px 0;
    font-size: 16px;
    line-height: 1.5;
}

/* Tiêu đề nổi bật */
footer p:first-child {
    font-weight: bold;
    font-size: 20px;
    color: #ffd700;
}


</style>
<footer>
<div class="thongtinch">
    <p>Burger Gold Company</p>
    <p>SĐT: 0399 914 942</p>
    <p>Email: Nhom3.TTLTweb@burgergold.com</p>  
    <p>Địa chỉ : 5 ttlt_web : Bán đồ ăn nhanh</p>
    </div>
    <div class="thanhvien">
    <p style="text-align: left;">  Thành viên nhóm 5 :</p>
    <p>  Ngô Hữu Huỳnh - 17/11/2003</p>
    <p>  Lê Khánh Hòa  - 30/05/2003</p>
    <p>  Mai Thị Ngọc Ánh - 02/12/2003</p>
    </div>
</footer>