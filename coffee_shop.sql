-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 07, 2026 at 06:18 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `coffee_shop`
--

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`, `created_at`) VALUES
(1, 'Cà Phê', '2026-04-03 02:50:10'),
(2, 'Trà Trái Cây', '2026-04-03 02:50:10'),
(3, 'Bánh Ngọt', '2026-04-03 02:50:10');

-- --------------------------------------------------------

--
-- Table structure for table `inventory_items`
--

CREATE TABLE `inventory_items` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `unit` varchar(50) NOT NULL,
  `min_stock` int(11) DEFAULT 0 COMMENT 'Mức cảnh báo sắp hết hàng'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `inventory_items`
--

INSERT INTO `inventory_items` (`id`, `name`, `unit`, `min_stock`) VALUES
(1, 'Cà phê hạt pha máy (Arabica/Robusta)', 'Kg', 5),
(2, 'Sữa tươi thanh trùng Đà Lạt Milk', 'Hộp 1L', 15),
(3, 'Đường cát trắng Biên Hòa', 'Kg', 10),
(4, 'Syrup Caramel Monin', 'Chai', 2),
(5, 'Ly nhựa dập màng size M', 'Cái', 500);

-- --------------------------------------------------------

--
-- Table structure for table `inventory_transactions`
--

CREATE TABLE `inventory_transactions` (
  `id` int(11) NOT NULL,
  `item_id` int(11) NOT NULL,
  `type` enum('in','out') NOT NULL,
  `quantity` int(11) NOT NULL,
  `note` text DEFAULT NULL,
  `staff_id` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `inventory_transactions`
--

INSERT INTO `inventory_transactions` (`id`, `item_id`, `type`, `quantity`, `note`, `staff_id`, `created_at`) VALUES
(1, 1, 'in', 20, 'Nhập lô hàng đầu tháng', 1, '2026-04-02 04:15:20'),
(2, 1, 'out', 5, 'Xuất quầy pha chế', 1, '2026-04-05 04:15:20'),
(3, 2, 'in', 40, 'Nhập từ nhà cung cấp', 1, '2026-04-03 04:15:20'),
(4, 2, 'out', 30, 'Xuất sử dụng trong tuần', 1, '2026-04-07 04:15:20'),
(5, 3, 'in', 10, 'Nhập tạp hóa', 1, '2026-03-28 04:15:20'),
(6, 3, 'out', 10, 'Xuất cho bếp làm syrup', 1, '2026-04-07 04:15:20'),
(7, 4, 'in', 6, 'Nhập hàng', 1, '2026-04-06 04:15:20'),
(8, 5, 'in', 2000, 'Nhập kho bao bì', 1, '2026-03-23 04:15:20'),
(9, 5, 'out', 450, 'Xuất quầy thu ngân', 1, '2026-04-06 04:15:20');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `table_id` int(11) NOT NULL,
  `staff_id` int(11) DEFAULT NULL,
  `total_amount` decimal(12,2) DEFAULT 0.00,
  `status` enum('pending','processing','completed','cancelled') DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `table_id`, `staff_id`, `total_amount`, `status`, `created_at`) VALUES
(9, 1, 2, 175000.00, 'completed', '2026-04-04 15:58:55'),
(12, 1, 2, 175000.00, 'completed', '2026-04-04 16:00:06'),
(15, 1, 2, 175000.00, 'completed', '2026-04-04 16:00:56'),
(18, 1, 1, 175000.00, 'completed', '2026-04-05 05:26:57'),
(21, 1, 2, 175000.00, 'completed', '2026-04-05 07:06:27'),
(30, 1, 1, 410000.00, 'completed', '2026-04-05 08:10:40'),
(33, 1, 1, 105000.00, 'completed', '2026-04-05 08:11:53'),
(34, 2, 2, 15000.00, 'completed', '2026-04-05 11:36:31');

-- --------------------------------------------------------

--
-- Table structure for table `order_details`
--

CREATE TABLE `order_details` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL DEFAULT 1,
  `unit_price` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `order_details`
--

INSERT INTO `order_details` (`id`, `order_id`, `product_id`, `quantity`, `unit_price`) VALUES
(26, 9, 1, 1, 25000.00),
(27, 9, 2, 1, 30000.00),
(28, 9, 3, 1, 45000.00),
(29, 9, 4, 1, 40000.00),
(30, 9, 5, 1, 35000.00),
(41, 12, 1, 1, 25000.00),
(42, 12, 2, 1, 30000.00),
(43, 12, 3, 1, 45000.00),
(44, 12, 4, 1, 40000.00),
(45, 12, 5, 1, 35000.00),
(56, 15, 1, 1, 25000.00),
(57, 15, 2, 1, 30000.00),
(58, 15, 3, 1, 45000.00),
(59, 15, 4, 1, 40000.00),
(60, 15, 5, 1, 35000.00),
(71, 18, 1, 1, 25000.00),
(72, 18, 2, 1, 30000.00),
(73, 18, 3, 1, 45000.00),
(74, 18, 4, 1, 40000.00),
(75, 18, 5, 1, 35000.00),
(86, 21, 1, 1, 25000.00),
(87, 21, 2, 1, 30000.00),
(88, 21, 3, 1, 45000.00),
(89, 21, 4, 1, 40000.00),
(90, 21, 5, 1, 35000.00),
(110, 30, 1, 2, 25000.00),
(111, 30, 2, 4, 30000.00),
(112, 30, 3, 1, 45000.00),
(113, 30, 4, 1, 40000.00),
(114, 30, 5, 4, 35000.00),
(115, 30, 6, 1, 15000.00),
(121, 33, 2, 1, 30000.00),
(122, 33, 5, 1, 35000.00),
(123, 33, 4, 1, 40000.00),
(124, 34, 6, 1, 15000.00);

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  `name` varchar(150) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `description` text DEFAULT NULL,
  `image` varchar(255) DEFAULT 'default.jpg',
  `status` enum('active','inactive') DEFAULT 'active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `category_id`, `name`, `price`, `description`, `image`, `status`, `created_at`) VALUES
(1, 1, 'Cà Phê Đen Đá', 25000.00, NULL, 'product_1775315528158.jpg', 'active', '2026-04-03 02:50:10'),
(2, 1, 'Bạc Xỉu', 30000.00, NULL, 'product_1775315521785.jpg', 'active', '2026-04-03 02:50:10'),
(3, 2, 'Trà Đào Cam Sả', 45000.00, NULL, 'product_1775315540408.jpg', 'active', '2026-04-03 02:50:10'),
(4, 2, 'Trà Vải', 40000.00, NULL, 'product_1775315548467.jpg', 'active', '2026-04-03 02:50:10'),
(5, 3, 'Tiramisu', 35000.00, NULL, 'product_1775315533426.jpg', 'active', '2026-04-03 02:50:10'),
(6, 2, 'Trà chanh', 15000.00, NULL, 'product_1775373709330.png', 'active', '2026-04-05 07:21:49');

-- --------------------------------------------------------

--
-- Table structure for table `tables`
--

CREATE TABLE `tables` (
  `id` int(11) NOT NULL,
  `table_name` varchar(50) NOT NULL,
  `status` enum('available','occupied') DEFAULT 'available',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tables`
