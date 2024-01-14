-- phpMyAdmin SQL Dump
-- version 5.1.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Nov 29, 2023 at 09:56 AM
-- Server version: 5.7.24
-- PHP Version: 8.0.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `testcase`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin_table`
--

CREATE TABLE `admin_table` (
  `id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` varchar(20) DEFAULT 'admin',
  `title` varchar(10) NOT NULL,
  `fname` varchar(100) NOT NULL,
  `lname` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `admin_table`
--

INSERT INTO `admin_table` (`id`, `username`, `email`, `password`, `role`, `title`, `fname`, `lname`) VALUES
(1, 'admin1', 'admin1@example.com', 'd04fcc6b2fceaee8d9c3fd99a8d789a7', 'admin', 'Mr.', 'John', 'Doe'),
(2, 'admin2', 'admin2@example.com', '0d8c5b4b6f02d99a0342a164af396bbd', 'admin', 'Miss', 'Jane', 'Doe'),
(3, 'admin3', 'admin3@example.com', 'hashed_password', 'admin', 'Mrs.', 'Alice', 'Smith');

-- --------------------------------------------------------

--
-- Table structure for table `bookings`
--

CREATE TABLE `bookings` (
  `id` int(11) NOT NULL,
  `parking_spot_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `booking_time` datetime DEFAULT NULL,
  `floor_level` varchar(255) DEFAULT NULL,
  `zone` varchar(255) DEFAULT NULL,
  `slot_no` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `bookings`
--

INSERT INTO `bookings` (`id`, `parking_spot_id`, `user_id`, `booking_time`, `floor_level`, `zone`, `slot_no`, `created_at`) VALUES
(8, 1, 6, '2023-11-24 17:55:00', '2', 'B', 12, '2023-11-24 08:56:03'),
(9, 1, 6, '2023-11-24 16:56:00', '2', 'B', 4, '2023-11-24 08:56:19'),
(10, 4, 6, '2023-11-24 22:02:00', '1', 'B', 12, '2023-11-24 14:02:14');

-- --------------------------------------------------------

--
-- Table structure for table `cars`
--

