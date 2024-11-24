-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Máy chủ: 127.0.0.1
-- Thời gian đã tạo: Th10 24, 2024 lúc 04:37 PM
-- Phiên bản máy phục vụ: 10.4.32-MariaDB
-- Phiên bản PHP: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Cơ sở dữ liệu: `totnghiep`
--

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `devices`
--

CREATE TABLE `devices` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `status` enum('ON','OFF') DEFAULT 'OFF',
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `node` varchar(50) DEFAULT NULL,
  `last_activated` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Đang đổ dữ liệu cho bảng `devices`
--

INSERT INTO `devices` (`id`, `name`, `status`, `updated_at`, `node`, `last_activated`) VALUES
(1, 'fan', 'OFF', '2024-11-23 15:42:47', 'Node 1', NULL),
(2, 'pump', 'OFF', '2024-11-23 15:41:25', 'Node 1', NULL),
(3, 'shade', 'ON', '2024-11-23 15:43:10', 'Node 2', '2024-11-23 22:43:10'),
(4, 'dioxide sensor', 'OFF', '2024-11-23 02:15:37', 'Node 2', NULL);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `device_status`
--

CREATE TABLE `device_status` (
  `id` int(11) NOT NULL,
  `device_name` varchar(50) NOT NULL,
  `status` varchar(10) NOT NULL DEFAULT 'OFF'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `notification`
--

CREATE TABLE `notification` (
  `id` int(11) NOT NULL,
  `content` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `notification`
--

INSERT INTO `notification` (`id`, `content`, `created_at`) VALUES
(2, 'Nhiệt độ lớn hơn 30 độ C, đã bật quạt', '2024-11-22 06:09:39'),
(3, 'Cường độ ánh sáng lớn hơn 2000, đã bật máy che', '2024-11-22 06:09:39'),
(4, 'Nhiệt độ lớn hơn 30 độ C, đã bật quạt', '2024-11-23 06:02:01'),
(5, 'Cường độ ánh sáng lớn hơn 2000, đã bật máy che', '2024-11-23 06:02:01'),
(6, 'Độ ẩm đất đã đạt ngưỡng, máy bơm đã được tắt', '2024-11-23 15:41:25'),
(7, 'Cường độ ánh sáng lớn hơn 2000, đã bật mái che', '2024-11-23 15:41:27'),
(8, 'Nhiệt độ không giảm, quạt có thể bị hỏng.', '2024-11-23 15:41:29'),
(9, 'Cường độ ánh sáng lớn hơn 2000, đã bật mái che', '2024-11-23 15:43:10'),
(10, 'Nhiệt độ không giảm, quạt có thể bị hỏng.', '2024-11-23 15:43:11');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `recorddata`
--

CREATE TABLE `recorddata` (
  `id` varchar(10) NOT NULL,
  `board` varchar(50) DEFAULT NULL,
  `temperature` float DEFAULT NULL,
  `humidity` float DEFAULT NULL,
  `soil` float DEFAULT NULL,
  `light` float DEFAULT NULL,
  `concentration` float DEFAULT NULL,
  `time` time DEFAULT NULL,
  `date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Đang đổ dữ liệu cho bảng `recorddata`
--

INSERT INTO `recorddata` (`id`, `board`, `temperature`, `humidity`, `soil`, `light`, `concentration`, `time`, `date`) VALUES
('127wsnjla', 'esp8266_01', 27.6, 80, 37, 12332, 45, '04:39:14', '2024-11-22'),
('1kDCPOrA8', 'esp8266_01', 29, 48, 28, 4000, 2000, '13:07:51', '2024-11-22'),
('5QdeTkAgL', 'esp8266_01', 27.8, 80, 37, 12332, 45, '04:51:41', '2024-11-22'),
('6a2xIbcX5', 'esp8266_01', 29, 46, 47, 35, 1100, '13:37:57', '2024-11-22'),
('6ZAeyY0tQ', 'esp8266_01', 29, 48, 28, 31, 2000, '13:07:24', '2024-11-22'),
('8Q5moD6jt', 'esp8266_01', 29, 46, 47, 35, 1100, '13:26:21', '2024-11-22'),
('8Uic1BkfH', 'esp8266_01', 35, 48, 28, 4000, 2000, '13:09:39', '2024-11-22'),
('9640wxnPJ', 'esp8266_01', 30, 60, 80, 2000, 10, '13:04:23', '2024-11-22'),
('buK9ZSFBQ', 'esp8266_01', 35, 100, 34, 12345, 123, '13:02:01', '2024-11-23'),
('BVU0eE2df', 'esp8266_01', 29, 46.3, 47.5, 37, 1101, '13:42:48', '2024-11-22'),
('cgyRiYDtO', 'esp8266_01', 30.2, 60, 80, 2000, 10, '13:04:43', '2024-11-22'),
('COkL804YS', 'esp8266_01', 30, 60, 80, 2000, 10, '13:03:50', '2024-11-22'),
('cOsVv38gD', 'esp8266_01', 26.7, 80.6, 56, 12, 34.5, '17:40:17', '2024-11-21'),
('dv7AuVZhI', 'esp8266_01', 29, 46, 47, 35, 1100, '13:27:37', '2024-11-22'),
('EKqyC68WQ', 'esp8266_01', 1.1, 1, 1, 1, 1, '12:57:27', '2024-11-22'),
('eZVrbXdot', 'esp8266_01', 29, 46.3, 47.5, 37, 1101, '13:43:36', '2024-11-22'),
('Fz46uZURD', 'esp8266_01', 45, 17, 20, 12321, 45, '18:21:47', '2024-11-21'),
('GJfFnPvNK', 'esp8266_01', 45, 34, 37, 12456, 45, '12:45:00', '2024-11-22'),
('HnrXsIMfC', 'esp8266_01', 29, 48, 28, 4000, 2000, '13:08:08', '2024-11-22'),
('InLDwk4uj', 'esp8266_01', 29, 46, 47, 35, 1100, '13:31:35', '2024-11-22'),
('JQWGYrhbD', 'esp8266_01', 35, 100, 100, 2001, 123, '22:43:10', '2024-11-23'),
('l4uLJmUOX', 'esp8266_01', 30, 81, 37, 12332, 45, '04:52:13', '2024-11-22'),
('LKDWGfjob', 'esp8266_01', 29, 46, 47.5, 35, 1101, '13:41:16', '2024-11-22'),
('pc5iUVb1w', 'esp8266_01', 35, 48, 28, 4000, 2000, '13:09:04', '2024-11-22'),
('phBceQCyr', 'esp8266_01', 30, 80, 37, 12332, 45, '04:52:02', '2024-11-22'),
('qA2HrZ1Ok', 'esp8266_01', 29, 46.3, 47.5, 37, 1101, '13:41:29', '2024-11-22'),
('QT8kydDRh', 'esp8266_01', 1, 1, 1, 1, 1, '12:57:10', '2024-11-22'),
('TEFgym8nZ', 'esp8266_01', 26.7, 80.6, 56, 12, 34.5, '17:35:25', '2024-11-21'),
('tjrmq6Zs3', 'esp8266_01', 1.1, 1, 1.8, 1, 1, '12:57:39', '2024-11-22'),
('tuZybpPMR', 'esp8266_01', 26.8, 80.5, 56, 12, 34.5, '17:42:22', '2024-11-21'),
('ucsWeYfgo', 'esp8266_01', 26.7, 80.6, 56, 12, 34.5, '17:41:55', '2024-11-21'),
('VF3jofUmw', 'esp8266_01', 29, 46, 47, 35, 1100, '13:39:40', '2024-11-22'),
('VYInjlK5O', 'esp8266_01', 1.1, 1.3, 1.8, 1, 1, '12:57:43', '2024-11-22'),
('wamvy34I8', 'esp8266_01', 29, 46.3, 47.5, 37, 1101, '13:42:24', '2024-11-22'),
('X0YuLwvzb', 'esp8266_01', 30, 81, 37, 12456, 45, '10:54:42', '2024-11-22'),
('XeRr20Zj4', 'esp8266_01', 29, 46, 47, 35, 1100, '13:37:20', '2024-11-22'),
('Xkn78Yf5A', 'esp8266_01', 29, 46, 47.5, 37, 1101, '13:41:24', '2024-11-22'),
('Xt49iqj2p', 'esp8266_01', 29, 46, 47, 35, 1100, '13:38:52', '2024-11-22'),
('Y8jI9QSad', 'esp8266_01', 35, 100, 100, 2001, 123, '22:41:25', '2024-11-23'),
('yGdMntkWR', 'esp8266_01', 30, 81, 37, 12332, 45, '10:53:49', '2024-11-22');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `sensors`
--

CREATE TABLE `sensors` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `model` varchar(255) NOT NULL,
  `measurement_range` varchar(255) DEFAULT NULL,
  `accuracy` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `node` varchar(50) DEFAULT NULL,
  `link` varchar(100) DEFAULT NULL,
  `state` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Đang đổ dữ liệu cho bảng `sensors`
--

INSERT INTO `sensors` (`id`, `name`, `model`, `measurement_range`, `accuracy`, `description`, `node`, `link`, `state`) VALUES
(1, 'Cảm biến nhiệt độ và độ ẩm không khí', 'DHT11', '0°C - 50°C', '±2°C', 'Cảm biến đo nhiệt độ và độ ẩm cơ bản', 'Node 1', 'https://nshopvn.com/product/module-cam-bien-do-am-nhiet-do-dht11/', 'online'),
(2, 'Cảm biến độ ẩm đất\r\n', 'Soil Moisture Sensor', '0% - 100%', '±5%', 'Cảm biến đo độ ẩm đất', 'Node 1', 'https://nshopvn.com/product/cam-bien-do-am-dat/', 'online'),
(3, 'Cảm biến ánh sáng', 'LDR', '0 - 1000 Lux', '±3%', 'Cảm biến đo cường độ ánh sáng môi trường', 'Node 2', 'https://nshopvn.com/category/cam-bien/cam-bien-anh-sang/', 'online'),
(4, 'Cảm biến đo nồng độ Co2', 'MG-511', '0-1200 ppm', '~2-3%', 'Cảm biến đo cường độ Co2 của môi trường', 'Node 2', 'https://nshopvn.com/product/cam-bien-mua/', 'online'),
(5, 'Cảm biến mưa', 'LM-358', '0-1', '~1-2%', 'Cảm biến mưa của môi trường', 'Node 2', 'https://nshopvn.com/product/cam-bien-mua/', 'online');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `updatesdata`
--

CREATE TABLE `updatesdata` (
  `temperature` float DEFAULT NULL,
  `humidity` float DEFAULT NULL,
  `soil` float DEFAULT NULL,
  `light` float DEFAULT NULL,
  `concentration` float DEFAULT NULL,
  `time` time DEFAULT NULL,
  `date` date DEFAULT NULL,
  `id` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Đang đổ dữ liệu cho bảng `updatesdata`
--

INSERT INTO `updatesdata` (`temperature`, `humidity`, `soil`, `light`, `concentration`, `time`, `date`, `id`) VALUES
(35, 100, 100, 2001, 123, '22:43:10', '2024-11-23', 'esp8266_01');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('user','admin') DEFAULT 'user'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Đang đổ dữ liệu cho bảng `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password`, `role`) VALUES
(1, 'admin', '', '$2y$10$TVzfRdBHMXhER76ipkLKL.gjeRL6W/CodXSLENwdQ98pcgkLkAReu', 'admin'),
(5, 'manh', 'manh@gmail.com', '$2y$10$O.l5zUkBpFyLVjGRZ5rvxOYEu21EwYbdvWwNG1HXv8N5p6FDibiMG', 'user'),
(7, 'trang', 'trang@gmail.com', '$2y$10$bHorvkscMjLAuab2fIu9We/fOcE2j/KHYmOcon7gfeATpdD265XNG', 'user'),
(8, 'hienbaby', 'hienbaby@gmail.com', '$2y$10$5RsLe5fJgXe.TTSrOWaYRerKpqbx4x/mLEuqIGVN0lNrNucEolOKK', 'user'),
(9, 'chipchip7a2@gmail.com', 'gachienmam88', '$2y$10$TVzfRdBHMXhER76ipkLKL.gjeRL6W/CodXSLENwdQ98pcgkLkAReu', 'admin');

--
-- Chỉ mục cho các bảng đã đổ
--

--
-- Chỉ mục cho bảng `devices`
--
ALTER TABLE `devices`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `device_status`
--
ALTER TABLE `device_status`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `notification`
--
ALTER TABLE `notification`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `recorddata`
--
ALTER TABLE `recorddata`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `sensors`
--
ALTER TABLE `sensors`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT cho các bảng đã đổ
--

--
-- AUTO_INCREMENT cho bảng `devices`
--
ALTER TABLE `devices`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT cho bảng `device_status`
--
ALTER TABLE `device_status`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `notification`
--
ALTER TABLE `notification`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT cho bảng `sensors`
--
ALTER TABLE `sensors`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT cho bảng `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
