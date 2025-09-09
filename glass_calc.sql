-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 09, 2025 at 09:37 AM
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
-- Database: `glass_calc`
--

-- --------------------------------------------------------

--
-- Table structure for table `calculator_options`
--

CREATE TABLE `calculator_options` (
  `id` int(11) NOT NULL,
  `type` enum('shape','extra','vent','toughened','spacer_color') DEFAULT NULL,
  `name` varchar(100) DEFAULT NULL,
  `price_type` enum('fixed','percent','linear') DEFAULT NULL,
  `price_value` decimal(10,2) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `enquiries`
--

CREATE TABLE `enquiries` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `subject` varchar(150) DEFAULT NULL,
  `message` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `enquiries`
--

INSERT INTO `enquiries` (`id`, `name`, `email`, `phone`, `subject`, `message`, `created_at`) VALUES
(1, 'kumar', 'ballasunilkumar19@gmail.com', '8008778418', 'SOC Notification: [Severity: Medium] SEEDWORKS [SW_HY_S01]', 'testing 123456', '2025-08-28 20:38:46'),
(2, 'rfeihg', 'sunil@gmail.com', 'efg', 'gg', 'regrb', '2025-08-28 20:48:33'),
(3, 'sai prasad', 'saimuddada789@gmail.com', '6309502681', 'english', 'hi how r uy\r\n', '2025-08-28 20:49:09'),
(4, 'sai prasad', 'saimuddada789@gmail.com', '6309502681', 'english', 'hi how r uy\r\n', '2025-08-28 20:49:14'),
(5, 'sai prasad', 'saimuddada789@gmail.com', '6309502681', 'english', 'hi how r uy\r\n', '2025-08-28 20:50:09'),
(6, 'ef', 'ballasunilkumar19@gmail.com', '789', '456', 'tette', '2025-08-28 20:54:37'),
(7, 'praveen kumar', 'saimuddada789@gmail.com', '1234567890', 'asdf', 'asdfvbn', '2025-08-28 20:54:44'),
(8, 'ef', 'ballasunilkumar19@gmail.com', '789', '456', 'tette', '2025-08-28 20:55:50'),
(9, 'varsghahbs', 'saimuddada789@gmail.com', '6309502681', 'english', 'asdfghn', '2025-08-28 20:56:55'),
(10, 'fgeg', 'fuguvj@gm.com', 'cyh', 'v ', '46vvhuf', '2025-08-28 20:57:02'),
(11, 'raghava', 'raghava2003@gmail.com', '234567', '123', '123456', '2025-08-28 20:57:29'),
(12, 'raghava', 'raghava2003@gmail.com', '234567', '123', '123456', '2025-08-28 20:57:31'),
(13, 'raghava', 'raghava2003@gmail.com', '234567', '123', '123456', '2025-08-28 20:57:39'),
(14, 'kumar thalli', 'kumar2003@gmail.com', '1238936327', 'telugu', 'mama tagudam rara ', '2025-08-28 20:58:16'),
(15, 'kumar thalli', 'kumar2003@gmail.com', '1238936327', 'telugu', 'mama tagudam rara ', '2025-08-28 20:58:18'),
(16, 'er', 'fg@gmil.com', 'e43', 'rer', 'ee', '2025-08-28 20:58:29'),
(17, 'janu ', 'janu12@gmail.com', '9174343434', '545453436', 'i love cheppudam\r\n', '2025-08-28 20:59:04'),
(18, 'janu ', 'janu12@gmail.com', '9174343434', '545453436', 'i love cheppudam\r\n', '2025-08-28 20:59:05'),
(19, 'oshfn', 'demo@webdok.in', '8008778418', 'nff', 'ihfsb', '2025-08-28 20:59:47'),
(20, 'uday', 'uday12@gmail.com', '9989768663', 'love problem ', 'beer lu tesukoni rara tagudam', '2025-08-28 21:00:00'),
(21, 'uday', 'uday12@gmail.com', '9989768663', 'love problem ', 'beer lu tesukoni rara tagudam', '2025-08-28 21:00:02'),
(22, 'kumar', 'sunil@gmail.com', '8008778418', 'Kiya | GUC_Multiple Failed Login Attempts on Linux | critical ', 'wer', '2025-08-28 22:00:45'),
(23, 'kumar', 'ballasunilkumar19@gmail.com', '8008778481', 'Demo | Amazon Web Services Dataset Stopped Triggering', 'qwertyuiopasdfghjk\r\nasdfghjkxcvbn\r\nzxcvbnm,dfgh\r\ndfgh', '2025-08-28 22:04:26'),
(24, 'Sunil', 'kumar@gmail.com', '7008778418', 'Seedworks | SA dataset Stopped triggering', 'efxzxcvbnsdfg\r\nqwer', '2025-08-28 22:05:41'),
(25, 'Qwerty kumar', 'demo@webdok.in', '8008778418', 'SOC Notification: [Severity: Medium] SEEDWORKS [SW_HY_S01]', 'qwertyuiop\r\nasdfgjkl\r\nzxcvbnm', '2025-09-03 21:48:28'),
(26, 'Qwerty kumar', 'demo@webdok.in', '8008778418', 'SOC Notification: [Severity: Medium] SEEDWORKS [SW_HY_S01]', 'qwertyuiop\r\nasdfgjkl\r\nzxcvbnm', '2025-09-03 21:48:39'),
(27, 'kumar', 'kumar@gmail.com', '7008778481', 'Demo | Amazon Web Services Dataset Stopped Triggering', 'sdfgh\r\nfghj', '2025-09-03 21:52:40'),
(28, 'developer', 'dev@webdok.in', '8886017810', 'Final testing duplicate bug', 'qwertyuiop\r\nasdfghjkl\r\nzxcvbnm', '2025-09-03 21:53:30'),
(29, 'developer', 'dev@webdok.in', '8886017810', 'Final testing duplicate bug', 'qwertyuiop\r\nasdfghjkl\r\nzxcvbnm', '2025-09-03 22:25:18');

-- --------------------------------------------------------

--
-- Table structure for table `glass_categories`
--

CREATE TABLE `glass_categories` (
  `id` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `area_price` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `title` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `is_read` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `notifications`
--

INSERT INTO `notifications` (`id`, `user_id`, `title`, `message`, `created_at`, `is_read`) VALUES
(1, 4, 'testing', 'My test notify', '2025-09-04 02:16:49', 1),
(2, NULL, 'All Test', 'We are sending notifications to all users', '2025-09-04 02:17:18', 1),
(3, NULL, 'read / unread', 'test man', '2025-09-04 02:39:28', 1),
(4, NULL, 'test', 'qwertyuiop', '2025-09-04 02:46:21', 1),
(5, NULL, 'hua', 'kya', '2025-09-04 02:49:53', 1),
(6, NULL, 'just', 'now', '2025-09-04 02:51:04', 1),
(7, 3, 'bhaiya', 'ok', '2025-09-04 02:51:24', 0),
(8, 3, 'oka', 'samsung', '2025-09-04 02:51:40', 0),
(9, 3, 'oka', 'samsung', '2025-09-04 02:52:01', 0),
(10, NULL, 'qwertyuiop', 'asdfghjkl\r\nzxcvbnm', '2025-09-04 02:52:27', 1),
(11, 4, 'tet', 'excam', '2025-09-04 02:53:51', 1),
(12, NULL, '789', 'sdfgh', '2025-09-04 02:54:12', 1),
(13, NULL, '1234679', 'qwertyuiosdfhjk\r\nxcvbnmdfg\r\nwdv tgblkjbv', '2025-09-04 02:54:29', 1),
(14, NULL, 'qscef', 'vbjnk', '2025-09-04 02:54:54', 1),
(15, NULL, 'final test', 'yes', '2025-09-04 02:59:18', 1),
(16, 4, 'final user test', 'yes', '2025-09-04 02:59:30', 1),
(17, NULL, 'bug setting', 'clearied', '2025-09-04 03:05:08', 1);

-- --------------------------------------------------------

--
-- Table structure for table `notification_reads`
--

CREATE TABLE `notification_reads` (
  `id` int(11) NOT NULL,
  `notification_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `read_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `notification_reads`
--

INSERT INTO `notification_reads` (`id`, `notification_id`, `user_id`, `read_at`) VALUES
(1, 17, 4, '2025-09-04 03:14:24'),
(2, 17, 6, '2025-09-04 03:14:43'),
(3, 15, 4, '2025-09-04 03:15:07'),
(4, 14, 4, '2025-09-04 03:15:09'),
(5, 13, 4, '2025-09-04 03:15:10'),
(6, 12, 4, '2025-09-04 03:15:11'),
(7, 10, 4, '2025-09-04 03:15:12'),
(8, 6, 4, '2025-09-04 03:15:14'),
(9, 5, 4, '2025-09-04 03:15:14'),
(10, 2, 4, '2025-09-04 03:15:21'),
(11, 3, 4, '2025-09-04 03:15:22'),
(12, 4, 4, '2025-09-04 03:15:23');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `total` decimal(10,2) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `id` int(11) NOT NULL,
  `order_id` int(11) DEFAULT NULL,
  `product_type` enum('single','double','triple') DEFAULT NULL,
  `details` text DEFAULT NULL,
  `price` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

CREATE TABLE `password_resets` (
  `id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `expires_at` datetime NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('customer','admin') DEFAULT 'customer',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `reset_token` varchar(64) DEFAULT NULL,
  `reset_expires` datetime DEFAULT NULL,
  `house_no` varchar(100) DEFAULT NULL,
  `street` varchar(255) DEFAULT NULL,
  `city` varchar(100) DEFAULT NULL,
  `mobile` varchar(20) DEFAULT NULL,
  `state` varchar(100) DEFAULT NULL,
  `country` varchar(100) DEFAULT NULL,
  `pincode` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `role`, `created_at`, `reset_token`, `reset_expires`, `house_no`, `street`, `city`, `mobile`, `state`, `country`, `pincode`) VALUES
(1, 'kumar', 'sunil@gmail.com', '$2y$10$MyAnIDXHk9yQCGAddmtARep/IwJNgJeUYrIGuw.Wt0PkNKK63IIfS', 'customer', '2025-07-23 14:26:11', 'f1ddfdeb4d5b0c871886a46bcc04acded61ed957be598f4dc6a20d3024020590', '2025-07-23 20:56:11', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(3, 'qwert', 'Qwert@gmail.com', '$2y$10$AThlbXM5h8gWVjNbMmUF3ukLe2UtVOwQ9q0KfI62PPFhnL7pBHeEG', 'admin', '2025-07-23 15:16:57', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(4, 'Demo User', 'demo@webdok.in', '$2y$10$xZyAZxO0CjXRqjwaOM0WAuSi4MN/G6/vEeNM.PNffW8Mm8QF9Znxi', 'customer', '2025-08-28 19:13:31', NULL, NULL, 'Demo Office', 'Demo Street', 'Demo City', '9876543210', 'Hyderabad', 'india', '532001'),
(5, 'sai prasad', 'saimuddada789@gmail.com', '$2y$10$JxYniIhzLGY/xefSq2fFV.5MjNt1Gq1wBpXNMSFHUqIl6ITk5dBFO', 'customer', '2025-08-28 21:00:49', NULL, NULL, 'vaddivada village', 'kotha veedhi', 'srikakulam', '6309502681', 'andhra pradesh', 'india', '532195'),
(6, 'developer', 'dev@webdok.in', '$2y$10$slOgMNCyruCLW8zOy408au/W1.QXBpqIwCx4ZUfT4ykiLnXhDMSn2', 'customer', '2025-09-03 21:30:48', NULL, NULL, '401', 'P.O. Box 304, 1036 Integer St.', 'Srikakulam', '08008778418', 'Andhra Pradesh', 'India', '532005');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `calculator_options`
--
ALTER TABLE `calculator_options`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `enquiries`
--
ALTER TABLE `enquiries`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `glass_categories`
--
ALTER TABLE `glass_categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `notification_reads`
--
ALTER TABLE `notification_reads`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `notification_id` (`notification_id`,`user_id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `password_resets`
--
ALTER TABLE `password_resets`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `calculator_options`
--
ALTER TABLE `calculator_options`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `enquiries`
--
ALTER TABLE `enquiries`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT for table `glass_categories`
--
ALTER TABLE `glass_categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `notification_reads`
--
ALTER TABLE `notification_reads`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `password_resets`
--
ALTER TABLE `password_resets`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