--

INSERT INTO `tables` (`id`, `table_name`, `status`, `created_at`) VALUES
(1, 'Bàn 1', '', '2026-04-03 02:50:10'),
(2, 'Bàn 2', '', '2026-04-03 02:50:10'),
(3, 'Bàn 3', 'available', '2026-04-03 02:50:10');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `full_name` varchar(100) NOT NULL,
  `role` enum('staff','admin') DEFAULT 'staff',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` enum('active','inactive') DEFAULT 'active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `full_name`, `role`, `created_at`, `status`) VALUES
(1, 'admin', '123456', 'Quản Trị Viên', 'admin', '2026-04-04 15:05:55', 'active'),
(2, 'staff', '123456', 'Nhân Viên Phục Vụ', 'staff', '2026-04-04 15:06:29', 'active');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `inventory_items`
--
ALTER TABLE `inventory_items`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `inventory_transactions`
--
ALTER TABLE `inventory_transactions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `item_id` (`item_id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `table_id` (`table_id`),
  ADD KEY `staff_id` (`staff_id`);

--
-- Indexes for table `order_details`
--
ALTER TABLE `order_details`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `category_id` (`category_id`);

--
-- Indexes for table `tables`
--
ALTER TABLE `tables`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `inventory_items`
--
ALTER TABLE `inventory_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `inventory_transactions`
--
ALTER TABLE `inventory_transactions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT for table `order_details`
--
ALTER TABLE `order_details`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=125;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `tables`
--
ALTER TABLE `tables`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `inventory_transactions`
--
ALTER TABLE `inventory_transactions`
  ADD CONSTRAINT `inventory_transactions_ibfk_1` FOREIGN KEY (`item_id`) REFERENCES `inventory_items` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`table_id`) REFERENCES `tables` (`id`),
  ADD CONSTRAINT `orders_ibfk_2` FOREIGN KEY (`staff_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `order_details`
--
ALTER TABLE `order_details`
  ADD CONSTRAINT `order_details_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `order_details_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`);

--
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
