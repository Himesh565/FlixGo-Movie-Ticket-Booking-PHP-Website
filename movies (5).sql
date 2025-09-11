-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 19, 2025 at 09:50 AM
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
-- Database: `movies`
--

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`id`, `name`, `email`, `password`, `created_at`) VALUES
(1, 'Parth', 'parth123@gmail.com', 'parth123', '2024-10-23 06:39:32');

-- --------------------------------------------------------

--
-- Table structure for table `feedback`
--

CREATE TABLE `feedback` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `rating` int(11) NOT NULL,
  `feedback` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `feedback`
--

INSERT INTO `feedback` (`id`, `user_id`, `rating`, `feedback`, `created_at`) VALUES
(1, 30, 5, 'good', '2024-09-20 18:24:05'),
(2, 34, 1, 'Bad', '2024-09-28 05:25:50'),
(3, 30, 5, 'Best', '2024-10-11 05:09:42'),
(4, 35, 5, 'Amazing..', '2024-10-16 06:04:59');

-- --------------------------------------------------------

--
-- Table structure for table `movies`
--

CREATE TABLE `movies` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `poster` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `theater_id` int(11) NOT NULL,
  `release_date` date DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT 1,
  `trailer_link` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `movies`
--

INSERT INTO `movies` (`id`, `name`, `poster`, `description`, `theater_id`, `release_date`, `is_active`, `trailer_link`) VALUES
(11, 'GOAT', 'uploads/et00401441-jhkvdzzhks-portrait.jpg', 'Drama/Acion', 2, '0000-00-00', 0, 'https://www.youtube.com/embed/Uf8rt635LLo?si=hRNkhnnoYCYAvUcT'),
(12, 'Tumbbad', 'uploads/et00079092-rqkbvsbjxp-portrait.jpg', 'this movie is best movie', 5, '0000-00-00', 0, 'https://www.youtube.com/embed/O9CaB4J4VEI?si=ws5Yd1MROcFZ2gdr'),
(14, 'Joker', 'uploads/et00352820-rauglgcvkc-portrait.jpg', 'Action/Drama/Thriller', 2, '0000-00-00', 0, 'https://www.youtube.com/embed/_OKAwz2MsJs?si=116BUTzLdkwOzFXA'),
(15, 'Joker', 'uploads/et00352820-rauglgcvkc-portrait.jpg', 'Action/Drama/Thriller', 5, '0000-00-00', 1, 'https://www.youtube.com/embed/_OKAwz2MsJs?si=116BUTzLdkwOzFXA'),
(16, 'Tumbbad', 'uploads/et00079092-rqkbvsbjxp-portrait.jpg', 'Horror/Fantasy/', 2, '0000-00-00', 1, 'https://www.youtube.com/embed/O9CaB4J4VEI?si=ws5Yd1MROcFZ2gdr'),
(17, 'GOAT', 'uploads/et00401441-jhkvdzzhks-portrait.jpg', 'Action/Drama', 5, '0000-00-00', 1, 'https://www.youtube.com/embed/Uf8rt635LLo?si=hRNkhnnoYCYAvUcT'),
(18, 'Devra', 'uploads/et00310216-tluebxpafx-portrait.jpg', 'Action/Thriller', 2, '0000-00-00', 1, 'https://www.youtube.com/embed/NcCYq3bvlJM?si=HLgktyxULQHt910A'),
(24, 'Devra', 'uploads/et00310216-tluebxpafx-portrait.jpg', 'Action', 5, '2024-10-03', 1, 'https://www.youtube.com/embed/Xg0vBOxV2to?si=xfFO3UaC8STey_Jg'),
(25, 'Joker', 'uploads/et00352820-rauglgcvkc-portrait.jpg', 'action', 5, '2025-03-22', 1, 'https://www.youtube.com/embed/G62HrubdD6o?si=CEmg2AneRBji-9mg');

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

