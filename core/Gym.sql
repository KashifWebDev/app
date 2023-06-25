-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 25, 2023 at 08:36 AM
-- Server version: 10.4.27-MariaDB
-- PHP Version: 8.2.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `test1`
--

-- --------------------------------------------------------

--
-- Table structure for table `gyms`
--

CREATE TABLE `gyms` (
  `id` int(11) NOT NULL,
  `user_id` int(10) NOT NULL,
  `name` varchar(100) NOT NULL,
  `sessions` varchar(50) NOT NULL,
  `gender` varchar(20) NOT NULL,
  `address` varchar(200) NOT NULL,
  `fees` int(100) NOT NULL,
  `lat` decimal(9,6) DEFAULT NULL,
  `loong` decimal(9,6) DEFAULT NULL,
  `img` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `gyms`
--

INSERT INTO `gyms` (`id`, `user_id`, `name`, `sessions`, `gender`, `address`, `fees`, `lat`, `loong`, `img`) VALUES
(1, 1, 'First Gym', 'Group', 'Male', 'Addddrrreesss 123', 0, '12.000000', '11.000000', '64856a146dd60_zoom-logo.png'),
(2, 1, 'First Gym', 'Solo', 'Female', 'Second Gym Address', 0, '5.646500', '73.654400', '64856ad24c8e0_skype-logo-vector-icon-template-clipart-download-0.png'),
(3, 1, 'First Gym', 'Solo', 'Female', 'Second Gym Address', 0, '5.646500', '73.654400', 'default.jpg'),
(4, 1, 'First Gym', 'Solo', 'Female', 'Second Gym Address', 0, '5.646500', '73.654400', 'default.jpg'),
(5, 1, 'First Gym', 'Solo', 'Female', 'Second Gym Address', 0, '5.646500', '73.654400', '6485a5d047f26_Vector.jpg'),
(6, 1, 'First Gym', 'Solo', 'Female', 'Second Gym Address', 0, '999.999999', '999.999999', '6485a741946fa_Vector.jpg'),
(8, 1, 'First Gym', 'Solo', 'Female', 'Second Gym Address', 0, '5.646500', '73.654400', 'default.jpg'),
(9, 1, 'Second Gym', 'Solo', 'Female', 'Second Gym Address', 0, '5.646500', '73.654400', 'default.jpg'),
(11, 26, 'new.gym.my', 'Group', 'Male', 'Punjab, C63H+M7C, Bhobtian, Lahore', 100, '31.404302', '74.228131', '6485b68211e94_scaled_licensed-image.jpg'),
(12, 1, 'First Gym1', 'Solo', 'Female', 'Second Gym Address', 45, '5.646500', '73.654400', 'default.jpg'),
(14, 1, 'RWP GYmn', 'Solo', 'Female', 'RWP Gym', 60, '33.560686', '73.011593', 'default.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `userType` enum('User','Trainer') NOT NULL,
  `fullName` varchar(50) NOT NULL,
  `email` varchar(50) NOT NULL,
  `phone` varchar(60) NOT NULL,
  `address` varchar(200) NOT NULL,
  `password` varchar(50) NOT NULL,
  `verified` tinyint(1) NOT NULL DEFAULT 0,
  `lat` decimal(9,6) DEFAULT NULL,
  `loong` decimal(9,6) DEFAULT NULL,
  `verification_code` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `userType`, `fullName`, `email`, `phone`, `address`, `password`, `verified`, `lat`, `loong`, `verification_code`) VALUES
(1, 'User', 'Test User', 'test@test.com', '123345567', 'H#34, Street 5', 'test@test.com', 0, NULL, NULL, 4295),
(3, 'User', 'John Doe', 'test@test.com1', '+923115474893', 'ABC 123', 'test@test.com', 0, NULL, NULL, NULL),
(4, 'User', 'John Doe', 'test@test.com2', '+923115474893', 'ABC 123', 'test@test.com', 0, NULL, NULL, NULL),
(5, 'User', 'John Doe', 'test@test.com3', '+923115474893', 'ABC 123', 'test@test.com', 0, NULL, NULL, NULL),
(7, 'User', 'John Doe', 'test@test.com0', '+923115474893', 'ABC 123', 'test@test.com', 0, '11.232300', '-5.234000', NULL),
(8, 'User', 'John Doe', 'test1@test.com0', '+923115474893', 'ABC 123', 'test1@test.com', 0, '11.232300', '-5.234000', NULL),
(9, 'User', 'John Doe', 'test@test.com12', '+923115474893', 'ABC 123', 'test@test.com', 0, '11.232300', '-5.234000', NULL),
(10, 'User', 'Hamza', 'test1@gmail.com', '03024047554', 'Punjab, C63H+M7C, Bhobtian, Lahore', '123456789', 0, '31.404302', '74.228135', 6981),
(11, 'User', 'Hamza', 'test8@gmail.com', '123456789', 'Punjab, C63H+M7C, Bhobtian, Lahore', '123456789', 0, '31.404302', '74.228136', 5743),
(12, 'User', 'Hamza', 'testapp@gmail.com', '1234567890', 'Punjab, C63H+M7C, Bhobtian, Lahore', '123456789', 0, '31.404302', '74.228131', 1231),
(13, 'User', 'Hamza', 'hamzasabir8486@gmail.com', '1234567890', 'Punjab, C63H+M7C, Bhobtian, Lahore', '123456789', 1, '31.404302', '74.228135', 2826),
(23, 'User', 'John Doe', 'admin@kashifali.me', '+923115474893', 'ABC 123', 'admin@kashifali.me', 0, '11.232300', '-5.234000', 1315),
(24, 'User', 'John Doe', 'kmalik748@gmail.com', '+923115474893', 'ABC 123', 'admin@kashifali.me', 0, '11.232300', '-5.234000', 2365),
(25, 'User', 'John Doe', '', '+923115474893', 'ABC 123', 'pass123', 0, '11.232300', '-5.234000', 2279),
(26, '', 'Hamza', 'hamzauos7866@gmail.com', '1234567890', 'Punjab, C63H+M7C, Bhobtian, Lahore', '123456789', 1, '31.404300', '74.228131', 9978);

-- --------------------------------------------------------

--
-- Table structure for table `user_payments`
--

CREATE TABLE `user_payments` (
  `payment_id` int(11) NOT NULL,
  `date` date NOT NULL,
  `payment_response` longtext DEFAULT NULL,
  `amount` int(100) NOT NULL,
  `user_id` int(11) NOT NULL,
  `gym_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user_payments`
--

INSERT INTO `user_payments` (`payment_id`, `date`, `payment_response`, `amount`, `user_id`, `gym_id`) VALUES
(1, '2023-06-14', '21', 0, 1, 14),
(2, '2023-06-28', '34', 0, 1, 11),
(3, '2023-06-26', '234', 0, 1, 12),
(5, '2023-06-25', 'payment_Response_FROM_API', 80, 3, 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `gyms`
--
ALTER TABLE `gyms`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user_payments`
--
ALTER TABLE `user_payments`
  ADD PRIMARY KEY (`payment_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `gym_id` (`gym_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `gyms`
--
ALTER TABLE `gyms`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `user_payments`
--
ALTER TABLE `user_payments`
  MODIFY `payment_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `user_payments`
--
ALTER TABLE `user_payments`
  ADD CONSTRAINT `user_payments_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `user_payments_ibfk_2` FOREIGN KEY (`gym_id`) REFERENCES `gyms` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
