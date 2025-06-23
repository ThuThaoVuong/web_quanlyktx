-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Máy chủ: 127.0.0.1
-- Thời gian đã tạo: Th6 17, 2025 lúc 05:25 PM
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
-- Cơ sở dữ liệu: `quanly2`
--

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `contract`
--

CREATE TABLE `contract` (
  `id` int(11) NOT NULL,
  `users_id` varchar(20) DEFAULT NULL,
  `room_id` int(11) DEFAULT NULL,
  `start_date` date DEFAULT NULL,
  `month_living` int(11) DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `admin_id` varchar(20) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `status` tinyint(4) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `contract`
--

INSERT INTO `contract` (`id`, `users_id`, `room_id`, `start_date`, `month_living`, `end_date`, `admin_id`, `created_at`, `status`) VALUES
(1, 'B20DCCN001', 1, '2025-01-01', 6, '2025-07-01', 'NV1', '2024-12-12 00:00:00', 1),
(2, 'B20DCCN002', 2, '2025-02-01', 4, '2025-06-01', 'NV1', '2025-01-20 00:00:00', 4),
(3, 'B20DCCN008', 4, '2025-05-01', 6, '2025-11-01', 'NV1', '2025-04-28 21:14:20', 1),
(7, 'B20DCCN004', 4, '2025-05-02', 6, '2025-11-02', 'NV1', '2025-04-29 15:25:20', 1);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `ktx`
--

CREATE TABLE `ktx` (
  `id` int(11) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `info` text DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `status` tinyint(4) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `ktx`
--

INSERT INTO `ktx` (`id`, `name`, `address`, `info`, `image`, `status`) VALUES
(1, 'KTX A', 'Hà Đông, Hà Nội', 'KTX hiện đại với đầy đủ CSVC', 'anh_ktx_1.jpg', 1),
(2, 'KTX B', 'Nam Từ Liêm, Hà Nội', 'KTX thuận tiện cho việc đi lại', 'anh_ktx_1.jpg', 1),
(3, 'KTX C', 'Hòa Lạc, Hà Nội', 'KTX hòa hợp với thiên nhiên', 'anh_ktx_1.jpg', 2),
(4, 'abcd', 'Ngọc Trục, Hà Nội', 'Ktx mới xây hiện đại', '1748012155_anh_ktx_3.jpg', 0);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `report`
--

CREATE TABLE `report` (
  `id` int(11) NOT NULL,
  `sender_id` varchar(20) DEFAULT NULL,
  `receiver_id` varchar(20) DEFAULT NULL,
  `title` varchar(100) DEFAULT NULL,
  `content` text DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `status` tinyint(4) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `report`
--

INSERT INTO `report` (`id`, `sender_id`, `receiver_id`, `title`, `content`, `image`, `created_at`, `status`) VALUES
(1, 'B20DCCN001', 'NV1', 'Báo cáo về cơ sở vật chất', 'Phòng bị rò rỉ nước.', NULL, '2025-02-20 00:00:00', 2),
(2, 'B20DCCN002', 'NV1', 'Báo cáo về cơ sở vật chất', 'Thiết bị điện hỏng.', NULL, '2025-03-29 00:00:00', 2),
(3, 'NV1', 'B20DCCN001', 'Thông báo về cơ sở vật chất', 'Đã sửa chữa xong', NULL, '2025-03-01 00:00:00', 1),
(4, 'NV1', 'B20DCCN009', 'Thông báo về lịch cắt điện', 'Ngày 30/3/2025 sẽ cắt điện', NULL, '2025-03-27 00:00:00', 0),
(5, 'NV1', 'B20DCCN002', 'Thông báo về lịch sửa phòng', 'KTX sẽ sửa phòng vào buổi sáng thứ 2 tuần sau', NULL, '2025-05-11 00:00:00', 0),
(6, 'NV1', 'B20DCCN001', 'Thông báo về lịch cắt điện', 'KTX sẽ cắt điện để phục vụ sửa điện', NULL, '2025-05-01 00:00:00', 1),
(7, 'NV1', 'B20DCCN002', 'Thông báo về lịch cắt điện', 'KTX sẽ cắt điện để phục vụ sửa điện', NULL, '2025-05-01 00:00:00', 1),
(8, 'B20DCCN008', 'NV1', 'Báo cáo về cơ sở vật chất', 'Quạt bị hỏng', NULL, '2025-05-29 09:01:36', 1),
(9, 'B20DCCN008', 'NV1', 'Báo cáo về cơ sở vật chất', 'Ổ điện bị hỏng', NULL, '2025-05-29 09:06:29', 1),
(10, 'B20DCCN008', 'NV1', 'Báo cáo thử', 'test', NULL, '2025-05-29 09:08:16', 1),
(14, 'nv@gmail.com', 'NV1', 'Liên hệ từ người dùng chưa đăng nhập', '123', NULL, '2025-05-29 09:47:30', 2),
(16, 'haha@gaga.lala', 'NV1', 'Liên hệ từ người dùng chưa đăng nhập', NULL, NULL, '2025-05-29 16:02:43', 0),
(17, 'haha@gaga.lala', 'NV1', 'Liên hệ từ người dùng chưa đăng nhập', 'tôi muốn hỏi về chỗ để xe', NULL, '2025-05-29 16:04:42', 1),
(18, 'NV1', 'B20DCCN001', 'Thông báo về việc phân loại rác', 'KTX yêu cầu mọi người cần phân loại rác trước khi vứt.', NULL, '2025-05-01 00:00:00', 1);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `room`
--

CREATE TABLE `room` (
  `id` int(11) NOT NULL,
  `name` varchar(50) DEFAULT NULL,
  `area` varchar(100) DEFAULT NULL,
  `gender` tinyint(4) DEFAULT NULL,
  `slot` int(11) DEFAULT NULL,
  `price` decimal(10,2) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `ktx_id` int(11) DEFAULT NULL,
  `status` tinyint(4) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `room`
--

INSERT INTO `room` (`id`, `name`, `area`, `gender`, `slot`, `price`, `image`, `ktx_id`, `status`) VALUES
(1, 'Phòng 101', 'Tầng 1', 0, 4, 600000.00, 'anh_room_1.jpg', 1, 1),
(2, 'Phòng 201', 'Tầng 2', 1, 6, 450000.00, 'anh_room_2.jpg', 2, 1),
(3, 'Phòng 301', 'Tầng 3', 0, 4, 500000.00, 'anh_room_3.jpg', 3, 1),
(4, 'Phòng 102', 'Tầng 1', 1, 4, 600000.00, '1748098147_anh_room_3.jpg', 1, 1),
(6, 'Phòng 202', 'Tầng 2', 0, 6, 500000.00, '1748507324_anh_room_2.jpg', 2, 1);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `room_bill`
--

CREATE TABLE `room_bill` (
  `id` int(11) NOT NULL,
  `room_id` int(11) DEFAULT NULL,
  `month` int(11) DEFAULT NULL,
  `year` int(11) DEFAULT NULL,
  `electricity_fee` decimal(10,2) DEFAULT NULL,
  `water_fee` decimal(10,2) DEFAULT NULL,
  `total` decimal(11,0) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `status` tinyint(4) DEFAULT NULL,
  `paid_by_user_id` varchar(20) DEFAULT NULL,
  `paid_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `room_bill`
--

INSERT INTO `room_bill` (`id`, `room_id`, `month`, `year`, `electricity_fee`, `water_fee`, `total`, `created_at`, `status`, `paid_by_user_id`, `paid_at`) VALUES
(1, 1, 1, 2025, 100000.00, 50000.00, 150000, '2025-02-02 00:00:00', 2, 'B20DCCN001', '2025-02-02 00:00:00'),
(2, 2, 2, 2025, 120000.00, 60000.00, 180000, '2025-03-01 00:00:00', 2, 'B20DCCN002', '2025-03-04 00:00:00'),
(3, 1, 4, 2025, 200000.00, 100000.00, 300000, '2025-05-01 00:00:00', 2, 'B20DCCN001', '2025-05-02 00:00:00'),
(4, 1, 3, 2025, 200000.00, 60000.00, 260000, '2025-04-11 00:00:00', 1, '', NULL);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `users`
--

CREATE TABLE `users` (
  `msv` varchar(20) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `gender` tinyint(4) DEFAULT NULL,
  `dob` date DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `password` varchar(100) DEFAULT NULL,
  `avatar` varchar(255) DEFAULT NULL,
  `status` tinyint(4) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `users`
--

INSERT INTO `users` (`msv`, `name`, `gender`, `dob`, `phone`, `address`, `email`, `password`, `avatar`, `status`) VALUES
('B20DCCN001', 'Nguyễn Văn A', 0, '2002-05-10', '0912345678', 'Bắc Ninh', 'a@student.edu.vn', '$2y$10$tqll75AtBuKLAoNp1X5UceEO2GAiP8UEVjPyjT1aaHhy9Q/27lrJ.', 'avatar.jpg', 1),
('B20DCCN002', 'Trần Thị B', 1, '2002-08-15', '0911122233', 'Hà Nam', 'b@student.edu.vn', '$2y$10$8zCzil1iAl4Onwk8ln5kG./8jcxIWrvS4vaqGte1kOK0/kR8gRkki', 'avatar.jpg', 1),
('B20DCCN004', 'Nguyễn Văn B', 0, '2003-01-28', '0123456789', 'Bắc Ninh', 'sv4@gmail.com', '$2y$10$CAyv2VGCqCfHU8CvY6JwLeBJjbbNtNtxH1/W6KnWqbhJ2EGPLlZIy', 'avatar.jpg', 1),
('B20DCCN008', 'abc', 1, '2003-10-19', '0123456789', 'Hà Đông, Hà Nội', 'sv3@gmail.com', '$2y$10$huP4VxgucH24jfYZHyDceeDzFUeY3aqob3tX1e.DtBN3JXAfipXM.', 'avatar.jpg', 1),
('B20DCCN009', 'abc', 1, '2004-04-01', '0123456789', 'Hà Đông, Hà Nội', 'sv1@gmail.com', '$2y$10$RnS3y51kBJJoKq9stse9SusOJoUtqay.7eVWU6A9OIew5/eJ72nM2', '1747923359_img004-028.png', 2),
('NV1', 'Lê Thị C', 1, '1990-01-01', '0900999888', 'Hải Phòng', 'admin@ktx.vn', '$2y$10$0wH1ORVV5QOmHafQPM0ZEO4LG/WRDN4Or/KmXwVWrO4mZqlxyVYSG', 'avatar.jpg', 2);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `users_bill`
--

CREATE TABLE `users_bill` (
  `id` int(11) NOT NULL,
  `users_id` varchar(20) DEFAULT NULL,
  `month` tinyint(4) DEFAULT NULL,
  `year` smallint(6) DEFAULT NULL,
  `room_fee` decimal(10,2) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `status` tinyint(4) DEFAULT NULL,
  `paid_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `users_bill`
--

INSERT INTO `users_bill` (`id`, `users_id`, `month`, `year`, `room_fee`, `created_at`, `status`, `paid_at`) VALUES
(1, 'B20DCCN001', 1, 2025, 500000.00, '2025-02-01 00:00:00', 2, '2025-02-02 00:00:00'),
(2, 'B20DCCN002', 2, 2025, 600000.00, '2025-03-01 00:00:00', 2, NULL),
(3, 'B20DCCN002', 3, 2025, 450000.00, '2025-04-01 00:00:00', 1, NULL);

--
-- Chỉ mục cho các bảng đã đổ
--

--
-- Chỉ mục cho bảng `contract`
--
ALTER TABLE `contract`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `ktx`
--
ALTER TABLE `ktx`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `report`
--
ALTER TABLE `report`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `room`
--
ALTER TABLE `room`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `room_bill`
--
ALTER TABLE `room_bill`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`msv`);

--
-- Chỉ mục cho bảng `users_bill`
--
ALTER TABLE `users_bill`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT cho các bảng đã đổ
--

--
-- AUTO_INCREMENT cho bảng `contract`
--
ALTER TABLE `contract`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT cho bảng `ktx`
--
ALTER TABLE `ktx`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT cho bảng `report`
--
ALTER TABLE `report`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT cho bảng `room`
--
ALTER TABLE `room`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT cho bảng `room_bill`
--
ALTER TABLE `room_bill`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT cho bảng `users_bill`
--
ALTER TABLE `users_bill`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