CREATE TABLE `payments` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `screen_id` int(11) NOT NULL,
  `seats` text NOT NULL,
  `total_price` decimal(10,2) NOT NULL,
  `payment_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `payments`
--

INSERT INTO `payments` (`id`, `user_id`, `screen_id`, `seats`, `total_price`, `payment_date`) VALUES
(1, 30, 9, '26,27', '198.00', '2025-03-12 06:27:18'),
(2, 30, 9, '65,66', '198.00', '2025-03-12 06:27:46'),
(3, 30, 9, '49,48', '198.00', '2025-03-12 06:30:08'),
(4, 30, 9, '49,48', '198.00', '2025-03-12 06:33:12'),
(5, 30, 9, '49,48', '198.00', '2025-03-12 06:45:34'),
(6, 30, 9, '49,48', '198.00', '2025-03-12 06:50:37'),
(7, 30, 9, '49,48', '198.00', '2025-03-12 06:52:50'),
(8, 30, 9, '14,15', '198.00', '2025-03-12 07:01:55'),
(9, 30, 9, '14,15', '198.00', '2025-03-12 07:05:13'),
(10, 30, 9, '14,15', '198.00', '2025-03-12 07:05:50'),
(11, 30, 9, '14,15', '198.00', '2025-03-12 07:06:19'),
(12, 30, 9, '14,15', '198.00', '2025-03-12 07:08:12'),
(13, 30, 9, '14,15', '198.00', '2025-03-12 07:10:48'),
(14, 30, 9, '37,38', '198.00', '2025-03-12 07:15:26'),
(15, 30, 9, '37,38', '198.00', '2025-03-12 07:22:10'),
(16, 30, 9, '37,38', '198.00', '2025-03-12 07:39:00'),
(17, 44, 9, '34', '99.00', '2025-03-12 07:49:05'),
(18, 44, 9, '64,65', '198.00', '2025-03-12 07:56:46'),
(19, 45, 9, '65,66', '198.00', '2025-03-13 05:55:01'),
(20, 46, 12, '24,25', '250.00', '2025-03-16 07:36:54'),
(21, 46, 12, '44,45', '250.00', '2025-03-16 07:38:15'),
(22, 46, 12, '5,6', '250.00', '2025-03-16 07:51:37'),
(23, 30, 9, '66,67', '198.00', '2025-03-18 06:33:33'),
(24, 30, 12, '57,58,59', '375.00', '2025-03-19 05:13:44'),
(25, 30, 12, '57,58,59', '375.00', '2025-03-19 05:17:54'),
(26, 30, 9, '69,70', '198.00', '2025-03-19 05:31:10'),
(27, 30, 9, '19,20', '198.00', '2025-03-19 05:41:51'),
(28, 30, 9, '19,20', '198.00', '2025-03-19 05:44:45'),
(29, 30, 9, '19,20', '198.00', '2025-03-19 05:45:40'),
(30, 30, 9, '19,20', '198.00', '2025-03-19 05:46:31'),
(31, 30, 9, '19,20', '198.00', '2025-03-19 05:47:40'),
(32, 30, 9, '79,80', '198.00', '2025-03-19 05:57:58'),
(33, 30, 9, '14,15', '198.00', '2025-03-19 06:05:44'),
(34, 30, 9, '28,29', '198.00', '2025-03-19 06:06:35'),
(35, 30, 9, '48,49,50', '297.00', '2025-03-19 06:59:09'),
(36, 30, 9, '59,60', '198.00', '2025-03-19 07:12:43'),
(37, 30, 9, '8,9', '198.00', '2025-03-19 07:24:42'),
(38, 48, 11, '46,47', '240.00', '2025-03-19 08:02:16');

-- --------------------------------------------------------

--
-- Table structure for table `screens`
--

CREATE TABLE `screens` (
  `id` int(11) NOT NULL,
  `movie_id` int(11) DEFAULT NULL,
  `screen_number` int(11) DEFAULT NULL,
  `show_time` time DEFAULT NULL,
  `price` decimal(10,2) DEFAULT NULL,
  `is_active` tinyint(4) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `screens`
--

INSERT INTO `screens` (`id`, `movie_id`, `screen_number`, `show_time`, `price`, `is_active`) VALUES
(9, 18, 1, '15:00:00', '99.00', 1),
(10, 24, 1, '12:40:00', '199.00', 1),
(11, 17, 1, '09:30:00', '120.00', 1),
(12, 16, 1, '08:30:00', '125.00', 1),
(13, 15, 1, '09:30:00', '125.00', 1),
(14, 12, 1, '10:30:00', '180.00', 1),
(16, 15, 2, '16:32:00', '190.00', 1);

-- --------------------------------------------------------

--
-- Table structure for table `seat_selection`
--

CREATE TABLE `seat_selection` (
  `id` int(11) NOT NULL,
  `screen_id` int(11) NOT NULL,
  `seat_number` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `is_booked` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `seat_selection`
--

INSERT INTO `seat_selection` (`id`, `screen_id`, `seat_number`, `user_id`, `is_booked`) VALUES
(1, 1, 1, 30, 0),
(2, 1, 2, 30, 0),
(3, 1, 3, 30, 1),
(4, 1, 4, 30, 1),
(5, 1, 5, 30, 1),
(6, 1, 15, 30, 0),
(7, 2, 1, 30, 1),
(8, 2, 2, 32, 0),
(9, 2, 3, 32, 0),
(10, 2, 4, 30, 1),
(11, 1, 35, 30, 1),
(12, 1, 36, 30, 1),
(13, 1, 12, 30, 1),
(14, 1, 13, 30, 1),
(15, 1, 14, 30, 1),
(16, 1, 55, 33, 1),
(17, 1, 56, 33, 1),
(18, 1, 57, 33, 0),
(19, 3, 1, 33, 1),
(20, 3, 2, 33, 1),
(21, 3, 3, 33, 1),
(22, 3, 4, 33, 1),
(23, 3, 5, 33, 1),
(24, 3, 6, 33, 1),
(25, 3, 34, 31, 1),
(26, 3, 35, 31, 1),
(27, 3, 14, 31, 1),
(28, 3, 15, 31, 1),
(29, 3, 16, 31, 1),
(30, 3, 23, 31, 1),
(31, 3, 25, 31, 1),
(32, 3, 44, 31, 1),
(33, 3, 45, 31, 1),
(34, 3, 43, 31, 1),
(35, 3, 42, 31, 1),
(36, 2, 55, 34, 0),
(37, 1, 45, 30, 1),
(38, 1, 46, 30, 1),
(39, 1, 47, 30, 1),
(40, 1, 22, 30, 1),
(41, 1, 24, 30, 1),
(42, 7, 34, 30, 1),
(43, 7, 35, 30, 1),
(44, 8, 35, 30, 1),
(45, 8, 36, 30, 1),
(46, 9, 45, 30, 0),
(47, 9, 46, 30, 0),
(48, 9, 35, 30, 0),
(49, 9, 36, 30, 0),
(50, 9, 24, 30, 0),
(51, 9, 25, 30, 0),
(52, 9, 26, 30, 0),
(53, 9, 27, 30, 0),
(54, 9, 28, 30, 0),
(55, 9, 54, 30, 0),
(56, 9, 55, 30, 0),
(57, 9, 14, 30, 0),
(58, 9, 15, 30, 0),
(59, 10, 24, 30, 1),
(60, 10, 25, 30, 0),
(61, 9, 5, 30, 0),
(62, 9, 6, 30, 1),
(63, 10, 26, 30, 0),
(64, 12, 35, 37, 0),
(65, 12, 36, 37, 1),
(66, 12, 43, 30, 1),
(67, 12, 14, 30, 1),
(68, 12, 15, 30, 1),
(69, 12, 68, 38, 1),
(70, 12, 69, 38, 1),
(71, 11, 35, 38, 0),
(72, 11, 36, 38, 0),
(73, 9, 35, 30, 1),
(74, 9, 36, 30, 1),
(75, 9, 16, 30, 1),
(76, 9, 17, 30, 1),
(77, 12, 46, 30, 1),
(78, 12, 47, 30, 1),
(79, 12, 34, 30, 1),
(80, 12, 35, 30, 1),
(108, 9, 24, 30, 1),
(109, 9, 25, 30, 1),
(110, 9, 44, 30, 1),
(111, 9, 45, 30, 1),
(112, 15, 35, 30, 1),
(113, 15, 36, 30, 1),
(114, 13, 34, 30, 0),
(115, 13, 35, 30, 0),
(116, 9, 54, 30, 1),
(117, 9, 56, 30, 1),
(118, 9, 55, 30, 1),
(119, 15, 45, 30, 1),
(120, 15, 46, 30, 1),
(121, 9, 46, 30, 1),
(122, 9, 47, 30, 1),
(123, 12, 5, 46, 1),
(124, 12, 6, 46, 0),
(125, 9, 66, 30, 1),
(126, 9, 67, 30, 1),
(127, 12, 57, 30, 1),
(128, 12, 58, 30, 1),
(129, 12, 59, 30, 1),
(130, 12, 57, 30, 1),
(131, 12, 58, 30, 1),
(132, 12, 59, 30, 1),
(133, 9, 69, 30, 1),
(134, 9, 70, 30, 1),
(135, 9, 19, 30, 1),
(136, 9, 20, 30, 1),
(137, 9, 19, 30, 1),
(138, 9, 20, 30, 1),
(139, 9, 19, 30, 1),
(140, 9, 20, 30, 1),
(141, 9, 19, 30, 1),
(142, 9, 20, 30, 1),
(143, 9, 19, 30, 1),
(144, 9, 20, 30, 1),
(145, 9, 79, 30, 1),
(146, 9, 80, 30, 1),
(147, 9, 14, 30, 1),
(148, 9, 15, 30, 1),
(149, 9, 28, 30, 1),
(150, 9, 29, 30, 1),
(151, 9, 48, 30, 1),
(152, 9, 49, 30, 1),
(153, 9, 50, 30, 1),
(154, 9, 59, 30, 1),
(155, 9, 60, 30, 1),
(156, 9, 8, 30, 1),
(157, 9, 9, 30, 1),
(158, 11, 46, 48, 1),
(159, 11, 47, 48, 0);

-- --------------------------------------------------------

--
-- Table structure for table `theater`
--

CREATE TABLE `theater` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `poster` varchar(100) NOT NULL,
  `owner` varchar(50) NOT NULL,
  `mobileno` varchar(12) NOT NULL,
  `address` varchar(100) NOT NULL,
  `email` varchar(50) NOT NULL,
  `is_active` tinyint(4) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `theater`
--

INSERT INTO `theater` (`id`, `name`, `poster`, `owner`, `mobileno`, `address`, `email`, `is_active`) VALUES
(1, 'cineplax', 'uploads/cineplex.jpg', 'parth', '1234567890', 'Surat', 'parth@gmail.com', 0),
(2, 'PVR', 'uploads/Screenshot 2024-09-21 132309.png', 'parth', '9081219005', 'Surat', 'sahil@gmail.com', 1),
(5, 'RajHans', 'uploads/Screenshot 2024-09-21 131549.png', 'parth', '1234567890', 'Surat', 'parth@gmail.com', 1),
(6, 'cinepolos', 'uploads/images.jpg', 'parth', '1234567890', 'Surat', 'parth123@gmail.com', 1),
(10, 'Multiplax', 'uploads/movie-ticket-logo-template-design_20029-891.jpg', 'sahil', '1234567890', 'surat', 'sahil@gmail.com', 1);

-- --------------------------------------------------------

--
-- Table structure for table `userinfo`
--

CREATE TABLE `userinfo` (
  `id` int(11) NOT NULL,
  `firstName` varchar(255) NOT NULL,
  `lastName` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `userinfo`
--

INSERT INTO `userinfo` (`id`, `firstName`, `lastName`, `email`, `password`) VALUES
(21, 'Dhanu', 'G', 'dhanu@gmail.com', 'dhanu'),
(22, 'Sainath', 'Biradar', 'sainath@gmail.com', 'asdf'),
(23, 'Vivek', 'Galagali', 'vivek@gmail.com', 'vivek'),
(25, 'Anirudh', 'V', 'anirudh@gmail.com', 'asdf'),
(26, 'Sheldon', 'Shelly', 's@s.s', 'sss'),
(27, 'Iron', 'Man', 'iron@avengers.com', 'iron'),
(28, 'Amrit', 'Kumar', 'amrit@gmail.com', 'amrit'),
(29, 'Spider Man', 'Parker', 'spider@avengers.com', 'spider'),
(30, 'PARTH', 'Patel', 'parth@gmail.com', '12345'),
(31, 'sahil', 'patel', 'sahilpatel123@gmail.com', '123'),
(32, 'demo', 'demo', 'demo@gmail.com', '123'),
(33, 'rutvesh', 'upale', 'rutvesh@gmail.com', '123'),
(34, 'rutvesh', 'upale', 'parth1197@gmail.com', '12345678'),
(35, 'ppp', 'patel', 'demo1@gmail.com', '1'),
(36, 'aaa', 'aaa', 'aaa1@gmail.com', '123'),
(37, 'zzz', 'zzz', 'zzz@gmail.com', 'zzz'),
(38, 'aaa', 'aaa', 'aaa123@gmail.com', 'aaa'),
(39, 'dev', 'patel', 'pateldev123@gmail.com', '123'),
(40, 'ram', 'patel', 'ram123@gmail.com', 'ram123'),
(41, 'rajesh', 'patel', 'raju123@gmail.com', '12345'),
(42, 'raj', 'prajapati', 'raj123@gmail.com', 'raj123'),
(43, 'nill', 'jain', 'nill123@gmail.com', 'nill123'),
(44, 'lalu', 'patel', 'lalu123@gmail.com', 'lalu123'),
(45, 'vvv', 'vvv', 'vvv123@gmail.com', '123'),
(46, 'darshan', 'rathod', 'darshan123@gmail.com', '12345'),
(47, 'xyz', 'aa', 'xyz123@gmail.com', '12345'),
(48, 'xyz', 'xyz', 'xyz1234@gmail.com', '12345');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `feedback`
--
ALTER TABLE `feedback`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `movies`
--
ALTER TABLE `movies`
  ADD PRIMARY KEY (`id`),
  ADD KEY `theater_id` (`theater_id`);

--
-- Indexes for table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `screens`
--
ALTER TABLE `screens`
  ADD PRIMARY KEY (`id`),
  ADD KEY `movie_id` (`movie_id`);

--
-- Indexes for table `seat_selection`
--
ALTER TABLE `seat_selection`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `theater`
--
ALTER TABLE `theater`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `userinfo`
--
ALTER TABLE `userinfo`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `feedback`
--
ALTER TABLE `feedback`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `movies`
--
ALTER TABLE `movies`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;

--
-- AUTO_INCREMENT for table `screens`
--
ALTER TABLE `screens`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `seat_selection`
--
ALTER TABLE `seat_selection`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=160;

--
-- AUTO_INCREMENT for table `theater`
--
ALTER TABLE `theater`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `userinfo`
--
ALTER TABLE `userinfo`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=49;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `movies`
--
ALTER TABLE `movies`
  ADD CONSTRAINT `movies_ibfk_1` FOREIGN KEY (`theater_id`) REFERENCES `theater` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `screens`
--
ALTER TABLE `screens`
  ADD CONSTRAINT `screens_ibfk_1` FOREIGN KEY (`movie_id`) REFERENCES `movies` (`id`);

--
-- Constraints for table `seat_selection`
--
ALTER TABLE `seat_selection`
  ADD CONSTRAINT `seat_selection_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `userinfo` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
