-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Máy chủ: 127.0.0.1
-- Thời gian đã tạo: Th10 03, 2024 lúc 05:31 AM
-- Phiên bản máy phục vụ: 10.4.32-MariaDB
-- Phiên bản PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Cơ sở dữ liệu: `shopfastfood`
--

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `chitietdonhang`
--

CREATE TABLE `chitietdonhang` (
  `MaChiTiet` int(11) NOT NULL,
  `MaDonHang` int(11) NOT NULL,
  `MaSanPham` int(11) NOT NULL,
  `SoLuong` int(11) NOT NULL,
  `GiaTaiThoiDiemMua` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `chitietdonhang`
--

INSERT INTO `chitietdonhang` (`MaChiTiet`, `MaDonHang`, `MaSanPham`, `SoLuong`, `GiaTaiThoiDiemMua`) VALUES
(25, 10, 14, 1, 25000.00),
(26, 10, 15, 1, 27999.00),
(27, 10, 16, 2, 63000.00),
(28, 10, 24, 1, 20000.00),
(29, 11, 30, 1, 60000.00),
(30, 11, 33, 1, 22000.00),
(31, 11, 26, 1, 5000.00),
(32, 12, 50, 1, 99999.00),
(33, 12, 17, 1, 40000.00),
(34, 12, 46, 1, 57000.00),
(35, 12, 35, 1, 10000.00);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `donhang`
--

CREATE TABLE `donhang` (
  `MaDonHang` int(11) NOT NULL,
  `MaKhachHang` int(11) NOT NULL,
  `NgayDat` timestamp NOT NULL DEFAULT current_timestamp(),
  `TongTien` decimal(10,2) NOT NULL,
  `thongtingiaohang` varchar(500) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `donhang`
--

INSERT INTO `donhang` (`MaDonHang`, `MaKhachHang`, `NgayDat`, `TongTien`, `thongtingiaohang`) VALUES
(10, 7, '2024-11-02 18:30:13', 135999.00, '218 Lĩnh Nam ,Hoàng Mai ,Hà Nội + 0399914942'),
(11, 7, '2024-11-02 18:31:14', 87000.00, '89 Minh Khai ,Hai Bà Trưng ,Hà Nội +091114533'),
(12, 8, '2024-11-02 18:33:55', 206999.00, '123 Nguyễn Trãi, Hà Nội + 0901234567');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `giohang`
--

CREATE TABLE `giohang` (
  `MaGioHang` int(11) NOT NULL,
  `MaKhachHang` int(11) NOT NULL,
  `MaSanPham` int(11) NOT NULL,
  `SoLuong` int(11) NOT NULL,
  `Tonggia` int(10) NOT NULL,
  `TrangThai` enum('Dang dat','Da thanh toan','Huy') DEFAULT 'Dang dat'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `giohang`
--

INSERT INTO `giohang` (`MaGioHang`, `MaKhachHang`, `MaSanPham`, `SoLuong`, `Tonggia`, `TrangThai`) VALUES
(58, 7, 30, 1, 60000, 'Dang dat'),
(59, 7, 31, 1, 230000, 'Dang dat'),
(60, 7, 47, 1, 65000, 'Dang dat');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `khachhang`
--

CREATE TABLE `khachhang` (
  `MaKhachHang` int(11) NOT NULL,
  `HoTen` varchar(100) NOT NULL,
  `SoDienThoai` varchar(15) DEFAULT NULL,
  `Matkhau` varchar(255) NOT NULL,
  `DiaChi` varchar(255) DEFAULT NULL,
  `NgayTao` timestamp NOT NULL DEFAULT current_timestamp(),
  `Email` varchar(255) NOT NULL,
  `Tendangnhap` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `khachhang`
--

INSERT INTO `khachhang` (`MaKhachHang`, `HoTen`, `SoDienThoai`, `Matkhau`, `DiaChi`, `NgayTao`, `Email`, `Tendangnhap`) VALUES
(7, 'Ngô Hữu Huỳnh', '0399914942', '12345678', 'Bảo Thắng - Lào Cai', '2024-10-27 13:11:47', 'nhhuynh1711@gmail.com', 'nhhuynh'),
(8, 'Nguyễn Văn An', '0901234567', 'password1', '123 Nguyễn Trãi, Hà Nội', '2024-11-02 18:27:11', 'an.nguyen@gmail.com', 'nguyenvana'),
(9, 'Trần Thị Bình', '0912345678', 'password2', '456 Lê Văn Sỹ, TP.HCM', '2024-11-02 18:27:11', 'binh.tran@gmail.com', 'tranthibinh'),
(10, 'Lê Quang Cường', '0923456789', 'password3', '789 Trần Phú, Đà Nẵng', '2024-11-02 18:27:11', 'cuong.le@gmail.com', 'lequangcuong'),
(11, 'Phạm Thị Dung', '0934567890', 'password4', '101 Nguyễn Văn Cừ, Hải Phòng', '2024-11-02 18:27:11', 'dung.pham@gmail.com', 'phamthidung'),
(12, 'Nguyễn Thị Hương', '0945678901', 'password5', '202 Đinh Tiên Hoàng, Nha Trang', '2024-11-02 18:27:11', 'huong.nguyen@gmail.com', 'nguyenthihuong'),
(13, 'Đỗ Minh Khoa', '0956789012', 'password6', '303 Ngô Gia Tự, Cần Thơ', '2024-11-02 18:27:11', 'khoa.do@gmail.com', 'dominhkhoa'),
(14, 'Vũ Văn Lâm', '0967890123', 'password7', '404 Phan Văn Trị, Bình Dương', '2024-11-02 18:27:11', 'lam.vu@gmail.com', 'vvanlam'),
(15, 'Nguyễn Thế Mạnh', '0978901234', 'password8', '505 Huỳnh Văn Bánh, Vũng Tàu', '2024-11-02 18:27:11', 'manh.nguyen@gmail.com', 'nguyenthemanh'),
(16, 'Trương Thị Ngọc', '0989012345', 'password9', '606 Lê Hồng Phong, Huế', '2024-11-02 18:27:11', 'ngoc.truong@gmail.com', 'truongthingoc'),
(17, 'Phạm Minh Tú', '0990123456', 'password10', '707 Trần Hưng Đạo, Thanh Hóa', '2024-11-02 18:27:11', 'tu.pham@gmail.com', 'phamminhtu'),
(18, 'Nguyễn Văn E', '0902345678', 'password11', '808 Lê Thánh Tôn, Đà Lạt', '2024-11-02 18:27:11', 'e.nguyen@gmail.com', 'nguyenvane'),
(19, 'Trần Thị Hạnh', '0913456789', 'password12', '909 Võ Văn Tần, Phan Thiết', '2024-11-02 18:27:11', 'hanh.tran@gmail.com', 'tranthihanh'),
(20, 'Lê Quang Hiếu', '0924567890', 'password13', '111 Nguyễn An Ninh, Bạc Liêu', '2024-11-02 18:27:11', 'hieu.le@gmail.com', 'lequanghieu'),
(21, 'Phạm Thị Lệ', '0935678901', 'password14', '222 Lê Lai, Cà Mau', '2024-11-02 18:27:11', 'le.pham@gmail.com', 'phamthile'),
(22, 'Nguyễn Thị Kim', '0946789012', 'password15', '333 Đinh Bộ Lĩnh, Mỹ Tho', '2024-11-02 18:27:11', 'kim.nguyen@gmail.com', 'nguyenthikim'),
(23, 'Đỗ Minh Tùng', '0957890123', 'password16', '444 Lê Quý Đôn, Biên Hòa', '2024-11-02 18:27:11', 'tung.do@gmail.com', 'dominhtung'),
(24, 'Vũ Thị Tuyết', '0968901234', 'password17', '555 Hồ Xuân Hương, Quảng Ninh', '2024-11-02 18:27:11', 'tuyet.vu@gmail.com', 'vuthituyet'),
(25, 'Nguyễn Văn Khoa', '0979012345', 'password18', '666 Đường số 1, TP.HCM', '2024-11-02 18:27:11', 'khoa.nguyen@gmail.com', 'nguyenkhoa'),
(26, 'Trương Thị Mai', '0980123456', 'password19', '777 Đường số 2, Hà Nội', '2024-11-02 18:27:11', 'mai.truong@gmail.com', 'truongthimai'),
(27, 'Phạm Minh Nhật', '0991234567', 'password20', '888 Đường số 3, Đà Nẵng', '2024-11-02 18:27:11', 'nhat.pham@gmail.com', 'phamminhn'),
(28, 'Nguyễn Văn Tài', '0903456789', 'password21', '999 Đường số 4, Nha Trang', '2024-11-02 18:27:11', 'tai.nguyen@gmail.com', 'nguyentai'),
(29, 'Trần Thị Hồng', '0914567890', 'password22', '101 Đường số 5, Hải Phòng', '2024-11-02 18:27:11', 'hong.tran@gmail.com', 'tranthihong'),
(30, 'Lê Văn Hải', '0925678901', 'password23', '202 Đường số 6, Cần Thơ', '2024-11-02 18:27:11', 'hai.le@gmail.com', 'levanhai'),
(31, 'Phạm Thị Nhung', '0936789012', 'password24', '303 Đường số 7, Vũng Tàu', '2024-11-02 18:27:11', 'nhung.pham@gmail.com', 'phamthinhung'),
(32, 'Nguyễn Văn Mạnh', '0947890123', 'password25', '404 Đường số 8, Bình Dương', '2024-11-02 18:27:11', 'manh.nguyen@gmail.com', 'nguyenmanh'),
(33, 'Trương Thị Lan', '0958901234', 'password26', '505 Đường số 9, Ninh Bình', '2024-11-02 18:27:11', 'lan.truong@gmail.com', 'truongthilan'),
(34, 'Phạm Văn Tuấn', '0969012345', 'password27', '606 Đường số 10, TP.HCM', '2024-11-02 18:27:11', 'tuan.pham@gmail.com', 'phamvantuan'),
(35, 'Nguyễn Thị Thu', '0970123456', 'password28', '707 Đường số 11, Đà Lạt', '2024-11-02 18:27:11', 'thu.nguyen@gmail.com', 'nguyenthithu'),
(36, 'Trần Văn Dũng', '0981234567', 'password29', '808 Đường số 12, Quảng Ninh', '2024-11-02 18:27:11', 'dung.tran@gmail.com', 'tranvandung'),
(37, 'Lê Thị Như', '0992345678', 'password30', '909 Đường số 13, Thanh Hóa', '2024-11-02 18:27:11', 'nhu.le@gmail.com', 'lethinhnhu');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `lienhe`
--

CREATE TABLE `lienhe` (
  `MaLienHe` int(11) NOT NULL,
  `TenKhachHang` varchar(100) NOT NULL,
  `Email` varchar(100) NOT NULL,
  `NoiDung` text NOT NULL,
  `NgayGui` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `lienhe`
--

INSERT INTO `lienhe` (`MaLienHe`, `TenKhachHang`, `Email`, `NoiDung`, `NgayGui`) VALUES
(1, 'huynh hâm', 'nhhuynh1711@gmail.com', 'sâsa', '2024-10-21 17:26:15'),
(2, 'huynh hâm', 'nhhuynh1711@gmail.com', 'sâsa', '2024-10-21 17:29:35'),
(5, 'huynh hâm', '100025915643566@dd', 'dsdsds', '2024-10-21 17:31:33'),
(11, 'Lê Minh K', 'leminhk@gmail.com', 'Đóng gói cẩn thận.', '2024-10-25 06:30:00'),
(12, 'Trương Quang L', 'truongquangl@gmail.com', 'Phục vụ nhanh.', '2024-10-25 07:00:00'),
(13, 'Nguyễn Thị M', 'nguyenthim@gmail.com', 'Chất lượng đồ ăn ổn.', '2024-10-25 07:30:00'),
(14, 'Phạm Văn N', 'phamvann@gmail.com', 'Sẽ quay lại lần sau.', '2024-10-25 08:00:00'),
(15, 'Lý Anh O', 'lyanho@gmail.com', 'Khuyến mãi hấp dẫn.', '2024-10-25 08:30:00'),
(16, 'Đinh Thị P', 'dinhthip@gmail.com', 'Phản hồi tích cực.', '2024-10-25 09:00:00'),
(17, 'Tô Văn Q', 'tovanq@gmail.com', 'Món ăn hơi nguội.', '2024-10-25 09:30:00'),
(18, 'Nguyễn Hữu R', 'nguyenhuur@gmail.com', 'Đóng gói chưa đẹp.', '2024-10-25 10:00:00'),
(19, 'Lê Thị S', 'lethis@gmail.com', 'Giao hàng sai món.', '2024-10-25 10:30:00'),
(20, 'Trần Văn T', 'tranvant@gmail.com', 'Giao hàng nhanh.', '2024-10-25 11:00:00'),
(21, 'Ngô Thị U', 'ngothiu@gmail.com', 'Món ăn thiếu vị.', '2024-10-25 11:30:00'),
(22, 'Hoàng Văn V', 'hoangvanv@gmail.com', 'Dịch vụ tốt.', '2024-10-25 12:00:00'),
(23, 'Đỗ Minh W', 'dominw@gmail.com', 'Nhân viên thiếu chuyên nghiệp.', '2024-10-25 12:30:00'),
(24, 'Phan Thị X', 'phanthix@gmail.com', 'Quán sạch sẽ.', '2024-10-25 13:00:00'),
(25, 'Trịnh Văn Y', 'trinhvany@gmail.com', 'Món ăn hợp khẩu vị.', '2024-10-25 13:30:00'),
(26, 'Nguyễn Văn Z', 'nguyenvanz@gmail.com', 'Cần cải thiện menu.', '2024-10-25 14:00:00'),
(27, 'Trần Thị A1', 'tranthia1@gmail.com', 'Phản hồi về giá.', '2024-10-25 14:30:00'),
(29, 'Ngô Minh C3', 'ngominhc3@gmail.com', 'Nhạc nền hơi ồn.', '2024-10-25 15:30:00'),
(30, 'Bùi Quang D4', 'buiquangd4@gmail.com', 'Hài lòng với dịch vụ.', '2024-10-25 16:00:00');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `nhanvien`
--

CREATE TABLE `nhanvien` (
  `MaNhanVien` int(11) NOT NULL,
  `HoTen` varchar(100) NOT NULL,
  `SoDienThoai` varchar(15) NOT NULL,
  `TaiKhoan` varchar(100) NOT NULL,
  `MatKhau` varchar(255) NOT NULL,
  `NgayVaoLam` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `nhanvien`
--

INSERT INTO `nhanvien` (`MaNhanVien`, `HoTen`, `SoDienThoai`, `TaiKhoan`, `MatKhau`, `NgayVaoLam`) VALUES
(1, 'Ngô Hữu Huỳnh', '0399914942', 'nhhuynh', '12345678', '2024-09-30 17:00:00'),
(7, 'Nguyễn Văn An', '0912345678', 'nguyenvanan', 'password1', '2024-11-02 18:25:03'),
(8, 'Trần Thị Bình', '0909876543', 'tranthibinh', 'password2', '2024-11-02 18:25:03'),
(9, 'Lê Quang Cường', '0911122233', 'lequangcuong', 'password3', '2024-11-02 18:25:03'),
(10, 'Phạm Thị Dung', '0987654321', 'phamthidung', 'password4', '2024-11-02 18:25:03'),
(11, 'Nguyễn Thị Hương', '0934567890', 'nguyenthihuong', 'password5', '2024-11-02 18:25:03'),
(12, 'Đỗ Minh Khoa', '0923456789', 'dominhkhoa', 'password6', '2024-11-02 18:25:03'),
(13, 'Vũ Văn Lâm', '0956781234', 'vuvanlam', 'password7', '2024-11-02 18:25:03'),
(14, 'Nguyễn Thế Mạnh', '0945678901', 'nguyenthemanh', 'password8', '2024-11-02 18:25:03'),
(15, 'Trương Thị Ngọc', '0961234567', 'truongthingoct', 'password9', '2024-11-02 18:25:03'),
(16, 'Phạm Minh Tú', '0976543210', 'phamminhtu', 'password10', '2024-11-02 18:25:03');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `sanpham`
--

CREATE TABLE `sanpham` (
  `MaSanPham` int(11) NOT NULL,
  `TenSanPham` varchar(100) NOT NULL,
  `DanhMuc` varchar(50) DEFAULT NULL,
  `Gia` decimal(10,2) NOT NULL,
  `MoTa` varchar(255) DEFAULT NULL,
  `HinhAnh` varchar(255) DEFAULT NULL,
  `NgayTao` timestamp NOT NULL DEFAULT current_timestamp(),
  `trangthai` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `sanpham`
--

INSERT INTO `sanpham` (`MaSanPham`, `TenSanPham`, `DanhMuc`, `Gia`, `MoTa`, `HinhAnh`, `NgayTao`, `trangthai`) VALUES
(14, 'Burger Bò', 'Các Loại Burger', 25000.00, 'Burger với thịt bò tươi cùng sốt đặc biệt, mang đến hương vị khó quên.', 'Imgsanpham/bergerbo.jpg', '2024-10-27 13:15:43', 'Kinh doanh'),
(15, 'Burger Gà', 'Các Loại Burger', 27999.00, 'Thịt gà giòn rụm, kết hợp với rau tươi và sốt mayonnaise béo ngậy.', 'Imgsanpham/bergerga.jpg', '2024-10-27 13:16:39', 'Kinh doanh'),
(16, 'Burger chay', 'Các Loại Burger', 31500.00, 'Lựa chọn hoàn hảo cho người ăn chay với nguyên liệu tự nhiên và đậu hạt.', 'Imgsanpham/bergerchay.jpg', '2024-10-27 13:17:51', 'Kinh doanh'),
(17, 'Burger tôm', 'Các Loại Burger', 40000.00, 'Sự kết hợp độc dáo với berger truyền thống và hương vị tôm thơm ngon.', 'Imgsanpham/hbgertom.webp', '2024-10-27 13:19:25', 'Kinh doanh'),
(18, 'Burger Phi lê Cá phô mai', 'Các Loại Burger', 56000.00, 'Burger Phi lê Cá phô mai là sự kết hợp hoàn hảo giữa miếng phi lê cá giòn rụm bên ngoài, mềm thơm bên trong, phủ thêm lớp phô mai béo ngậy tan chảy trên bề mặt. Bánh mì burger được nướng vàng ươm, kèm với rau tươi xanh mát và sốt đặc biệt tạo nên hương vị', 'Imgsanpham/xfof_bb.png.pagespeed.ic.X358SC8trh.webp', '2024-11-02 16:18:40', 'Kinh doanh'),
(19, 'Burger Big Mac', 'Các Loại Burger', 76000.00, 'Burger 2 lớp bò, phô-mai, rau tươi và sốt Big Mac.', 'Imgsanpham/bigmac_bb.png', '2024-11-02 16:26:02', 'Kinh doanh'),
(20, 'Burger Xúc Xích - 337 Kcal', 'Các Loại Burger', 36000.00, 'Burger Xúc Xích mang đến hương vị đậm đà từ miếng xúc xích thơm ngon, được nướng vừa tới để giữ được độ dai và giòn hoàn hảo. Bánh mì burger mềm mại kết hợp với rau xà lách tươi, dưa leo giòn, và nước sốt đặc biệt, tất cả tạo nên một món ăn vừa ngon miệng', 'Imgsanpham/xsausagebg_bb.png.pagespeed.ic.HvIz0h1bPE.webp', '2024-11-02 16:27:55', 'Kinh doanh'),
(21, 'Burger Bò Hoàng Gia Đặc Biệt', 'Các Loại Burger', 89000.00, 'Burger Bò Hoàng Gia Đặc Biệt là món burger đỉnh cao dành cho những thực khách yêu thích sự sang trọng và đậm đà trong từng miếng cắn. Với miếng bò nướng mọng nước, được chế biến từ thịt bò tươi hảo hạng, kết hợp cùng lớp phô mai tan chảy, thịt xông khói g', 'Imgsanpham/xmcroyaldlx_bb.png.pagespeed.ic.QwZWgunRiy.webp', '2024-11-02 16:30:18', 'Kinh doanh'),
(22, 'Burger Gà Nhỏ Mayo', 'Các Loại Burger', 36000.00, 'Burger Gà Nhỏ Mayo là sự lựa chọn lý tưởng cho những ai yêu thích sự nhẹ nhàng và thanh mát. Miếng gà chiên vàng giòn rụm, bên trong mềm mọng và thấm đượm hương vị, được kẹp giữa hai lớp bánh mì burger nướng vàng. Điểm nhấn của món burger này là lớp sốt m', 'Imgsanpham/xchickbg.png.pagespeed.ic.vcPtK7csEr.webp', '2024-11-02 16:33:03', 'Kinh doanh'),
(23, 'Salad lắc', 'Món Ăn Kèm', 35000.00, 'Salad lắc là món ăn tươi ngon và đầy màu sắc, được kết hợp từ các loại rau củ tươi giòn như xà lách, cà chua bi, dưa leo, bắp cải tím, và cà rốt. Điểm đặc biệt của món salad này là cách thưởng thức độc đáo: tất cả các nguyên liệu được đặt trong một chiếc ', 'Imgsanpham/xsalad500.png.pagespeed.ic.is8aFQ1qL_.webp', '2024-11-02 16:35:49', 'Kinh doanh'),
(24, 'Khoai Tây Chiên', 'Món Ăn Kèm', 20000.00, 'Khoai tây chiên giòn tan, ăn kèm với sốt cà chua hoặc phô mai.', 'Imgsanpham/khoaitaychien.jpg', '2024-11-02 16:54:13', 'Kinh doanh'),
(25, 'Vòng Hành Tây Chiên', 'Món Ăn Kèm', 23000.00, 'Vòng hành tây chiên vàng giòn, thích hợp cho mọi bữa ăn.', 'Imgsanpham/vonghanhtaychien.jpg', '2024-11-02 16:55:30', 'Kinh doanh'),
(26, 'Sốt Cà Chua', 'Nước Sốt và Phụ Gia', 5000.00, 'Tương cà chua đậm đà, làm món ăn thêm hấp dẫn.', 'Imgsanpham/sotca.webp', '2024-11-02 16:56:45', 'Kinh doanh'),
(27, 'Sốt BBQ', 'Nước Sốt và Phụ Gia', 5000.00, 'Sốt BBQ thơm ngon, mang đến hương vị đậm đà cho món ăn.', 'Imgsanpham/sotbbq.png', '2024-11-02 16:57:36', 'Kinh doanh'),
(28, 'Coca-Cola Chai 350 ml', 'Đồ Uống', 20000.00, 'Nước giải khát có ga, giúp bữa ăn thêm sảng khoái.Chai 350ml', 'Imgsanpham/coca.jpg', '2024-11-02 16:58:48', 'Kinh doanh'),
(29, 'Milkshake', 'Đồ Uống', 30000.00, 'Sữa lắc ngọt ngào với nhiều hương vị hấp dẫn.', 'Imgsanpham/milshake.jpg', '2024-11-02 16:59:39', 'Kinh doanh'),
(30, 'Combo Burger Bò Nướng + 1 Gà Rán', 'Combo Khuyến Mãi', 60000.00, 'Combo Burger Bò Nướng + 1 Gà Rán là sự kết hợp hoàn hảo giữa hương vị đậm đà của miếng burger bò nướng thơm lừng và sự giòn tan của gà rán vàng ươm. Burger bò được chế biến từ thịt bò nướng mềm mọng, phủ thêm phô mai tan chảy, rau tươi và sốt đặc biệt, tấ', 'Imgsanpham/xCSO_1851combo_gabo.png.pagespeed.ic.kdTndZXnQz.png', '2024-11-02 17:03:56', 'Kinh doanh'),
(31, 'Combo 3B', 'Combo Khuyến Mãi', 230000.00, 'Phù hợp cho 3 người ăn Gồm Burger + Coca + Khoai tây chiên', 'Imgsanpham/xCombo-3B.png.pagespeed.ic.u3std1sDwA.png', '2024-11-02 17:06:44', 'Kinh doanh'),
(32, 'Fanta®', 'Đồ Uống', 22000.00, 'Nước cam ép Fanta', 'Imgsanpham/xhero-pdt-Fanta-201703_0.png.pagespeed.ic.VTCOuWWJR9.png', '2024-11-02 17:12:09', 'Kinh doanh'),
(33, 'Coca-Cola® - 150 Kcal', 'Đồ Uống', 22000.00, 'Coca tươi cốc 500ml', 'Imgsanpham/xmcd-food-beverages-soft-drinks-coke.png.pagespeed.ic.DnHv1b1E6D.webp', '2024-11-02 17:13:47', 'Kinh doanh'),
(34, 'Sprite®', 'Đồ Uống', 21000.00, 'Sprite tươi cốc 500ml', 'Imgsanpham/xProduct_thumb_Sprite.png.pagespeed.ic.JeSrsw7hgQ.png', '2024-11-02 17:16:56', 'Kinh doanh'),
(35, 'Nước suối', 'Đồ Uống', 10000.00, 'Nước suối thanh khiết ', 'Imgsanpham/xdasani_water.png.pagespeed.ic.9dIOxB9nfk.png', '2024-11-02 17:17:58', 'Kinh doanh'),
(36, 'Milo™ - 110 Kcal', 'Đồ Uống', 20000.00, 'Milo hộp 110 ml', 'Imgsanpham/xmilo.png.pagespeed.ic.owUoRzA7-9.png', '2024-11-02 17:19:06', 'Kinh doanh'),
(37, 'Kem tươi (Ly)', 'Đồ Uống', 30000.00, 'Kem tươi mát ngọt ngào', 'Imgsanpham/kem-tuoi-ngon.jpg', '2024-11-02 17:21:35', 'Kinh doanh'),
(38, 'Sốt mayonnaise', 'Nước Sốt và Phụ Gia', 5000.00, 'Mayonnaise là một loại nước sốt đặc làm tăng hương vị cho bánh sandwich, bánh mì kẹp thịt, salad trộn, khoai tây chiên... ', 'Imgsanpham/3_3-sk.webp', '2024-11-02 17:26:07', 'Kinh doanh'),
(39, 'Tương ớt', 'Nước Sốt và Phụ Gia', 5000.00, 'Bùng nổ hương vị món ăn', 'Imgsanpham/1.jpg', '2024-11-02 17:28:05', 'Kinh doanh'),
(40, 'Salad bắp cải', 'Món Ăn Kèm', 20000.00, 'Bắp cải thái nhỏ trộn với cà rốt và sốt mayonnaise hoặc dầu giấm.', 'Imgsanpham/me-dam-goi-y-cac-mon-salad-cuc-don-gian-tuoi-mat-cho-ngay-he-5-1722227751-847-width780height585.jpg', '2024-11-02 17:36:11', 'Kinh doanh'),
(41, ' 1 miếng gà rán - 183 Kcal', 'Món Ăn Kèm', 25000.00, 'Gà kia ai rán mà ròn !', 'Imgsanpham/x1-ga-ran.png.pagespeed.ic.XO0nTsiztl.png', '2024-11-02 17:42:16', 'Kinh doanh'),
(42, '3 miếng Cánh Gà BGWings™', 'Món Ăn Kèm', 69000.00, 'Những món ăn có thể chia sẻ cùng bạn bè', 'Imgsanpham/x3pcs_chicken_mcwings.png.pagespeed.ic._feqAqwncK.png', '2024-11-02 17:43:49', 'Kinh doanh'),
(43, '5 miếng gà rán', 'Món Ăn Kèm', 179000.00, 'Combo 5 miếng gà rán .Vui vẻ cùng gia đình', 'Imgsanpham/x5-ga-ran.png.pagespeed.ic.P-5fm5oV44.png', '2024-11-02 17:45:11', 'Kinh doanh'),
(44, 'Salad khoai tây với ngô tươi và húng quế', 'Món Ăn Kèm', 30000.00, 'Khoai tây đỏ baby lý tưởng cho món salad khoai tây vì lớp vỏ mềm và phần bên trong kem của chúng kết dính với nhau, ngay cả khi đã nấu chín. Lucy Morrow thêm ngô vàng tươi, hành tây vàng, cần tây, húng quế và thì là. Món này sẽ sẵn sàng sau hai giờ và có ', 'Imgsanpham/potato-salad-with-fresh-corn-and-basil-FT-RECIPE0620-b5301ba610b4407ca7b48800b0c6da4d.webp', '2024-11-02 17:48:24', 'Kinh doanh'),
(45, 'Khoai tây chiên kiểu bánh quế của Kwame', 'Món Ăn Kèm', 37000.00, 'Berbere, một loại gia vị hỗn hợp của Ethiopia, mang đến vị cay ngọt của cam quýt cho món khoai tây chiên này. Đầu bếp mới xuất sắc nhất năm 2019 của F&W Kwame Onwuachi trộn nó với muối kosher rồi rắc lên trên món khoai tây chiên đã hoàn thành.', 'Imgsanpham/kwames-waffle-fries-ft-RECIPE0718-c057c2cefd5c448e957d56dea3d10e87.webp', '2024-11-02 17:49:32', 'Kinh doanh'),
(46, 'Khoai tây nướng với bơ hẹ', 'Món Ăn Kèm', 57000.00, 'Sản phẩm mùa hè nướng kết hợp với nước sốt thảo mộc có ớt trong món ăn tươi sáng này từ Paige Grandjean và Liz Mervosh của F&W. Sử dụng máy xay sinh tố hoặc máy chế biến thực phẩm để chế biến nước sốt sẽ giữ được màu sắc bằng cách cắt và phủ dầu lên thảo ', 'Imgsanpham/HD-201406-r-grilled-baked-potatoes-with-chive-butter-745ab1e49eff40e0a5729a6d0704fb2f.webp', '2024-11-02 17:51:26', 'Kinh doanh'),
(47, 'combo cheese burger', 'Combo Khuyến Mãi', 65000.00, '1 Burger bò nướng phô mai + Khoai chiên  + 1 Đồ uống', 'Imgsanpham/combo-burger-cheeseburger_hero.jpg', '2024-11-02 17:55:29', 'Kinh doanh'),
(48, 'Combo \"sống chất\"', 'Combo Khuyến Mãi', 150000.00, '2 Burger Tắm Bò Phô Mai + 2 Cá Cuộn Rong Biển + 1 Khoai Tây Chiên Tắm Phô Mai + 2 Nước Ngọt', 'Imgsanpham/combo-so_ng-cha_t.jpg', '2024-11-02 17:56:37', 'Kinh doanh'),
(49, 'Combo double whopper jr. ', 'Combo Khuyến Mãi', 95000.00, 'combo double whopper jr.\r\n1 Burger 2 miếng bò nướng + Khoai chiên  + 1 Đồ uống', 'Imgsanpham/combo-doublewhopper_2.jpg', '2024-11-02 17:58:28', 'Kinh doanh'),
(50, 'Combo extreme cheese lover', 'Combo Khuyến Mãi', 99999.00, '1 Burger bò tắm phô mai + Khoai chiên + 4 gà cuộn rong biển + 1 Đồ uống\r\n\r\n', 'Imgsanpham/combo-ex-cheese-whopper-lover-new.jpg', '2024-11-02 18:00:08', 'Kinh doanh'),
(51, 'Combo family chic\'n lovers', 'Combo Khuyến Mãi', 230999.00, '6 Cánh Gà BBQ + 2 Khoai Tây Chiên + 3 Nước ngọt', 'Imgsanpham/combo-chic_n-lover-fam_1.jpg', '2024-11-02 18:02:13', 'Kinh doanh');

--
-- Chỉ mục cho các bảng đã đổ
--

--
-- Chỉ mục cho bảng `chitietdonhang`
--
ALTER TABLE `chitietdonhang`
  ADD PRIMARY KEY (`MaChiTiet`),
  ADD KEY `MaDonHang` (`MaDonHang`),
  ADD KEY `MaSanPham` (`MaSanPham`);

--
-- Chỉ mục cho bảng `donhang`
--
ALTER TABLE `donhang`
  ADD PRIMARY KEY (`MaDonHang`),
  ADD KEY `MaKhachHang` (`MaKhachHang`);

--
-- Chỉ mục cho bảng `giohang`
--
ALTER TABLE `giohang`
  ADD PRIMARY KEY (`MaGioHang`),
  ADD KEY `MaKhachHang` (`MaKhachHang`),
  ADD KEY `MaSanPham` (`MaSanPham`);

--
-- Chỉ mục cho bảng `khachhang`
--
ALTER TABLE `khachhang`
  ADD PRIMARY KEY (`MaKhachHang`),
  ADD UNIQUE KEY `SoDienThoai` (`SoDienThoai`) USING BTREE;

--
-- Chỉ mục cho bảng `lienhe`
--
ALTER TABLE `lienhe`
  ADD PRIMARY KEY (`MaLienHe`);

--
-- Chỉ mục cho bảng `nhanvien`
--
ALTER TABLE `nhanvien`
  ADD PRIMARY KEY (`MaNhanVien`),
  ADD UNIQUE KEY `Email` (`TaiKhoan`),
  ADD UNIQUE KEY `SoDienThoai` (`SoDienThoai`);

--
-- Chỉ mục cho bảng `sanpham`
--
ALTER TABLE `sanpham`
  ADD PRIMARY KEY (`MaSanPham`);

--
-- AUTO_INCREMENT cho các bảng đã đổ
--

--
-- AUTO_INCREMENT cho bảng `chitietdonhang`
--
ALTER TABLE `chitietdonhang`
  MODIFY `MaChiTiet` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT cho bảng `donhang`
--
ALTER TABLE `donhang`
  MODIFY `MaDonHang` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT cho bảng `giohang`
--
ALTER TABLE `giohang`
  MODIFY `MaGioHang` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=61;

--
-- AUTO_INCREMENT cho bảng `khachhang`
--
ALTER TABLE `khachhang`
  MODIFY `MaKhachHang` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- AUTO_INCREMENT cho bảng `lienhe`
--
ALTER TABLE `lienhe`
  MODIFY `MaLienHe` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT cho bảng `nhanvien`
--
ALTER TABLE `nhanvien`
  MODIFY `MaNhanVien` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT cho bảng `sanpham`
--
ALTER TABLE `sanpham`
  MODIFY `MaSanPham` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=52;

--
-- Các ràng buộc cho các bảng đã đổ
--

--
-- Các ràng buộc cho bảng `chitietdonhang`
--
ALTER TABLE `chitietdonhang`
  ADD CONSTRAINT `chitietdonhang_ibfk_1` FOREIGN KEY (`MaDonHang`) REFERENCES `donhang` (`MaDonHang`) ON DELETE CASCADE,
  ADD CONSTRAINT `chitietdonhang_ibfk_2` FOREIGN KEY (`MaSanPham`) REFERENCES `sanpham` (`MaSanPham`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `donhang`
--
ALTER TABLE `donhang`
  ADD CONSTRAINT `donhang_ibfk_1` FOREIGN KEY (`MaKhachHang`) REFERENCES `khachhang` (`MaKhachHang`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `giohang`
--
ALTER TABLE `giohang`
  ADD CONSTRAINT `giohang_ibfk_1` FOREIGN KEY (`MaKhachHang`) REFERENCES `khachhang` (`MaKhachHang`) ON DELETE CASCADE,
  ADD CONSTRAINT `giohang_ibfk_2` FOREIGN KEY (`MaSanPham`) REFERENCES `sanpham` (`MaSanPham`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
