-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Jul 28, 2023 at 12:12 PM
-- Server version: 8.0.31
-- PHP Version: 8.0.26

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `gymapp`
--

-- --------------------------------------------------------

--
-- Table structure for table `gyms`
--

DROP TABLE IF EXISTS `gyms`;
CREATE TABLE IF NOT EXISTS `gyms` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `name` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `sessions` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `gender` varchar(20) COLLATE utf8mb4_general_ci NOT NULL,
  `address` varchar(200) COLLATE utf8mb4_general_ci NOT NULL,
  `fees` int NOT NULL,
  `lat` decimal(9,6) DEFAULT NULL,
  `loong` decimal(9,6) DEFAULT NULL,
  `img` varchar(200) COLLATE utf8mb4_general_ci NOT NULL,
  `days` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `types` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `startTime` time NOT NULL,
  `endTime` time NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `gyms`
--

INSERT INTO `gyms` (`id`, `user_id`, `name`, `sessions`, `gender`, `address`, `fees`, `lat`, `loong`, `img`, `days`, `types`, `startTime`, `endTime`) VALUES
(1, 1, 'First Gym', 'Group', 'Male', 'Addddrrreesss 123', 0, '12.000000', '11.000000', '64856a146dd60_zoom-logo.png', 'Mon,Tue,Wed', NULL, '08:00:00', '18:00:18'),
(2, 1, 'Wow Gym', 'Solo', 'Female', 'Second Gym Address', 0, '5.646500', '73.654400', '64856ad24c8e0_skype-logo-vector-icon-template-clipart-download-0.png', 'Mon,Tue,Wed,Thu,Fri', NULL, '14:00:00', '22:00:00'),
(9, 1, 'Second Gym', 'Solo', 'Female', 'Second Gym Address', 0, '5.646500', '73.654400', 'default.jpg', '', NULL, '00:00:00', '00:00:00'),
(11, 26, 'new.gym.my', 'Group', 'Male', 'Punjab, C63H+M7C, Bhobtian, Lahore', 100, '31.404302', '74.228131', '6485b68211e94_scaled_licensed-image.jpg', '', 'muscle, biceps', '00:00:00', '00:00:00'),
(12, 1, 'First Gym1', 'Solo', 'Female', 'Second Gym Address', 45, '5.646500', '73.654400', 'default.jpg', '', NULL, '00:00:00', '00:00:00'),
(14, 1, 'RWP GYmn', 'Solo', 'Female', 'RWP Gym', 60, '33.560686', '73.011593', 'default.jpg', '', NULL, '00:00:00', '00:00:00'),
(15, 1, 'Thirtd Gym', 'Solo', 'Female', 'Second Gym Address', 60, '5.646500', '73.654400', 'default.jpg', 'Mon,Thu,Sat', NULL, '16:00:00', '23:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `ratings`
--

DROP TABLE IF EXISTS `ratings`;
CREATE TABLE IF NOT EXISTS `ratings` (
  `id` int NOT NULL AUTO_INCREMENT,
  `gym_id` int NOT NULL,
  `user_id` int NOT NULL,
  `rating` int NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `ratings`
--

INSERT INTO `ratings` (`id`, `gym_id`, `user_id`, `rating`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 2, '2023-07-18 18:20:23', '2023-07-18 18:20:23'),
(2, 1, 2, 5, '2023-07-18 18:23:42', '2023-07-18 18:23:42'),
(3, 1, 1, 2, '2023-07-18 19:58:32', '2023-07-18 19:58:32'),
(4, 1, 3, 2, '2023-07-18 19:58:45', '2023-07-18 19:58:45'),
(5, 1, 3, 2, '2023-07-18 20:00:19', '2023-07-18 20:00:19');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` int NOT NULL AUTO_INCREMENT,
  `userType` enum('User','Trainer') COLLATE utf8mb4_general_ci NOT NULL,
  `fullName` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `email` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `phone` varchar(60) COLLATE utf8mb4_general_ci NOT NULL,
  `address` varchar(200) COLLATE utf8mb4_general_ci NOT NULL,
  `password` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `img` varchar(100) COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'default.jpg',
  `verified` tinyint(1) NOT NULL DEFAULT '0',
  `lat` decimal(9,6) DEFAULT NULL,
  `loong` decimal(9,6) DEFAULT NULL,
  `verification_code` int DEFAULT NULL,
  `age` int DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=27 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `userType`, `fullName`, `email`, `phone`, `address`, `password`, `img`, `verified`, `lat`, `loong`, `verification_code`, `age`) VALUES
(1, 'User', 'Test User', 'test@test.com', '123345567', 'H#34, Street 5', 'test@test.com', '64a2acc341ce6_VoiceThreadLogo.png', 0, NULL, NULL, 4295, 10),
(3, 'User', 'John Doe', 'test@test.com1', '+923115474893', 'ABC 123', 'test@test.com', '64a68f9a5d4cc_100px.png', 0, NULL, NULL, NULL, NULL),
(4, 'User', 'John Doe', 'test@test.com2', '+923115474893', 'ABC 123', 'test@test.com', 'default.jpg', 0, NULL, NULL, NULL, NULL),
(5, 'User', 'John Doe', 'test@test.com3', '+923115474893', 'ABC 123', 'test@test.com', 'default.jpg', 0, NULL, NULL, NULL, NULL),
(7, 'User', 'John Doe', 'test@test.com0', '+923115474893', 'ABC 123', 'test@test.com', 'default.jpg', 0, '11.232300', '-5.234000', NULL, NULL),
(8, 'User', 'John Doe', 'test1@test.com0', '+923115474893', 'ABC 123', 'test1@test.com', 'default.jpg', 0, '11.232300', '-5.234000', NULL, NULL),
(9, 'User', 'John Doe', 'test@test.com12', '+923115474893', 'ABC 123', 'test@test.com', 'default.jpg', 0, '11.232300', '-5.234000', NULL, NULL),
(10, 'User', 'Hamza', 'test1@gmail.com', '03024047554', 'Punjab, C63H+M7C, Bhobtian, Lahore', '123456789', 'default.jpg', 0, '31.404302', '74.228135', 6981, NULL),
(11, 'User', 'Hamza', 'test8@gmail.com', '123456789', 'Punjab, C63H+M7C, Bhobtian, Lahore', '123456789', 'default.jpg', 0, '31.404302', '74.228136', 5743, NULL),
(12, 'User', 'Hamza', 'testapp@gmail.com', '1234567890', 'Punjab, C63H+M7C, Bhobtian, Lahore', '123456789', 'default.jpg', 0, '31.404302', '74.228131', 1231, NULL),
(13, 'User', 'Hamza', 'hamzasabir8486@gmail.com', '1234567890', 'Punjab, C63H+M7C, Bhobtian, Lahore', '123456789', 'default.jpg', 1, '31.404302', '74.228135', 2826, NULL),
(23, 'User', 'John Doe', 'admin@kashifali.me', '+923115474893', 'ABC 123', 'admin@kashifali.me', 'default.jpg', 0, '11.232300', '-5.234000', 1315, NULL),
(24, 'User', 'John Doe', 'kmalik748@gmail.com', '+923115474893', 'ABC 123', 'admin@kashifali.me', 'default.jpg', 0, '11.232300', '-5.234000', 2365, NULL),
(25, 'User', 'John Doe', '', '+923115474893', 'ABC 123', 'pass123', 'default.jpg', 0, '11.232300', '-5.234000', 2279, NULL),
(26, '', 'Hamza', 'hamzauos7866@gmail.com', '1234567890', 'Punjab, C63H+M7C, Bhobtian, Lahore', '123456789', 'default.jpg', 1, '31.404300', '74.228131', 9978, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `user_payments`
--

DROP TABLE IF EXISTS `user_payments`;
CREATE TABLE IF NOT EXISTS `user_payments` (
  `payment_id` int NOT NULL AUTO_INCREMENT,
  `date` date NOT NULL,
  `payment_response` longtext COLLATE utf8mb4_general_ci,
  `amount` int NOT NULL,
  `user_id` int NOT NULL,
  `gym_id` int NOT NULL,
  PRIMARY KEY (`payment_id`),
  KEY `user_id` (`user_id`),
  KEY `gym_id` (`gym_id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user_payments`
--

INSERT INTO `user_payments` (`payment_id`, `date`, `payment_response`, `amount`, `user_id`, `gym_id`) VALUES
(1, '2023-06-14', '21', 0, 1, 14),
(2, '2023-06-28', '34', 0, 1, 1),
(3, '2023-06-26', '234', 0, 1, 12),
(5, '2023-06-25', 'payment_Response_FROM_API', 80, 3, 1);

-- --------------------------------------------------------

--
-- Table structure for table `videos`
--

DROP TABLE IF EXISTS `videos`;
CREATE TABLE IF NOT EXISTS `videos` (
  `id` int NOT NULL AUTO_INCREMENT,
  `filename` varchar(255) NOT NULL,
  `gym_id` int NOT NULL,
  `upload_date` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `videos`
--

INSERT INTO `videos` (`id`, `filename`, `gym_id`, `upload_date`) VALUES
(3, '64b6d41c7469f.torrent', 1, '2023-07-20 12:58:37'),
(2, '64b6d41c7469f.torrent', 1, '2023-07-18 18:04:12');

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
