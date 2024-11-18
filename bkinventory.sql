-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Nov 18, 2024 at 04:30 PM
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
-- Database: `bkinventory`
--

-- --------------------------------------------------------

--
-- Table structure for table `checkout_transactions`
--

CREATE TABLE `checkout_transactions` (
  `checkout_transactions_id` int(11) NOT NULL,
  `product_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `payment_method` enum('cash','gcash') DEFAULT NULL,
  `product_name` varchar(100) DEFAULT NULL,
  `quantity` int(11) DEFAULT NULL,
  `amount_paid` decimal(10,2) DEFAULT NULL,
  `total_amount` decimal(10,2) DEFAULT NULL,
  `total_invested_price` decimal(10,2) DEFAULT NULL,
  `transaction_date` datetime DEFAULT current_timestamp(),
  `change_amount` decimal(10,2) DEFAULT NULL,
  `size` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `checkout_transactions`
--

INSERT INTO `checkout_transactions` (`checkout_transactions_id`, `product_id`, `user_id`, `payment_method`, `product_name`, `quantity`, `amount_paid`, `total_amount`, `total_invested_price`, `transaction_date`, `change_amount`, `size`) VALUES
(207, 10, 1, 'cash', 'Cookies & Cream', 2, 400.00, 360.00, 180.00, '2024-11-17 03:56:41', 40.00, '22oz'),
(208, 11, 1, 'cash', 'Ube Macapuno', 2, 400.00, 360.00, 180.00, '2024-11-17 03:56:41', 40.00, '16oz'),
(209, 5, 1, 'cash', 'Buko Lyche', 1, 100.00, 85.00, 45.00, '2024-11-17 04:41:22', 15.00, '16oz'),
(210, 8, 1, 'cash', 'Vanilla', 2, 200.00, 190.00, 80.00, '2024-11-17 15:10:01', 10.00, '22oz'),
(211, 9, 5, 'cash', 'Ube Creamcheese', 2, 250.00, 220.00, 110.00, '2024-11-17 15:25:03', 30.00, '22oz'),
(212, 9, 1, 'cash', 'Ube Creamcheese', 1, 120.00, 110.00, 55.00, '2024-11-17 23:18:46', 10.00, '22oz'),
(213, 10, 1, 'cash', 'Cookies & Cream', 1, 100.00, 95.00, 45.00, '2024-11-18 01:07:14', 5.00, '22oz'),
(214, 18, 1, 'cash', 'Double Dutch', 1, 100.00, 95.00, 45.00, '2024-11-18 04:57:31', 5.00, '22oz'),
(215, 14, 1, 'cash', 'Buko Oreo', 1, 100.00, 85.00, 40.00, '2024-11-18 13:37:10', 15.00, '16oz'),
(216, 12, 1, 'cash', 'Strawberry', 1, 100.00, 95.00, 45.00, '2024-11-18 14:06:46', 5.00, '22oz'),
(217, 5, 1, 'cash', 'Buko Lyche', 2, 200.00, 170.00, 86.00, '2024-11-18 22:58:37', 30.00, '16oz');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `product_id` int(11) NOT NULL,
  `product_name` varchar(100) DEFAULT NULL,
  `product_category` varchar(50) DEFAULT NULL,
  `product_size` varchar(20) DEFAULT NULL,
  `product_selling_price` decimal(10,2) DEFAULT NULL,
  `invested_price` decimal(10,2) DEFAULT NULL,
  `product_stock_quantity` int(11) DEFAULT NULL,
  `product_cups` int(11) DEFAULT NULL,
  `product_status` enum('Available','Not Available') DEFAULT NULL,
  `product_edit_by` int(11) DEFAULT NULL,
  `product_last_edit_by` int(11) DEFAULT NULL,
  `product_last_edited` datetime DEFAULT current_timestamp(),
  `product_image` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`product_id`, `product_name`, `product_category`, `product_size`, `product_selling_price`, `invested_price`, `product_stock_quantity`, `product_cups`, `product_status`, `product_edit_by`, `product_last_edit_by`, `product_last_edited`, `product_image`) VALUES
(5, 'Buko Lyche', 'Slushie', '16oz', 85.00, 43.00, 12, 19, 'Available', 1, 1, '2024-11-18 14:42:01', 'uploads/bukolyche.png'),
(6, 'Buko Graham', 'Slushie', '22oz', 95.00, 46.73, 14, 26, 'Available', 5, 1, '2024-11-17 15:16:53', 'uploads/productimg.jpg'),
(8, 'Vanilla', 'Milk Shake', '22oz', 95.00, 40.00, 10, 20, 'Available', 5, 5, '2024-11-17 15:17:26', 'uploads/mangograhamOVERLOAD.png'),
(9, 'Ube Creamcheese', 'Cream Cheese', '22oz', 110.00, 55.00, 9, 12, 'Available', 5, 1, '2024-11-17 15:17:21', 'uploads/productimg.jpg'),
(10, 'Cookies & Cream', 'Frappe', '22oz', 95.00, 45.00, 13, 19, 'Available', 5, 1, '2024-11-17 15:24:00', 'uploads/productimg.jpg'),
(11, 'Ube Macapuno', 'Milk Shake', '16oz', 85.00, 45.00, 14, 25, 'Available', 10, 5, '2024-11-18 23:09:08', 'uploads/bukolyche.png'),
(12, 'Strawberry', 'Milk Shake', '22oz', 95.00, 45.00, 14, 29, 'Available', 5, 1, '2024-11-17 15:24:11', 'uploads/strawberry.png'),
(13, 'Buko Pandan', 'Cream Cheese', '22oz', 110.00, 46.70, 97, 97, 'Available', 1, 1, '2024-11-16 12:43:43', 'uploads/bukopandan.png'),
(14, 'Buko Oreo', 'Slushie', '16oz', 85.00, 40.00, 15, 19, 'Available', 5, 1, '2024-11-17 15:24:29', 'uploads/productimg.jpg'),
(16, 'Capuccino', 'Frappe', '22oz', 95.00, 46.00, 14, 15, 'Available', 5, 1, '2024-11-17 15:24:22', 'uploads/chocohotfudge.png'),
(17, 'Mango Graham', 'Cream Cheese', '22oz', 115.00, 46.73, 20, 40, 'Available', 5, 1, '2024-11-17 15:24:38', 'uploads/bukolyche.png'),
(18, 'Double Dutch', 'Frappe', '22oz', 95.00, 45.00, 13, 11, 'Available', 10, 1, '2024-11-18 23:07:55', 'uploads/chocohotfudge.png'),
(19, 'Choco Hot Fudge', 'Frappe', '22oz', 95.00, 43.00, 12, 15, 'Available', NULL, 10, '2024-11-18 23:08:26', 'uploads/bgimage.png');

-- --------------------------------------------------------

--
-- Table structure for table `sales`
--

CREATE TABLE `sales` (
  `sales_id` int(11) NOT NULL,
  `product_id` int(11) DEFAULT NULL,
  `checkout_id` int(11) DEFAULT NULL,
  `sales_quantity_sold` int(11) DEFAULT NULL,
  `sales_cups_sold` int(11) DEFAULT NULL,
  `sales_total_amount` decimal(10,2) DEFAULT NULL,
  `sales_total_invested_price` decimal(10,2) DEFAULT NULL,
  `sales_date` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `sales`
--

INSERT INTO `sales` (`sales_id`, `product_id`, `checkout_id`, `sales_quantity_sold`, `sales_cups_sold`, `sales_total_amount`, `sales_total_invested_price`, `sales_date`) VALUES
(48, 10, 207, 2, 2, 190.00, 90.00, '2024-11-17 03:56:41'),
(49, 11, 207, 2, 2, 170.00, 90.00, '2024-11-17 03:56:41'),
(50, 5, 209, 1, 1, 85.00, 45.00, '2024-11-17 04:41:22'),
(51, 8, 210, 2, 2, 190.00, 80.00, '2024-11-17 15:10:01'),
(52, 9, 211, 2, 2, 220.00, 110.00, '2024-11-17 15:25:03'),
(53, 9, 212, 1, 1, 110.00, 55.00, '2024-11-17 23:18:46'),
(54, 10, 213, 1, 1, 95.00, 45.00, '2024-11-18 01:07:14'),
(55, 18, 214, 1, 1, 95.00, 45.00, '2024-11-18 04:57:31'),
(56, 14, 215, 1, 1, 85.00, 40.00, '2024-11-18 13:37:10'),
(57, 12, 216, 1, 1, 95.00, 45.00, '2024-11-18 14:06:46'),
(58, 5, 217, 2, 2, 170.00, 86.00, '2024-11-18 22:58:37');

-- --------------------------------------------------------

--
-- Table structure for table `transactions`
--

CREATE TABLE `transactions` (
  `transactions_id` int(11) NOT NULL,
  `product_id` int(11) DEFAULT NULL,
  `checkout_id` int(11) DEFAULT NULL,
  `avail_product` varchar(255) DEFAULT NULL,
  `quantity_count` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `transactions`
--

INSERT INTO `transactions` (`transactions_id`, `product_id`, `checkout_id`, `avail_product`, `quantity_count`) VALUES
(38, 10, 207, 'Cookies & Cream', 3),
(39, 11, 207, 'Ube Macapuno', 2),
(40, 5, 209, 'Buko Lyche', 3),
(41, 8, 210, 'Vanilla', 2),
(42, 9, 211, 'Ube Creamcheese', 3),
(43, 18, 214, 'Double Dutch', 1),
(44, 14, 215, 'Buko Oreo', 1),
(45, 12, 216, 'Strawberry', 1);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `users_id` int(11) NOT NULL,
  `user_profile` varchar(255) DEFAULT NULL,
  `first_name` varchar(50) DEFAULT NULL,
  `last_name` varchar(50) DEFAULT NULL,
  `contact_number` varchar(15) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `password` varchar(100) DEFAULT NULL,
  `type` enum('cashier','admin') DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`users_id`, `user_profile`, `first_name`, `last_name`, `contact_number`, `email`, `password`, `type`) VALUES
(1, 'uploads/diwata.jfif', 'Example', 'User', '096436927', 'test_user@example.com', '$2y$10$xloBZuaFJ74uxKeHo7JKU.7fnGRgTxkzWE6WvEbhCglJjy0wlmTnO', 'admin'),
(5, 'uploads/images.jfif', 'Mark Lexter', 'Felicilda', '09317412612', 'mmfelicilda@gmail.com', '$2y$10$msEtTGyLVH7TbA8dKN5bnOswf/OdCjW9s6Gwm2DQ4B6XgkQT2TCDK', 'cashier'),
(9, 'uploads/kalbo.jpg', 'Bk', 'Cashier', '09317412614', 'bkcashier@gmail.com', '$2y$10$AuCFEmRzHHL.U6bGBw5Rauqxxxg1Y740dxqVCsN4IjETk26pFdSB2', 'cashier'),
(10, 'uploads/travis tagalog.webp', 'Travis', 'Scott', '09243712321', 'travis@gmail.com', '$2y$10$c9eJ6d06X4R/dV0.jZgyvug274FZy72mFLanEZ9/KCR3ks4YP0/ga', 'cashier'),
(11, 'uploads/bossing.jfif', 'Boss', 'Malupiton', '09231345231', 'bossingmalupiton@gmail.com', '$2y$10$so8f1gSUYAA6eh6YlhzD.eiRsjo8me3lSQ87SVadoZV4ODRayksk.', 'admin');

-- --------------------------------------------------------

--
-- Table structure for table `user_activity`
--

CREATE TABLE `user_activity` (
  `users_activity_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `login_time` datetime DEFAULT current_timestamp(),
  `logout_time` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user_activity`
--

INSERT INTO `user_activity` (`users_activity_id`, `user_id`, `login_time`, `logout_time`) VALUES
(1, 0, '2024-11-11 23:28:02', '2024-11-11 23:33:13'),
(2, 1, '2024-11-11 23:33:40', '2024-11-11 23:34:22'),
(3, 1, '2024-11-12 06:36:20', '2024-11-12 06:37:22'),
(4, 1, '2024-11-12 06:38:20', '2024-11-12 06:47:16'),
(5, 1, '2024-11-12 06:47:27', '2024-11-12 07:18:17'),
(6, 1, '2024-11-12 07:18:28', '2024-11-12 07:42:20'),
(7, 1, '2024-11-13 01:54:58', '2024-11-13 03:08:33'),
(8, 1, '2024-11-13 03:11:10', '2024-11-13 03:12:59'),
(9, 1, '2024-11-13 03:13:26', '2024-11-13 03:15:35'),
(10, 1, '2024-11-13 15:21:29', '2024-11-13 17:41:54'),
(11, 1, '2024-11-13 17:45:25', '2024-11-13 17:50:03'),
(12, 1, '2024-11-13 20:07:55', '2024-11-13 20:49:38'),
(13, 1, '2024-11-13 22:27:43', '2024-11-13 23:38:54'),
(14, 1, '2024-11-14 12:20:09', '2024-11-14 12:20:09'),
(15, 1, '2024-11-14 13:07:43', '2024-11-14 13:09:26'),
(16, 1, '2024-11-14 13:09:47', '2024-11-14 13:10:24'),
(17, 1, '2024-11-14 13:11:23', '2024-11-14 13:20:17'),
(18, 1, '2024-11-14 13:21:02', '2024-11-14 14:10:22'),
(19, 1, '2024-11-14 14:10:28', '2024-11-14 14:19:42'),
(20, 1, '2024-11-14 14:24:09', '2024-11-14 14:27:36'),
(21, 1, '2024-11-14 15:01:28', '2024-11-14 15:11:45'),
(22, 1, '2024-11-14 15:20:54', '2024-11-14 15:27:17'),
(23, 1, '2024-11-14 15:29:30', '2024-11-14 15:46:10'),
(24, 1, '2024-11-14 16:00:55', '2024-11-14 16:04:39'),
(25, 1, '2024-11-14 19:54:19', '2024-11-14 19:55:17'),
(26, 1, '2024-11-15 12:44:56', '2024-11-15 14:54:03'),
(27, 1, '2024-11-15 14:54:08', '2024-11-15 15:07:57'),
(28, 1, '2024-11-15 17:48:41', '2024-11-15 18:33:00'),
(29, 1, '2024-11-15 18:39:54', '2024-11-15 18:59:20'),
(30, 1, '2024-11-15 19:17:52', '2024-11-15 20:19:37'),
(31, 1, '2024-11-15 20:21:57', '2024-11-15 22:06:40'),
(32, 1, '2024-11-15 22:20:00', '2024-11-15 22:20:56'),
(33, 1, '2024-11-15 22:26:02', '2024-11-15 22:35:52'),
(34, 1, '2024-11-16 08:30:53', '2024-11-16 09:46:43'),
(35, 1, '2024-11-16 09:58:25', '2024-11-16 10:13:31'),
(36, 1, '2024-11-16 11:40:54', '2024-11-16 13:08:37'),
(37, 1, '2024-11-16 13:24:40', '2024-11-16 13:35:39'),
(38, 1, '2024-11-16 15:07:07', '2024-11-16 16:03:58'),
(40, 1, '2024-11-16 16:04:34', '2024-11-16 16:12:36'),
(41, 1, '2024-11-16 16:12:41', '2024-11-16 16:27:50'),
(42, 1, '2024-11-16 16:29:27', '2024-11-16 16:29:57'),
(43, 5, '2024-11-16 16:30:04', '2024-11-16 16:30:10'),
(44, 1, '2024-11-16 18:47:00', '2024-11-16 18:51:51'),
(45, 5, '2024-11-16 18:51:57', '2024-11-16 18:51:57'),
(46, 1, '2024-11-16 19:43:07', '2024-11-16 20:44:29'),
(47, 5, '2024-11-16 20:44:36', '2024-11-16 20:53:07'),
(48, 5, '2024-11-16 20:53:15', '2024-11-16 20:55:15'),
(49, 5, '2024-11-16 20:55:25', '2024-11-16 20:58:15'),
(50, 5, '2024-11-16 20:58:20', '2024-11-16 21:03:38'),
(51, 5, '2024-11-16 21:03:46', '2024-11-16 21:04:23'),
(52, 1, '2024-11-16 21:24:29', '2024-11-16 21:27:38'),
(53, 1, '2024-11-16 21:35:05', '2024-11-16 21:37:01'),
(54, 5, '2024-11-16 21:37:21', '2024-11-16 21:38:42'),
(55, 1, '2024-11-16 21:40:19', '2024-11-16 21:53:09'),
(56, 5, '2024-11-16 21:58:59', '2024-11-16 22:02:27'),
(57, 1, '2024-11-16 22:02:32', '2024-11-16 22:04:15'),
(58, 1, '2024-11-16 22:06:38', '2024-11-16 22:15:34'),
(59, 1, '2024-11-16 22:21:54', '2024-11-16 22:24:24'),
(60, 1, '2024-11-17 00:10:38', '2024-11-17 00:18:06'),
(61, 1, '2024-11-17 00:18:11', '2024-11-17 00:21:48'),
(62, 1, '2024-11-17 00:22:53', '2024-11-17 00:25:44'),
(63, 1, '2024-11-17 00:31:07', '2024-11-17 00:42:02'),
(64, 1, '2024-11-17 00:42:06', '2024-11-17 00:42:26'),
(65, 1, '2024-11-17 03:06:07', '2024-11-17 03:06:37'),
(66, 1, '2024-11-17 03:48:42', '2024-11-17 03:48:42'),
(67, 1, '2024-11-17 14:52:00', '2024-11-17 15:16:11'),
(68, 5, '2024-11-17 15:16:16', '2024-11-17 15:23:13'),
(69, 5, '2024-11-17 15:23:19', '2024-11-17 15:29:37'),
(70, 1, '2024-11-17 15:35:13', '2024-11-17 15:35:13'),
(71, 1, '2024-11-17 19:14:47', '2024-11-17 19:21:01'),
(72, 1, '2024-11-17 19:21:13', '2024-11-17 19:22:39'),
(73, 1, '2024-11-17 22:50:48', '2024-11-18 00:00:00'),
(74, 1, '2024-11-18 00:08:10', '2024-11-18 01:11:19'),
(75, 1, '2024-11-18 03:10:12', '2024-11-18 05:35:01'),
(76, 5, '2024-11-18 05:35:05', '2024-11-18 05:35:18'),
(77, 5, '2024-11-18 05:36:22', '2024-11-18 05:36:37'),
(78, 1, '2024-11-18 05:36:42', '2024-11-18 05:36:46'),
(79, 5, '2024-11-18 05:36:56', '2024-11-18 05:37:44'),
(80, 5, '2024-11-18 05:39:08', '2024-11-18 05:39:25'),
(81, 1, '2024-11-18 13:20:10', '2024-11-18 14:33:16'),
(82, 5, '2024-11-18 14:34:11', '2024-11-18 14:34:41'),
(83, 1, '2024-11-18 14:34:45', '2024-11-18 14:37:08'),
(84, 1, '2024-11-18 14:38:21', '2024-11-18 14:43:04'),
(85, 1, '2024-11-18 21:42:17', '2024-11-18 22:27:29'),
(86, 11, '2024-11-18 22:27:37', '2024-11-18 22:43:39'),
(87, 5, '2024-11-18 22:43:43', '2024-11-18 22:46:38'),
(88, 5, '2024-11-18 22:46:43', '2024-11-18 22:48:31'),
(89, 1, '2024-11-18 22:48:54', '2024-11-18 22:53:19'),
(90, 1, '2024-11-18 22:53:50', '2024-11-18 22:55:03'),
(91, 1, '2024-11-18 22:55:25', '2024-11-18 22:56:40'),
(92, 1, '2024-11-18 22:56:48', '2024-11-18 22:57:17'),
(93, 5, '2024-11-18 22:57:23', '2024-11-18 22:57:49'),
(94, 1, '2024-11-18 22:58:02', '2024-11-18 23:00:53'),
(95, 10, '2024-11-18 23:01:12', '2024-11-18 23:02:04'),
(96, 1, '2024-11-18 23:06:25', '2024-11-18 23:06:49'),
(97, 10, '2024-11-18 23:06:59', '2024-11-18 23:09:41');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `checkout_transactions`
--
ALTER TABLE `checkout_transactions`
  ADD PRIMARY KEY (`checkout_transactions_id`),
  ADD KEY `product_id` (`product_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`product_id`),
  ADD KEY `product_edit_by` (`product_edit_by`),
  ADD KEY `product_last_edit_by` (`product_last_edit_by`);

