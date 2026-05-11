-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: May 11, 2026 at 06:54 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

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
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `status` enum('active','inactive') NOT NULL DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`, `description`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Cà Phê', NULL, 'active', '2026-04-26 05:56:29', '2026-04-26 05:56:29'),
(2, 'Bánh Ngọt', NULL, 'active', '2026-04-26 05:56:29', '2026-04-26 05:56:29'),
(3, 'Trà Trái Cây', NULL, 'active', '2026-04-26 05:56:29', '2026-04-26 05:56:29');

-- --------------------------------------------------------

--
-- Table structure for table `coffee_tables`
--

CREATE TABLE `coffee_tables` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `area` varchar(255) NOT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'empty',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `coffee_tables`
--

INSERT INTO `coffee_tables` (`id`, `name`, `area`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Bàn 01', 'Tầng trệt', 'available', '2026-04-26 05:56:29', '2026-05-09 00:46:07'),
(2, 'Bàn 02', 'Tầng trệt', 'available', '2026-04-26 05:56:29', '2026-04-27 04:36:00'),
(3, 'Bàn 03', 'Tầng trệt', 'empty', '2026-04-26 05:56:29', '2026-04-26 05:56:29'),
(4, 'Bàn 04', 'Tầng trệt', 'empty', '2026-04-26 05:56:29', '2026-04-26 05:56:29'),
(5, 'Bàn 05', 'Lầu 1', 'empty', '2026-04-26 05:56:29', '2026-04-26 05:56:29'),
(6, 'Bàn 06', 'Lầu 1', 'empty', '2026-04-26 05:56:29', '2026-04-26 05:56:29'),
(7, 'Bàn 07', 'Lầu 1', 'empty', '2026-04-26 05:56:29', '2026-04-26 05:56:29'),
(8, 'Bàn 08', 'Sân vườn', 'empty', '2026-04-26 05:56:29', '2026-04-26 05:56:29'),
(9, 'Bàn 09', 'Sân vườn', 'empty', '2026-04-26 05:56:29', '2026-04-26 05:56:29'),
(10, 'Bàn 10', 'Sân vườn', 'empty', '2026-04-26 05:56:29', '2026-04-26 05:56:29');

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ingredients`
--

CREATE TABLE `ingredients` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `code` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `unit` varchar(255) NOT NULL,
  `stock` int(11) NOT NULL DEFAULT 0,
  `min_stock` int(11) NOT NULL DEFAULT 5,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `ingredients`
--

INSERT INTO `ingredients` (`id`, `code`, `name`, `unit`, `stock`, `min_stock`, `created_at`, `updated_at`) VALUES
(1, 'NL-001', 'Cà phê hạt pha máy (Arabica/Robusta)', 'Kg', 15, 5, '2026-04-26 05:56:29', '2026-04-26 05:56:29'),
(2, 'NL-003', 'Đường cát trắng Biên Hòa', 'Kg', 50, 10, '2026-04-26 05:56:29', '2026-04-26 05:56:29'),
(3, 'NL-005', 'Ly nhựa dập màng size M', 'Cái', 1550, 100, '2026-04-26 05:56:29', '2026-04-26 05:56:29'),
(4, 'NL-002', 'Sữa tươi thanh trùng Đà Lạt Milk', 'Hộp 1L', 22, 5, '2026-04-26 05:56:29', '2026-04-26 05:56:29'),
(5, 'NL-004', 'Syrup Caramel Monin', 'Chai', 6, 2, '2026-04-26 05:56:29', '2026-04-26 05:56:29');

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) UNSIGNED NOT NULL,
  `reserved_at` int(10) UNSIGNED DEFAULT NULL,
  `available_at` int(10) UNSIGNED NOT NULL,
  `created_at` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext DEFAULT NULL,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2026_04_14_180534_create_categories_table', 1),