CREATE TABLE `cars` (
  `id` int(11) NOT NULL,
  `car_type` varchar(50) NOT NULL,
  `car_registration` varchar(50) NOT NULL,
  `car_brand` varchar(50) NOT NULL,
  `car_model` varchar(50) NOT NULL,
  `user_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `cars`
--

INSERT INTO `cars` (`id`, `car_type`, `car_registration`, `car_brand`, `car_model`, `user_id`) VALUES
(1, 'Car (Van)', 'ญญ 113 (กรุงเทพมหานคร)', 'Toyota', 'Crown', 6);

-- --------------------------------------------------------

--
-- Table structure for table `parking_spots`
--

CREATE TABLE `parking_spots` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `image_path` varchar(255) NOT NULL,
  `open_time` time NOT NULL,
  `close_time` time NOT NULL,
  `slot_no` int(11) NOT NULL,
  `province` varchar(255) NOT NULL,
  `district` varchar(255) NOT NULL,
  `sub_district` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `parking_spots`
--

INSERT INTO `parking_spots` (`id`, `name`, `image_path`, `open_time`, `close_time`, `slot_no`, `province`, `district`, `sub_district`) VALUES
(1, 'Siam Paragon', 'img/11.jpg', '08:00:00', '18:00:00', 28, 'Bangkok', 'Pathum Wan', 'Siam'),
(2, 'Central Plaza', 'img/22.jpg', '09:00:00', '20:00:00', 128, 'Bangkok', 'Lat Phrao', 'Chom Phon'),
(3, 'Chatuchak Market', 'img/33.jpg', '10:00:00', '17:00:00', 128, 'Bangkok', 'Chatuchak', 'Chatuchak'),
(4, 'MBK Center', 'img/44.jpg', '11:00:00', '21:00:00', 127, 'Bangkok', 'Pathum Wan', 'Wang Mai'),
(5, 'Central World', 'img/55.jpg', '10:00:00', '22:00:00', 128, 'Bangkok', 'Pathum Wan', 'Pathum Wan'),
(6, 'Mega Bangna', 'img/66.jpg', '10:00:00', '22:00:00', 126, 'Samut Prakan', 'Bang Phli', 'Bang Kaeo'),
(7, 'Future Park Rangsit', 'img/77.jpg', '10:30:00', '22:00:00', 127, 'Phathum Thani', 'Thanyaburi', 'Prachathipat'),
(8, 'Esplanade Ratchadaphisek', 'img/88.jpg', '10:00:00', '22:00:00', 130, 'Bangkok', 'Din Daeng', 'Din Daeng'),
(9, 'Fashion Island', 'img/99.jpg', '10:00:00', '21:00:00', 125, 'Bangkok', 'Khan Na Yao', 'Khan Na Yao');

-- --------------------------------------------------------

--
-- Table structure for table `parking_spot_floors`
--

CREATE TABLE `parking_spot_floors` (
  `id` int(11) NOT NULL,
  `parking_spot_id` int(11) NOT NULL,
  `floor_level` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `parking_spot_floors`
--

INSERT INTO `parking_spot_floors` (`id`, `parking_spot_id`, `floor_level`, `created_at`) VALUES
(1, 1, 1, '2023-11-23 03:31:20'),
(2, 1, 2, '2023-11-23 03:31:20'),
(3, 1, 3, '2023-11-23 03:31:20'),
(4, 1, 4, '2023-11-23 03:31:20'),
(5, 2, 1, '2023-11-23 03:31:20'),
(6, 2, 2, '2023-11-23 03:31:20'),
(7, 2, 3, '2023-11-23 03:31:20'),
(8, 2, 4, '2023-11-23 03:31:20'),
(9, 3, 1, '2023-11-23 03:31:20'),
(10, 3, 2, '2023-11-23 03:31:20'),
(11, 3, 3, '2023-11-23 03:31:20'),
(12, 3, 4, '2023-11-23 03:31:20'),
(13, 4, 1, '2023-11-23 03:31:20'),
(14, 4, 2, '2023-11-23 03:31:20'),
(15, 4, 3, '2023-11-23 03:31:20'),
(16, 4, 4, '2023-11-23 03:31:20'),
(17, 5, 1, '2023-11-23 03:31:20'),
(18, 5, 2, '2023-11-23 03:31:20'),
(19, 5, 3, '2023-11-23 03:31:20'),
(20, 6, 1, '2023-11-23 03:31:20'),
(21, 6, 2, '2023-11-23 03:31:20'),
(22, 6, 3, '2023-11-23 03:31:20'),
(23, 7, 1, '2023-11-23 03:31:20'),
(24, 7, 2, '2023-11-23 03:31:20'),
(25, 7, 3, '2023-11-23 03:31:20'),
(26, 7, 4, '2023-11-23 03:31:20'),
(27, 8, 1, '2023-11-23 03:31:20'),
(28, 8, 2, '2023-11-23 03:31:20'),
(29, 8, 3, '2023-11-23 03:31:20'),
(30, 9, 1, '2023-11-23 03:31:20'),
(31, 9, 2, '2023-11-23 03:31:20'),
(32, 9, 3, '2023-11-23 03:31:20'),
(33, 9, 4, '2023-11-23 03:31:20');

-- --------------------------------------------------------

--
-- Table structure for table `parking_spot_zones`
--

CREATE TABLE `parking_spot_zones` (
  `id` int(11) NOT NULL,
  `floor_id` int(11) NOT NULL,
  `zone` varchar(1) NOT NULL,
  `parking_spots_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `parking_spot_zones`
--

INSERT INTO `parking_spot_zones` (`id`, `floor_id`, `zone`, `parking_spots_id`, `created_at`) VALUES
(13, 13, 'A', 4, '2023-11-23 03:53:49'),
(14, 14, 'B', 4, '2023-11-23 03:53:49'),
(33, 15, 'A', 1, '2023-11-23 03:53:49'),
(34, 1, 'B', 1, '2023-11-23 03:53:49'),
(35, 1, 'C', 1, '2023-11-23 03:53:49'),
(36, 1, 'D', 1, '2023-11-23 03:53:49'),
(37, 2, 'A', 2, '2023-11-23 03:53:49'),
(38, 2, 'B', 2, '2023-11-23 03:53:49'),
(39, 2, 'C', 2, '2023-11-23 03:53:49'),
(40, 2, 'D', 2, '2023-11-23 03:53:49'),
(41, 3, 'A', 3, '2023-11-23 03:53:49'),
(42, 3, 'B', 3, '2023-11-23 03:53:49'),
(43, 3, 'C', 3, '2023-11-23 03:53:49'),
(44, 3, 'D', 3, '2023-11-23 03:53:49'),
(47, 4, 'C', 4, '2023-11-23 03:53:49'),
(48, 4, 'D', 4, '2023-11-23 03:53:49'),
(49, 5, 'A', 5, '2023-11-23 03:53:49'),
(50, 5, 'B', 5, '2023-11-23 03:53:49'),
(51, 5, 'C', 5, '2023-11-23 03:53:49'),
(52, 6, 'A', 6, '2023-11-23 03:53:49'),
(53, 6, 'B', 6, '2023-11-23 03:53:49'),
(54, 6, 'C', 6, '2023-11-23 03:53:49'),
(55, 7, 'A', 7, '2023-11-23 03:53:49'),
(56, 7, 'B', 7, '2023-11-23 03:53:49'),
(57, 7, 'C', 7, '2023-11-23 03:53:49'),
(58, 7, 'D', 7, '2023-11-23 03:53:49'),
(59, 8, 'A', 8, '2023-11-23 03:53:49'),
(60, 8, 'B', 8, '2023-11-23 03:53:49'),
(61, 8, 'C', 8, '2023-11-23 03:53:49'),
(62, 9, 'A', 9, '2023-11-23 03:53:49'),
(63, 9, 'B', 9, '2023-11-23 03:53:49'),
(64, 9, 'C', 9, '2023-11-23 03:53:49'),
(65, 9, 'D', 9, '2023-11-23 03:53:49');

-- --------------------------------------------------------

--
-- Table structure for table `user_form`
--

CREATE TABLE `user_form` (
  `id` int(100) NOT NULL,
  `username` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(100) NOT NULL,
  `title` varchar(10) NOT NULL,
  `fname` varchar(100) NOT NULL,
  `lname` varchar(100) NOT NULL,
  `gender` varchar(10) NOT NULL,
  `image` varchar(100) NOT NULL,
  `role` varchar(20) DEFAULT 'user',
  `phone` varchar(10) NOT NULL,
  `date_of_birth` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `user_form`
--

INSERT INTO `user_form` (`id`, `username`, `email`, `password`, `title`, `fname`, `lname`, `gender`, `image`, `role`, `phone`, `date_of_birth`) VALUES
(1, 'testinsert', 'tester1@gmail.con', '32bf0e6fcff51e53bd74e70ba1d622b2', 'Mr.', 'maa', 'taa', 'Male', '', 'user', '0', NULL),
(2, 'test1', 'tester11@gmail.con', '581421bd038f1bcdc63ad88ea2d49820', 'Ms.', 'test', 'admin12', 'Male', '', 'user', '0', NULL),
(3, 'testinsert01', 'testinsert01@examples12.com', '99e692f21dbf0d9d6aea039f675d4ef2', 'Ms.', 'mstest', 'kkkk', 'Female', 'Screenshot 2023-10-14 185410.png', 'user', '0', NULL),
(4, 'test12', 'tester12653@gmail.con', 'd41d8cd98f00b204e9800998ecf8427e', 'Mr.', 'ldkgksd', 'sdlvms', 'Male', 'Screenshot 2023-10-14 190338.png', 'user', '0', NULL),
(5, 'test123', 'insert123@gmail.com', '81dc9bdb52d04dc20036dbd8313ed055', 'Mr.', 'admin12', 'admin12', 'Male', '', 'user', '0', NULL),
(6, 'teamteam', 'demo@gmail.com', 'e9ea90857363708afc42938a00719e76', 'Mr.', 'Tean', 'Fk', 'Male', 'creditcard.png', 'user', '0951234432', '2004-11-12'),
(7, 'demo1', 'demo12@gmail.com', '827ccb0eea8a706c4c34a16891f84e7b', 'Mr.', 'demo12', 'demo22', 'Male', '', 'user', '', NULL),
(8, 'demo11', 'demo123@gmail.com', '827ccb0eea8a706c4c34a16891f84e7b', 'Ms.', 'demo111', 'demo222', 'Female', '', 'user', '', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin_table`
--
ALTER TABLE `admin_table`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `bookings`
--
ALTER TABLE `bookings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `parking_spot_id` (`parking_spot_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `cars`
--
ALTER TABLE `cars`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `parking_spots`
--
ALTER TABLE `parking_spots`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `parking_spot_floors`
--
ALTER TABLE `parking_spot_floors`
  ADD PRIMARY KEY (`id`),
  ADD KEY `parking_spot_id` (`parking_spot_id`);

--
-- Indexes for table `parking_spot_zones`
--
ALTER TABLE `parking_spot_zones`
  ADD PRIMARY KEY (`id`),
  ADD KEY `floor_id` (`floor_id`),
  ADD KEY `parking_spots_id` (`parking_spots_id`);

--
-- Indexes for table `user_form`
--
ALTER TABLE `user_form`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin_table`
--
ALTER TABLE `admin_table`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `bookings`
--
ALTER TABLE `bookings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `cars`
--
ALTER TABLE `cars`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `parking_spots`
--
ALTER TABLE `parking_spots`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `parking_spot_floors`
--
ALTER TABLE `parking_spot_floors`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- AUTO_INCREMENT for table `parking_spot_zones`
--
ALTER TABLE `parking_spot_zones`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=66;

--
-- AUTO_INCREMENT for table `user_form`
--
ALTER TABLE `user_form`
  MODIFY `id` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `bookings`
--
ALTER TABLE `bookings`
  ADD CONSTRAINT `bookings_ibfk_1` FOREIGN KEY (`parking_spot_id`) REFERENCES `parking_spots` (`id`),
  ADD CONSTRAINT `bookings_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `user_form` (`id`);

--
-- Constraints for table `cars`
--
ALTER TABLE `cars`
  ADD CONSTRAINT `cars_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user_form` (`id`);

--
-- Constraints for table `parking_spot_floors`
--
ALTER TABLE `parking_spot_floors`
  ADD CONSTRAINT `parking_spot_floors_ibfk_1` FOREIGN KEY (`parking_spot_id`) REFERENCES `parking_spots` (`id`);

--
-- Constraints for table `parking_spot_zones`
--
ALTER TABLE `parking_spot_zones`
  ADD CONSTRAINT `parking_spot_zones_ibfk_1` FOREIGN KEY (`floor_id`) REFERENCES `parking_spot_floors` (`id`),
  ADD CONSTRAINT `parking_spot_zones_ibfk_2` FOREIGN KEY (`parking_spots_id`) REFERENCES `parking_spots` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