--
-- Indexes for table `sales`
--
ALTER TABLE `sales`
  ADD PRIMARY KEY (`sales_id`),
  ADD KEY `product_id` (`product_id`),
  ADD KEY `checkout_id` (`checkout_id`);

--
-- Indexes for table `transactions`
--
ALTER TABLE `transactions`
  ADD PRIMARY KEY (`transactions_id`),
  ADD KEY `product_id` (`product_id`),
  ADD KEY `checkout_id` (`checkout_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`users_id`);

--
-- Indexes for table `user_activity`
--
ALTER TABLE `user_activity`
  ADD PRIMARY KEY (`users_activity_id`),
  ADD KEY `user_id` (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `checkout_transactions`
--
ALTER TABLE `checkout_transactions`
  MODIFY `checkout_transactions_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=218;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `product_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `sales`
--
ALTER TABLE `sales`
  MODIFY `sales_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=59;

--
-- AUTO_INCREMENT for table `transactions`
--
ALTER TABLE `transactions`
  MODIFY `transactions_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=46;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `users_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `user_activity`
--
ALTER TABLE `user_activity`
  MODIFY `users_activity_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=98;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `checkout_transactions`
--
ALTER TABLE `checkout_transactions`
  ADD CONSTRAINT `checkout_transactions_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`),
  ADD CONSTRAINT `checkout_transactions_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`users_id`);

--
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_ibfk_1` FOREIGN KEY (`product_edit_by`) REFERENCES `users` (`users_id`),
  ADD CONSTRAINT `products_ibfk_2` FOREIGN KEY (`product_last_edit_by`) REFERENCES `users` (`users_id`);

--
-- Constraints for table `sales`
--
ALTER TABLE `sales`
  ADD CONSTRAINT `sales_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`),
  ADD CONSTRAINT `sales_ibfk_2` FOREIGN KEY (`checkout_id`) REFERENCES `checkout_transactions` (`checkout_transactions_id`);

--
-- Constraints for table `transactions`
--
ALTER TABLE `transactions`
  ADD CONSTRAINT `transactions_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`),
  ADD CONSTRAINT `transactions_ibfk_2` FOREIGN KEY (`checkout_id`) REFERENCES `checkout_transactions` (`checkout_transactions_id`);

--
-- Constraints for table `user_activity`
--
ALTER TABLE `user_activity`
  ADD CONSTRAINT `user_activity_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`users_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