(5, '2026_04_14_180612_create_products_table', 1),
(6, '2026_04_14_180638_create_tables_table', 1),
(7, '2026_04_15_182452_create_settings_table', 1),
(8, '2026_04_16_031011_create_ingredients_table', 1),
(9, '2026_04_16_084156_create_coffee_tables_table', 1),
(10, '2026_04_16_173522_add_description_and_status_to_categories_table', 1),
(11, '2026_04_17_110000_create_orders_table', 1),
(12, '2026_04_17_113416_create_order_details_table', 1),
(13, '2026_04_21_000000_add_status_to_users_table', 1),
(14, '2026_04_22_163154_add_status_to_tables_table', 1),
(15, '2026_04_24_155218_create_shop_settings_table', 1),
(16, '2026_04_24_160314_add_is_best_seller_to_products_table', 1),
(17, '2026_04_24_162515_add_more_fields_to_shop_settings_table', 1),
(18, '2026_04_26_112808_create_vouchers_table', 1),
(19, '2026_05_01_130436_add_voucher_fields_to_orders_table', 2),
(20, '2026_05_01_134655_change_table_id_to_nullable_in_orders_table', 3),
(21, '2026_05_06_143646_add_oauth_fields_to_users_table', 4);

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `table_id` bigint(20) UNSIGNED DEFAULT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `total_amount` decimal(15,2) NOT NULL DEFAULT 0.00,
  `status` varchar(255) NOT NULL DEFAULT 'pending',
  `voucher_code` varchar(255) DEFAULT NULL,
  `discount_amount` decimal(15,2) DEFAULT 0.00,
  `final_amount` decimal(15,2) DEFAULT 0.00,
  `payment_status` varchar(255) NOT NULL DEFAULT 'unpaid',
  `payment_method` varchar(255) DEFAULT NULL,
  `note` text DEFAULT NULL,
  `order_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `table_id`, `user_id`, `total_amount`, `status`, `voucher_code`, `discount_amount`, `final_amount`, `payment_status`, `payment_method`, `note`, `order_date`, `created_at`, `updated_at`) VALUES
(1, 1, 2, 90000.00, 'completed', 'KHAITRUONG50', 45000.00, 45000.00, 'paid', 'cash', 'Khách đặt qua QR', '2026-05-01 06:07:58', '2026-05-01 04:34:13', '2026-05-01 06:07:58'),
(2, 1, 2, 90000.00, 'completed', 'KHAITRUONG50', 45000.00, 45000.00, 'paid', 'cash', NULL, '2026-05-01 06:08:33', '2026-05-01 06:08:33', '2026-05-01 06:08:33'),
(3, NULL, 2, 90000.00, 'completed', 'KHAITRUONG50', 45000.00, 45000.00, 'paid', 'cash', 'Khách mang về', '2026-05-01 06:53:03', '2026-05-01 06:53:03', '2026-05-01 06:53:03'),
(4, 1, 2, 90000.00, 'completed', 'KHAITRUONG50', 45000.00, 45000.00, 'paid', 'cash', 'cà phê đen đá nhưng mà không cà phê nhen', '2026-05-01 07:46:19', '2026-05-01 07:36:02', '2026-05-01 07:46:19'),
(5, 1, 2, 25000.00, 'completed', 'KHAITRUONG50', 12500.00, 12500.00, 'paid', 'cash', 'cho em ly ca phe nhieu da nhen :)', '2026-05-01 10:41:05', '2026-05-01 10:39:21', '2026-05-01 10:41:05'),
(6, 1, 2, 25000.00, 'completed', 'KHAITRUONG50', 12500.00, 12500.00, 'paid', 'cash', 'cho em ca phe khong da nhung them sua nhe ahiihi', '2026-05-01 13:13:45', '2026-05-01 13:11:44', '2026-05-01 13:13:45'),
(7, NULL, 2, 25000.00, 'completed', 'KHAITRUONG50', 12500.00, 12500.00, 'paid', 'cash', '[MANG VỀ] ', '2026-05-01 13:16:00', '2026-05-01 13:16:00', '2026-05-01 13:16:00'),
(8, 1, 2, 30000.00, 'completed', 'KHAITRUONG50', 15000.00, 15000.00, 'paid', 'cash', 'bac xiu ko sua nhe', '2026-05-01 14:01:08', '2026-05-01 14:00:20', '2026-05-01 14:01:08'),
(9, 1, 2, 30000.00, 'completed', 'KHAITRUONG50', 15000.00, 15000.00, 'paid', 'cash', 'bac xiu ko sua', '2026-05-01 14:02:12', '2026-05-01 14:01:45', '2026-05-01 14:02:12'),
(10, NULL, 2, 90000.00, 'completed', 'KHAITRUONG50', 45000.00, 45000.00, 'paid', 'cash', '[MANG VỀ] ', '2026-05-05 00:12:03', '2026-05-05 00:12:03', '2026-05-05 00:12:03'),
(11, 1, 2, 55000.00, 'completed', 'KHAITRUONG50', 27500.00, 27500.00, 'paid', 'cash', 'ca phe da ko da, bac xiu khong sua nhe', '2026-05-09 00:46:06', '2026-05-09 00:29:24', '2026-05-09 00:46:07');

-- --------------------------------------------------------

--
-- Table structure for table `order_details`
--

CREATE TABLE `order_details` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `order_id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `quantity` int(11) NOT NULL,
  `price` decimal(15,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `order_details`
--

INSERT INTO `order_details` (`id`, `order_id`, `product_id`, `quantity`, `price`, `created_at`, `updated_at`) VALUES
(4, 1, 1, 1, 30000.00, '2026-05-01 06:07:58', '2026-05-01 06:07:58'),
(5, 1, 2, 1, 25000.00, '2026-05-01 06:07:58', '2026-05-01 06:07:58'),
(6, 1, 3, 1, 35000.00, '2026-05-01 06:07:58', '2026-05-01 06:07:58'),
(7, 2, 1, 1, 30000.00, '2026-05-01 06:08:33', '2026-05-01 06:08:33'),
(8, 2, 2, 1, 25000.00, '2026-05-01 06:08:33', '2026-05-01 06:08:33'),
(9, 2, 3, 1, 35000.00, '2026-05-01 06:08:33', '2026-05-01 06:08:33'),
(10, 3, 1, 1, 30000.00, '2026-05-01 06:53:03', '2026-05-01 06:53:03'),
(11, 3, 2, 1, 25000.00, '2026-05-01 06:53:03', '2026-05-01 06:53:03'),
(12, 3, 3, 1, 35000.00, '2026-05-01 06:53:03', '2026-05-01 06:53:03'),
(16, 4, 1, 1, 30000.00, '2026-05-01 07:46:19', '2026-05-01 07:46:19'),
(17, 4, 2, 1, 25000.00, '2026-05-01 07:46:19', '2026-05-01 07:46:19'),
(18, 4, 3, 1, 35000.00, '2026-05-01 07:46:19', '2026-05-01 07:46:19'),
(20, 5, 2, 1, 25000.00, '2026-05-01 10:41:06', '2026-05-01 10:41:06'),
(22, 6, 2, 1, 25000.00, '2026-05-01 13:13:45', '2026-05-01 13:13:45'),
(23, 7, 2, 1, 25000.00, '2026-05-01 13:16:00', '2026-05-01 13:16:00'),
(25, 8, 1, 1, 30000.00, '2026-05-01 14:01:08', '2026-05-01 14:01:08'),
(27, 9, 1, 1, 30000.00, '2026-05-01 14:02:12', '2026-05-01 14:02:12'),
(28, 10, 1, 1, 30000.00, '2026-05-05 00:12:03', '2026-05-05 00:12:03'),
(29, 10, 2, 1, 25000.00, '2026-05-05 00:12:03', '2026-05-05 00:12:03'),
(30, 10, 3, 1, 35000.00, '2026-05-05 00:12:03', '2026-05-05 00:12:03'),
(33, 11, 1, 1, 30000.00, '2026-05-09 00:46:07', '2026-05-09 00:46:07'),
(34, 11, 2, 1, 25000.00, '2026-05-09 00:46:07', '2026-05-09 00:46:07');

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `category_id` bigint(20) UNSIGNED NOT NULL,
  `price` int(11) NOT NULL,
  `is_best_seller` tinyint(1) NOT NULL DEFAULT 0,
  `image` varchar(255) NOT NULL DEFAULT 'default.jpg',
  `status` enum('active','inactive') NOT NULL DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `category_id`, `price`, `is_best_seller`, `image`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Bạc Xỉu', 1, 30000, 0, '1777603019_1776262949_ae3484c74b82ef668d99f42cb3314026.jpg', 'active', '2026-04-26 05:56:29', '2026-05-01 02:36:59'),
(2, 'Cà Phê Đen Đá', 1, 25000, 0, '1777603012_1776262934_images.jpg', 'active', '2026-04-26 05:56:29', '2026-05-01 02:36:52'),
(3, 'Tiramisu', 2, 35000, 0, '1777603007_1776262922_images (1).jpg', 'active', '2026-04-26 05:56:29', '2026-05-01 02:36:47'),
(4, 'Trà chanh', 3, 15000, 0, '1777602999_1776262911_tra-chanh-gung-sa-41-5gl.png', 'active', '2026-04-26 05:56:29', '2026-05-01 02:36:39'),
(5, 'Trà Đào Cam Sả', 3, 45000, 0, '1777602992_1776262880_images (2).jpg', 'active', '2026-04-26 05:56:29', '2026-05-01 02:36:32'),
(6, 'Trà Vải', 3, 40000, 0, '1777602982_1776262865_images (3).jpg', 'active', '2026-04-26 05:56:29', '2026-05-01 02:36:22');

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE `settings` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `shop_name` varchar(255) NOT NULL DEFAULT 'Coffee Shop',
  `phone` varchar(255) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `vat` int(11) NOT NULL DEFAULT 10,
  `footer_text` text DEFAULT NULL,
  `logo` varchar(255) NOT NULL DEFAULT 'logo.png',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `settings`
--

INSERT INTO `settings` (`id`, `shop_name`, `phone`, `address`, `vat`, `footer_text`, `logo`, `created_at`, `updated_at`) VALUES
(1, 'HUTECH COFFEE', '081238XXXX', 'Trần Duy Hưng', 10, 'Chào tạm biệt và hẹn gặp lại quý khách!!', 'logo_1777211068.png', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `shop_settings`
--

CREATE TABLE `shop_settings` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `shop_name` varchar(255) NOT NULL DEFAULT 'HUTECH Coffee',
  `address` varchar(255) DEFAULT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `working_hours` varchar(255) DEFAULT '07:00 - 22:00',
  `logo` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `shop_settings`
--

INSERT INTO `shop_settings` (`id`, `shop_name`, `address`, `phone`, `email`, `working_hours`, `logo`, `created_at`, `updated_at`) VALUES
(1, 'HUTECH Coffee', '475A Điện Biên Phủ, P.25, Bình Thạnh, TP.HCM', '0123 456 789', NULL, '07:00 - 22:00', NULL, '2026-04-26 05:55:14', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `tables`
--

CREATE TABLE `tables` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `table_name` varchar(50) NOT NULL,
  `status` enum('available','occupied') NOT NULL DEFAULT 'available',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `username` varchar(255) NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `provider` varchar(255) DEFAULT NULL,
  `provider_id` varchar(255) DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `position` varchar(255) DEFAULT NULL,
  `avatar` varchar(255) DEFAULT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'active',
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `username`, `email`, `provider`, `provider_id`, `email_verified_at`, `password`, `position`, `avatar`, `status`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Trần Phúc Thịnh', 'admin', 'admin@hutechcoffee.local', NULL, NULL, NULL, '$2y$12$wg4S53TDsBcsKHhyC8o1yev1ud1Y2CNd64kO2uB5sfMBQvJtn5i2W', 'Admin', NULL, 'active', NULL, '2026-04-26 05:58:08', '2026-04-26 05:58:08'),
(2, 'Bộ PC', 'staff', 'staff@hutechcoffee.local', NULL, NULL, NULL, '$2y$12$JSrxsRZ27ls6EcoI2bb.KekqUoD3VhNNEGGhSaXnFrWlA6DlqKgfC', 'Staff', 'nv_2_1777650813.png', 'active', NULL, '2026-04-26 06:39:01', '2026-05-01 15:53:33'),
(3, 'Anh Dũng Senpai', 'staff02', 'staff02@hutechcoffee.local', NULL, NULL, NULL, '$2y$12$MlQV2hxjXdiLQppPcLN5Fud.Aw/z944vbS4gXe4uV2g6o5gHc6pG6', 'staff', 'nv_1777652893_402.jpg', 'active', NULL, '2026-05-01 16:28:13', '2026-05-01 16:28:13');

-- --------------------------------------------------------

--
-- Table structure for table `vouchers`
--

CREATE TABLE `vouchers` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `code` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `type` enum('percentage','fixed') NOT NULL,
  `discount_value` decimal(10,2) NOT NULL,
  `min_order_value` decimal(10,2) NOT NULL DEFAULT 0.00,
  `limit_uses` int(11) DEFAULT NULL,
  `used_count` int(11) NOT NULL DEFAULT 0,
  `start_date` datetime DEFAULT NULL,
  `end_date` datetime DEFAULT NULL,
  `status` enum('active','inactive') NOT NULL DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `vouchers`
--

INSERT INTO `vouchers` (`id`, `code`, `name`, `type`, `discount_value`, `min_order_value`, `limit_uses`, `used_count`, `start_date`, `end_date`, `status`, `created_at`, `updated_at`) VALUES
(2, 'KHAITRUONG50', 'Mừng Khai Trương HUTECH Coffee', 'percentage', 50.00, 0.00, 100, 11, '2026-04-26 14:03:53', '2026-05-26 14:03:53', 'active', '2026-04-26 07:03:53', '2026-05-09 00:46:07'),
(3, 'SINHVIENHUTECH', 'Ưu đãi đặc quyền Sinh Viên', 'fixed', 20000.00, 50000.00, 500, 0, '2026-04-26 14:03:53', '2027-04-26 14:03:53', 'active', '2026-04-26 07:03:53', '2026-04-26 07:03:53');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_expiration_index` (`expiration`);

--
-- Indexes for table `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_locks_expiration_index` (`expiration`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `coffee_tables`
--
ALTER TABLE `coffee_tables`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `ingredients`
--
ALTER TABLE `ingredients`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `ingredients_code_unique` (`code`);

--
-- Indexes for table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Indexes for table `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `orders_table_id_foreign` (`table_id`),
  ADD KEY `orders_user_id_foreign` (`user_id`);

--
-- Indexes for table `order_details`
--
ALTER TABLE `order_details`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_details_order_id_foreign` (`order_id`),
  ADD KEY `order_details_product_id_foreign` (`product_id`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `products_category_id_foreign` (`category_id`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `shop_settings`
--
ALTER TABLE `shop_settings`
  ADD PRIMARY KEY (`id`);

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
  ADD UNIQUE KEY `users_username_unique` (`username`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- Indexes for table `vouchers`
--
ALTER TABLE `vouchers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `vouchers_code_unique` (`code`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `coffee_tables`
--
ALTER TABLE `coffee_tables`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `ingredients`
--
ALTER TABLE `ingredients`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `order_details`
--
ALTER TABLE `order_details`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `settings`
--
ALTER TABLE `settings`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `shop_settings`
--
ALTER TABLE `shop_settings`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `tables`
--
ALTER TABLE `tables`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `vouchers`
--
ALTER TABLE `vouchers`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_table_id_foreign` FOREIGN KEY (`table_id`) REFERENCES `coffee_tables` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `orders_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `order_details`
--
ALTER TABLE `order_details`
  ADD CONSTRAINT `order_details_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `order_details_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
