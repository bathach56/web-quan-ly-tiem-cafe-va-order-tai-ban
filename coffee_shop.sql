-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 24, 2026 at 11:29 AM
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
(1, 'Cà Phê', NULL, 'active', '2026-04-19 05:32:44', '2026-04-19 05:32:44'),
(2, 'Bánh Ngọt', NULL, 'active', '2026-04-19 05:32:44', '2026-04-19 05:32:44'),
(3, 'Trà Trái Cây', NULL, 'active', '2026-04-19 05:32:44', '2026-04-19 05:32:44');

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
(1, 'Bàn 01', 'Tầng trệt', 'available', '2026-04-19 05:32:44', '2026-04-23 07:35:04'),
(2, 'Bàn 02', 'Tầng trệt', 'empty', '2026-04-19 05:32:44', '2026-04-19 05:32:44'),
(3, 'Bàn 03', 'Tầng trệt', 'empty', '2026-04-19 05:32:44', '2026-04-19 05:32:44'),
(4, 'Bàn 04', 'Tầng trệt', 'empty', '2026-04-19 05:32:44', '2026-04-19 05:32:44'),
(5, 'Bàn 05', 'Lầu 1', 'empty', '2026-04-19 05:32:44', '2026-04-19 05:32:44'),
(6, 'Bàn 06', 'Lầu 1', 'empty', '2026-04-19 05:32:44', '2026-04-19 05:32:44'),
(7, 'Bàn 07', 'Lầu 1', 'empty', '2026-04-19 05:32:44', '2026-04-19 05:32:44'),
(8, 'Bàn 08', 'Sân vườn', 'empty', '2026-04-19 05:32:44', '2026-04-19 05:32:44'),
(9, 'Bàn 09', 'Sân vườn', 'empty', '2026-04-19 05:32:44', '2026-04-19 05:32:44'),
(10, 'Bàn 10', 'Sân vườn', 'empty', '2026-04-19 05:32:44', '2026-04-19 05:32:44');

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
(1, 'NL-001', 'Cà phê hạt pha máy (Arabica/Robusta)', 'Kg', 15, 5, '2026-04-19 05:32:44', '2026-04-19 05:32:44'),
(2, 'NL-003', 'Đường cát trắng Biên Hòa', 'Kg', 50, 10, '2026-04-19 05:32:44', '2026-04-19 05:32:44'),
(3, 'NL-005', 'Ly nhựa dập màng size M', 'Cái', 1550, 100, '2026-04-19 05:32:44', '2026-04-19 05:32:44'),
(4, 'NL-002', 'Sữa tươi thanh trùng Đà Lạt Milk', 'Hộp 1L', 22, 5, '2026-04-19 05:32:44', '2026-04-19 05:32:44'),
(5, 'NL-004', 'Syrup Caramel Monin', 'Chai', 6, 2, '2026-04-19 05:32:44', '2026-04-19 05:32:44');

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
(13, '2026_04_21_000000_add_status_to_users_table', 2);

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `table_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `total_amount` decimal(15,2) NOT NULL DEFAULT 0.00,
  `status` varchar(255) NOT NULL DEFAULT 'pending',
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

INSERT INTO `orders` (`id`, `table_id`, `user_id`, `total_amount`, `status`, `payment_status`, `payment_method`, `note`, `order_date`, `created_at`, `updated_at`) VALUES
(1, 1, NULL, 55000.00, 'completed', 'paid', 'cash', 'Khách đặt món qua QR tại bàn', '2026-04-21 12:40:55', '2026-04-21 05:40:55', '2026-04-21 05:41:59'),
(2, 1, 6, 130000.00, 'completed', 'paid', 'cash', 'Khách đặt món qua QR tại bàn', '2026-04-22 04:28:16', '2026-04-21 21:28:16', '2026-04-21 22:11:11'),
(3, 1, 6, 175000.00, 'completed', 'paid', 'cash', 'Khách đặt món qua QR tại bàn', '2026-04-22 04:30:57', '2026-04-21 21:30:57', '2026-04-21 22:21:50'),
(4, 1, 6, 225000.00, 'completed', 'paid', 'cash', 'Khách đặt món qua QR tại bàn', '2026-04-22 04:53:07', '2026-04-21 21:53:07', '2026-04-22 06:37:05'),
(5, 1, 6, 30000.00, 'completed', 'paid', 'cash', 'Khách đặt món qua QR tại bàn', '2026-04-22 06:42:36', '2026-04-22 06:42:36', '2026-04-22 06:42:55'),
(6, 1, 6, 115000.00, 'completed', 'paid', 'cash', 'Khách gọi món qua QR', '2026-04-22 09:42:39', '2026-04-22 09:42:39', '2026-04-22 10:18:19'),
(7, 1, 6, 90000.00, 'completed', 'paid', 'cash', 'Khách gọi món qua QR', '2026-04-22 19:08:47', '2026-04-22 19:08:47', '2026-04-22 19:09:50'),
(8, 1, 6, 95000.00, 'completed', 'paid', 'cash', 'Khách gọi món qua QR', '2026-04-23 06:37:35', '2026-04-23 06:37:35', '2026-04-23 07:26:18'),
(9, 1, 6, 55000.00, 'completed', 'paid', 'cash', 'Khách gọi món qua QR', '2026-04-23 07:34:41', '2026-04-23 07:34:41', '2026-04-23 07:35:04');

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
(3, 1, 1, 1, 30000.00, '2026-04-21 05:41:59', '2026-04-21 05:41:59'),
(4, 1, 2, 1, 25000.00, '2026-04-21 05:41:59', '2026-04-21 05:41:59'),
(16, 2, 1, 1, 30000.00, '2026-04-21 22:11:11', '2026-04-21 22:11:11'),
(17, 2, 2, 1, 25000.00, '2026-04-21 22:11:11', '2026-04-21 22:11:11'),
(18, 2, 3, 1, 35000.00, '2026-04-21 22:11:11', '2026-04-21 22:11:11'),
(19, 2, 6, 1, 40000.00, '2026-04-21 22:11:11', '2026-04-21 22:11:11'),
(23, 3, 6, 2, 40000.00, '2026-04-21 22:21:50', '2026-04-21 22:21:50'),
(24, 3, 3, 1, 35000.00, '2026-04-21 22:21:50', '2026-04-21 22:21:50'),
(25, 3, 4, 1, 15000.00, '2026-04-21 22:21:50', '2026-04-21 22:21:50'),
(26, 3, 5, 1, 45000.00, '2026-04-21 22:21:50', '2026-04-21 22:21:50'),
(30, 4, 5, 1, 45000.00, '2026-04-22 06:37:05', '2026-04-22 06:37:05'),
(31, 4, 1, 2, 30000.00, '2026-04-22 06:37:05', '2026-04-22 06:37:05'),
(32, 4, 2, 2, 25000.00, '2026-04-22 06:37:05', '2026-04-22 06:37:05'),
(33, 4, 3, 2, 35000.00, '2026-04-22 06:37:05', '2026-04-22 06:37:05'),
(35, 5, 1, 1, 30000.00, '2026-04-22 06:42:55', '2026-04-22 06:42:55'),
(39, 6, 2, 2, 25000.00, '2026-04-22 10:18:19', '2026-04-22 10:18:19'),
(40, 6, 3, 1, 35000.00, '2026-04-22 10:18:19', '2026-04-22 10:18:19'),
(41, 6, 1, 1, 30000.00, '2026-04-22 10:18:19', '2026-04-22 10:18:19'),
(45, 7, 1, 1, 30000.00, '2026-04-22 19:09:50', '2026-04-22 19:09:50'),
(46, 7, 2, 1, 25000.00, '2026-04-22 19:09:50', '2026-04-22 19:09:50'),
(47, 7, 3, 1, 35000.00, '2026-04-22 19:09:50', '2026-04-22 19:09:50'),
(51, 8, 1, 1, 30000.00, '2026-04-23 07:26:18', '2026-04-23 07:26:18'),
(52, 8, 2, 1, 25000.00, '2026-04-23 07:26:18', '2026-04-23 07:26:18'),
(53, 8, 6, 1, 40000.00, '2026-04-23 07:26:18', '2026-04-23 07:26:18'),
(56, 9, 1, 1, 30000.00, '2026-04-23 07:35:04', '2026-04-23 07:35:04'),
(57, 9, 2, 1, 25000.00, '2026-04-23 07:35:04', '2026-04-23 07:35:04');

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

CREATE TABLE `password_resets` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `password_reset_tokens`
--

INSERT INTO `password_reset_tokens` (`email`, `token`, `created_at`) VALUES
('admin@hutechcoffee.local', 'cTvTJ98WZvFwi4RyTw6slsyPBAiWcEpabMeN1W510llYlhC9Kc6WyXF59sYQnHAa', '2026-04-22 02:56:01');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `category_id` bigint(20) UNSIGNED NOT NULL,
  `price` int(11) NOT NULL,
  `image` varchar(255) NOT NULL DEFAULT 'default.jpg',
  `status` enum('active','inactive') NOT NULL DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `category_id`, `price`, `image`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Bạc Xỉu', 1, 30000, '1776864879_1776414141_ae3484c74b82ef668d99f42cb3314026.jpg', 'active', '2026-04-19 05:32:44', '2026-04-22 06:34:39'),
(2, 'Cà Phê Đen Đá', 1, 25000, '1776864871_1776262934_images.jpg', 'active', '2026-04-19 05:32:44', '2026-04-22 06:34:31'),
(3, 'Tiramisu', 2, 35000, '1776864865_1776262922_images (1).jpg', 'active', '2026-04-19 05:32:44', '2026-04-22 06:34:25'),
(4, 'Trà chanh', 3, 15000, '1776864857_1776262911_tra-chanh-gung-sa-41-5gl.png', 'active', '2026-04-19 05:32:44', '2026-04-22 06:34:17'),
(5, 'Trà Đào Cam Sả', 3, 45000, '1776864848_1776262880_images (2).jpg', 'active', '2026-04-19 05:32:44', '2026-04-22 06:34:08'),
(6, 'Trà Vải', 3, 40000, '1776864839_1776262865_images (3).jpg', 'active', '2026-04-19 05:32:44', '2026-04-22 06:33:59');

-- --------------------------------------------------------

--
-- Table structure for table `product_recipes`
--

CREATE TABLE `product_recipes` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `ingredient_id` bigint(20) UNSIGNED NOT NULL,
  `quantity` double(8,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('KhixYXDVaZtp7K3DFOCjOrN4c82AOntljUu8cRyK', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36 Edg/147.0.0.0', 'YTo1OntzOjY6Il90b2tlbiI7czo0MDoic1JvUDNPdXlzanVmYjlrTFRvbzhhQ25WUWtidjRycGRuVDFlaklCcCI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJuZXciO2E6MDp7fXM6Mzoib2xkIjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzE6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9kYXNoYm9hcmQiO3M6NToicm91dGUiO3M6OToiZGFzaGJvYXJkIjt9czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6MTtzOjk6InVzZXJfbmFtZSI7czoxODoiUGjhuqFtIELDoSB0aOG6oWNoIjt9', 1776603653);

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
(1, 'HUTECH COFFEE', '081238XXXX', 'Trần Duy Hưng', 10, 'Cút mẹ m đi :)))', 'logo_1776780672.png', NULL, NULL);

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

INSERT INTO `users` (`id`, `name`, `username`, `email`, `email_verified_at`, `password`, `position`, `avatar`, `status`, `remember_token`, `created_at`, `updated_at`) VALUES
(5, 'Trần Phúc Thịnh', 'admin', 'tranquocdungtrn@gmail.com', NULL, '$2y$12$U8brQqGQp0wDwyWDROtCpe8VUlhg.kiCIGss29sV8Pu5MvHUdODjW', 'Admin', NULL, 'active', NULL, '2026-04-21 19:41:16', '2026-04-22 03:12:43'),
(6, 'Nguyen Van A', 'staff', 'staff@hutechcoffee.local', NULL, '$2y$12$aUlMBhfNf7i4.rX0tuX/..VV2hoKlSJo1VoCmE666fhlADX1JaCp6', 'Staff', 'nv_1776825707_6740.webp', 'active', NULL, '2026-04-21 19:41:47', '2026-04-22 19:11:21');

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
-- Indexes for table `password_resets`
--
ALTER TABLE `password_resets`
  ADD KEY `password_resets_email_index` (`email`);

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
-- Indexes for table `product_recipes`
--
ALTER TABLE `product_recipes`
  ADD PRIMARY KEY (`id`);

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
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `order_details`
--
ALTER TABLE `order_details`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=58;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `product_recipes`
--
ALTER TABLE `product_recipes`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `settings`
--
ALTER TABLE `settings`
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
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

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
