-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: May 26, 2022 at 10:21 PM
-- Server version: 10.4.14-MariaDB
-- PHP Version: 7.4.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `supapong_ticket`
--

-- --------------------------------------------------------

--
-- Table structure for table `bookings`
--

CREATE TABLE `bookings` (
  `id` int(100) NOT NULL,
  `booking_id` varchar(255) DEFAULT NULL,
  `customer_id` varchar(255) NOT NULL,
  `route_id` varchar(255) NOT NULL,
  `customer_route` varchar(200) NOT NULL,
  `booked_amount` int(100) NOT NULL,
  `booked_seat` varchar(100) NOT NULL,
  `booking_created` datetime NOT NULL DEFAULT current_timestamp(),
  `amphor` varchar(255) NOT NULL,
  `note` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `bookings`
--

INSERT INTO `bookings` (`id`, `booking_id`, `customer_id`, `route_id`, `customer_route`, `booked_amount`, `booked_seat`, `booking_created`, `amphor`, `note`) VALUES
(110, 'TAQOT110', 'CUST-2114034', 'RT-9069556', 'ชุมพร &rarr; ประจวบ(12.00)', 85, '1', '2022-04-14 22:33:38', 'ท่าแซะ', ''),
(111, 'D913W111', 'CUST-2114034', 'RT-9069556', 'ชุมพร &rarr; ประจวบ(12.00)', 85, '3', '2022-04-14 22:33:56', 'ท่าแซะ', ''),
(112, 'YNGM7112', 'CUST-2114034', 'RT-9069556', 'ชุมพร &rarr; ประจวบ(12.00)', 85, '4', '2022-04-15 02:33:45', 'ท่าแซะ', ''),
(113, 'R7H90113', 'CUST-2114034', 'RT-9069556', 'ชุมพร &rarr; ประจวบ(12.00)', 85, '12', '2022-04-15 02:34:05', 'ท่าแซะ', ''),
(114, 'J7WPE114', 'CUST-7728867', 'RT-9069556', 'ชุมพร &rarr; ประจวบ(12.00)', 85, '33', '2022-04-15 02:41:24', 'ท่าแซะ', ''),
(115, 'YVDC4115', 'CUST-7728867', 'RT-9069556', 'ชุมพร &rarr; ประจวบ(12.00)', 85, '31', '2022-04-15 02:41:34', 'ท่าแซะ', ''),
(116, 'TVC5G116', 'CUST-2114034', 'RT-9069556', 'ชุมพร &rarr; ประจวบ(12.00)', 85, '32', '2022-04-15 09:43:09', 'ท่าแซะ', ''),
(117, '5U5UW117', 'CUST-7728867', 'RT-9069556', 'ชุมพร &rarr; ประจวบ(12.00)', 85, '13', '2022-04-17 19:39:08', 'ท่าแซะ', ''),
(118, 'U8T6W118', 'CUST-4811569', 'RT-3835554', 'ชุมพร &rarr; ประจวบ(9.00)', 70, '12', '2022-04-17 19:40:45', 'ท่าแซะ', ''),
(119, 'FGQHL119', 'CUST-7728867', 'RT-1908653', 'ชุมพร &rarr; ประจวบ(7.30)', 100, '12', '2022-04-18 19:43:27', 'ทับสะแก', ''),
(120, 'I5G89120', 'CUST-7746771', 'RT-1908653', 'ชุมพร &rarr; ประจวบ(7.30)', 100, '22', '2022-04-19 20:13:35', 'ทับสะแก', ''),
(121, '05URA121', 'CUST-2114034', 'RT-1908653', 'ชุมพร &rarr; ประจวบ(7.30)', 100, '4', '2022-04-21 21:03:34', 'ท่าแซะ', 'ลงอำเภอแรก'),
(122, '8BTP2122', 'CUST-2114034', 'RT-1908653', 'ชุมพร &rarr; ประจวบ(7.30)', 100, '30', '2022-04-21 21:04:03', 'ท่าแซะ', 'ลงสนามบาส'),
(123, 'I1UHP123', 'CUST-7728867', 'RT-3835554', 'ชุมพร &rarr; ประจวบ(9.00)', 70, '12', '2022-04-28 19:02:22', 'ท่าแซะ', ''),
(124, 'ZNX4K124', 'CUST-7728867', 'RT-1908653', 'ชุมพร &rarr; ประจวบ(7.30)', 100, '5', '2022-04-28 19:03:01', 'ท่าแซะ', 'ลงอำเภอเมือง'),
(125, 'JRPV4125', 'CUST-7746771', 'RT-1908653', 'ชุมพร &rarr; ประจวบ(7.30)', 100, '3', '2022-04-29 21:09:37', 'เมือง​ประจวบ​', 'สี่แยกโลตัสประจวบ'),
(126, '7E9ZZ126', 'CUST-6913573', 'RT-1908653', 'ชุมพร &rarr; ประจวบ(7.30)', 100, '21', '2022-04-29 21:32:20', 'ทับสะแก', 'อ่าวมะนาว');

-- --------------------------------------------------------

--
-- Table structure for table `buses`
--

CREATE TABLE `buses` (
  `id` int(100) NOT NULL,
  `bus_no` varchar(255) NOT NULL,
  `bus_assigned` tinyint(1) NOT NULL DEFAULT 0,
  `bus_created` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `buses`
--

INSERT INTO `buses` (`id`, `bus_no`, `bus_assigned`, `bus_created`) VALUES
(44, 'MVL1000', 0, '2021-10-16 22:05:16'),
(45, 'ABC0010', 1, '2021-10-17 22:32:46'),
(46, 'XYZ7890', 0, '2021-10-17 22:33:15'),
(47, 'BCC9999', 0, '2021-10-17 22:33:22'),
(48, 'RDH4255', 1, '2021-10-17 22:33:36'),
(49, 'TTH8888', 1, '2021-10-18 00:05:32'),
(50, 'MMM9969', 1, '2021-10-18 00:06:02'),
(51, 'LLL7699', 0, '2021-10-18 00:06:42'),
(52, 'SSX6633', 0, '2021-10-18 00:06:52'),
(53, 'NBS4455', 0, '2021-10-18 09:27:49'),
(54, 'CAS3300', 0, '2021-10-18 09:36:54');

-- --------------------------------------------------------

--
-- Table structure for table `customers`
--

CREATE TABLE `customers` (
  `id` int(100) NOT NULL,
  `customer_id` varchar(255) DEFAULT NULL,
  `customer_name` varchar(30) NOT NULL,
  `customer_phone` varchar(10) NOT NULL,
  `customer_created` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `customers`
--

INSERT INTO `customers` (`id`, `customer_id`, `customer_name`, `customer_phone`, `customer_created`) VALUES
(34, 'CUST-2114034', 'Kevin Angas', '0815459423', '2021-10-16 22:09:12'),
(61, 'CUST-7890061', 'supapong sakulkoo', '0815459423', '2022-04-13 22:18:57'),
(62, 'CUST-3926362', 'carol danver', '0815459423', '2022-04-14 16:42:40'),
(63, 'CUST-8310463', 'yusei fudo', '085879689', '2022-04-14 17:10:12'),
(64, 'CUST-9221764', 'prayut janocha', '0815459423', '2022-04-14 17:11:15'),
(67, 'CUST-7728867', 'kevinx2 kevinx2', '0815459423', '2022-04-15 02:40:50'),
(68, 'CUST-98968', 'สุกฤตา อนุเผ่า', '0948230588', '2022-04-17 11:07:15'),
(69, 'CUST-4811569', 'แครอล เดนเวอร์', '0815459423', '2022-04-17 19:39:52'),
(70, 'CUST-6446170', 'นางสาวฐิติพร พิกุลทอง', '0936563260', '2022-04-18 19:31:59'),
(71, 'CUST-7746771', 'titporn pikulthong', '0936563260', '2022-04-19 20:08:10'),
(72, 'CUST-1315572', 'titpornsom pikulthong', '0828006376', '2022-04-29 21:23:39'),
(73, 'CUST-6913573', 'visarut pholkid', '0828006376', '2022-04-29 21:25:22');

-- --------------------------------------------------------

--
-- Table structure for table `payment`
--

CREATE TABLE `payment` (
  `id` int(11) NOT NULL,
  `file_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `uploaded_on` datetime NOT NULL,
  `status` enum('1','0') COLLATE utf8_unicode_ci NOT NULL DEFAULT '1',
  `customername` text COLLATE utf8_unicode_ci NOT NULL,
  `customer_id` text COLLATE utf8_unicode_ci NOT NULL,
  `pnr_id` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `pay_status` varchar(255) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `payment`
--

INSERT INTO `payment` (`id`, `file_name`, `uploaded_on`, `status`, `customername`, `customer_id`, `pnr_id`, `pay_status`) VALUES
(24, 'confusion_matrix.png', '2022-04-14 22:40:11', '1', 'Kevin Angas', 'CUST-2114034', 'D913W111', 'รอตรวจสอบ'),
(25, 'banner-gray (1).png', '2022-04-15 02:34:51', '1', 'Kevin Angas', 'CUST-2114034', 'R7H90113', 'จ่ายแล้ว'),
(26, 'codeacademy.jpg', '2022-04-15 02:43:05', '1', 'kevinx2 kevinx2', 'CUST-7890061', 'YVDC4115', 'ไม่อนุมัติ'),
(27, 'codeacademy.jpg', '2022-04-17 19:41:34', '1', 'แครอล เดนเวอร์', 'CUST-4811569', 'U8T6W118', 'จ่ายแล้ว'),
(28, '243260042_1034597154027196_4945262388590259769_n.png', '2022-04-19 20:19:52', '1', 'titporn pikulthong', 'CUST-7746771', 'I5G89120', 'จ่ายแล้ว'),
(29, 'bg.png', '2022-04-28 19:04:17', '1', 'kevinx2 kevinx2', 'CUST-7728867', 'ZNX4K124', 'จ่ายแล้ว'),
(30, '7.jpg', '2022-04-29 21:39:22', '1', 'visarut pholkid', 'CUST-6913573', '7E9ZZ126', 'จ่ายแล้ว');

-- --------------------------------------------------------

--
-- Table structure for table `routes`
--

CREATE TABLE `routes` (
  `id` int(100) NOT NULL,
  `route_id` varchar(255) NOT NULL,
  `bus_no` varchar(155) NOT NULL,
  `route_cities` varchar(255) NOT NULL,
  `route_dep_date` date NOT NULL,
  `route_dep_time` time NOT NULL,
  `route_step_cost` int(100) NOT NULL,
  `route_created` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `routes`
--

INSERT INTO `routes` (`id`, `route_id`, `bus_no`, `route_cities`, `route_dep_date`, `route_dep_time`, `route_step_cost`, `route_created`) VALUES
(53, 'RT-1908653', 'MVL1000', 'ชุมพร,ประจวบ(7.30)', '2022-04-14', '07:30:00', 100, '2021-10-16 22:05:42'),
(54, 'RT-3835554', 'MMM9969', 'ชุมพร,ประจวบ(9.00)', '2022-04-14', '09:00:00', 70, '2021-10-16 22:12:32'),
(55, 'RT-9941455', 'RDH4255', 'ชุมพร,ประจวบ(10.30)', '2022-04-14', '10:30:00', 110, '2021-10-17 22:34:47'),
(56, 'RT-9069556', 'XYZ7890', 'ชุมพร,ประจวบ(12.00)', '2022-04-14', '12:00:00', 85, '2021-10-17 23:39:57'),
(57, 'RT-775557', 'ABC0010', 'ชุมพร,ประจวบ(14.30)', '2022-04-14', '14:30:00', 131, '2021-10-17 23:42:12'),
(58, 'RT-753558', 'TTH8888', 'ชุมพร,ประจวบ(16.30)', '2022-04-14', '16:30:00', 55, '2021-10-18 00:04:42');

-- --------------------------------------------------------

--
-- Table structure for table `seats`
--

CREATE TABLE `seats` (
  `bus_no` varchar(155) NOT NULL,
  `seat_booked` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `seats`
--

INSERT INTO `seats` (`bus_no`, `seat_booked`) VALUES
('ABC0010', ''),
('BCC9999', NULL),
('CAS3300', '16'),
('LLL7699', ''),
('MMM9969', '2,15,6,18,12,12,12'),
('MVL1000', '12,22,4,30,5,3,21'),
('NBS4455', NULL),
('RDH4255', '15'),
('SSX6633', NULL),
('TTH8888', ''),
('XYZ7890', '1,3,4,12,33,31,32,13');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `user_fullname` varchar(100) DEFAULT NULL,
  `user_name` varchar(30) DEFAULT NULL,
  `user_password` varchar(255) DEFAULT NULL,
  `user_created` datetime NOT NULL DEFAULT current_timestamp(),
  `customer_id` varchar(255) DEFAULT NULL,
  `role` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `user_fullname`, `user_name`, `user_password`, `user_created`, `customer_id`, `role`) VALUES
(1, 'Kevin Angas', 'admin', '$2y$10$7rLSvRVyTQORapkDOqmkhetjF6H9lJHngr4hJMSM2lHObJbW5EQh6', '2021-06-02 13:55:21', 'CUST-2114034', 'admin'),
(16, 'supapong sakulkoo', 'superauu', '$2y$10$.jsEJgkVCoT7591uv0Jy0uMZJ5xzmronVCHcotjB1o3jyLs5.Graa', '2022-04-13 22:18:57', '', ''),
(18, 'carol danver', 'carol', '$2y$10$nCDw.keOD5aTw3lxHyXiyu5iVlW7TNlavLenSdJE2g63eljExDce6', '2022-04-14 16:42:40', '', ''),
(23, 'yusei fudo', 'yusei', '$2y$10$0xiugrmj7S4H/l.Du.Foq.UfhCP4DWZo9aQclW9ZfO8l8mB8d7abq', '2022-04-14 17:07:56', '', 'admin'),
(24, 'prayut janocha', 'prayut', '$2y$10$9Kkj8NZ43tQcW2xPvxy/xuIZlggDcGJKePobCu0wG4sB.syaFYMyy', '2022-04-14 17:11:15', '', ''),
(26, 'pom pom', 'bigpomx2', '$2y$10$.TV/0JNc090QuoVuFKhwB.mlmYMdpok2OP1fgOPVaXq2wckEYnjXy', '2022-04-14 18:44:10', '', 'admin'),
(28, 'kevinx2 kevinx2', 'kevinx2', '$2y$10$a8qkMkulM0x.IyTncM/FjuUbKHaBF9tH3wheHM.sIMU1nF0tItbeG', '2022-04-15 02:40:50', NULL, NULL),
(29, 'สุกฤตา อนุเผ่า', 'eyesukitta', '$2y$10$DmSpXauEts/pjr2nmZ.QluNRF5i04I56cuu9boeQLylpSvJktx5LO', '2022-04-17 11:07:15', NULL, NULL),
(30, 'แครอล เดนเวอร์', 'lemon', '$2y$10$ApHM2p5BNwxt09wQpZfW.O7NDKrqyg5YlGyPVsI.z./ZkN.zuok6C', '2022-04-17 19:39:52', NULL, NULL),
(31, 'นางสาวฐิติพร พิกุลทอง', 'titporn', '$2y$10$1D8Zcf9CSAoIW.T7yie0qOGXq/MEQSc2OonjoyXYYGfN9yrV97tbi', '2022-04-18 19:31:59', NULL, NULL),
(32, 'titporn pikulthong', 'titporn22', '$2y$10$iVGZJAoxBEENtdzBFrtvI.dmP3tK.bqgVbPoTF1Y/bP5J6qI4aytu', '2022-04-19 20:08:10', NULL, NULL),
(33, 'titpornsom pikulthong', 'titiporn18', '$2y$10$INS5Nc44vKMWRdC2J5CLVuaFdEdDT4ubY7kT6kQBIriTrCI/rp5km', '2022-04-29 21:23:39', NULL, NULL),
(34, 'visarut pholkid', 'somsom18', '$2y$10$nYe4xLggmGF2In7ijtpopeeU1cQYlb1DItZoZkrQ7.Z5t66UcG86i', '2022-04-29 21:25:22', NULL, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `bookings`
--
ALTER TABLE `bookings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `buses`
--
ALTER TABLE `buses`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `customers`
--
ALTER TABLE `customers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `payment`
--
ALTER TABLE `payment`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `routes`
--
ALTER TABLE `routes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `seats`
--
ALTER TABLE `seats`
  ADD PRIMARY KEY (`bus_no`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `bookings`
--
ALTER TABLE `bookings`
  MODIFY `id` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=127;

--
-- AUTO_INCREMENT for table `buses`
--
ALTER TABLE `buses`
  MODIFY `id` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=55;

--
-- AUTO_INCREMENT for table `customers`
--
ALTER TABLE `customers`
  MODIFY `id` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=74;

--
-- AUTO_INCREMENT for table `payment`
--
ALTER TABLE `payment`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `routes`
--
ALTER TABLE `routes`
  MODIFY `id` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=61;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
