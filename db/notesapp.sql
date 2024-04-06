-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 22, 2024 at 11:25 AM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.0.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `notesapp`
--

-- --------------------------------------------------------

--
-- Table structure for table `archive`
--

CREATE TABLE `archive` (
  `archive_id` int(11) NOT NULL,
  `note_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `archive`
--

INSERT INTO `archive` (`archive_id`, `note_id`) VALUES
(7, 17),
(3, 18);

-- --------------------------------------------------------

--
-- Table structure for table `deletednotes`
--

CREATE TABLE `deletednotes` (
  `deleted_note_id` int(11) NOT NULL,
  `note_id` int(11) DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  `scheduled_permanent_deletion` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `deletednotes`
--

INSERT INTO `deletednotes` (`deleted_note_id`, `note_id`, `deleted_at`, `scheduled_permanent_deletion`) VALUES
(1, 15, NULL, '2024-04-21 04:46:25'),
(2, 16, NULL, '2024-04-21 05:39:41');

-- --------------------------------------------------------

--
-- Table structure for table `notes`
--

CREATE TABLE `notes` (
  `note_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `text` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `notes`
--

INSERT INTO `notes` (`note_id`, `user_id`, `title`, `text`, `created_at`, `updated_at`) VALUES
(14, 4, 'add ', 'notes', '2024-03-20 12:37:09', '2024-03-21 09:36:25'),
(15, 3, 'Add the star for favourite', 'add favodwadw', '2024-03-20 12:38:01', '2024-03-21 08:22:47'),
(16, 3, 'time sample', 'time sam', '2024-03-21 07:05:02', '2024-03-21 07:12:19'),
(17, 3, 'sample', 'dwadad', '2024-03-21 08:22:16', '2024-03-21 08:22:16'),
(18, 3, 'dawda', 'wdawda', '2024-03-21 08:22:38', '2024-03-21 08:22:38'),
(19, 3, 'dawd', 'adwawda', '2024-03-21 08:22:43', '2024-03-21 08:22:43'),
(20, 3, 'display archive ', 'display archive or delete function\r\n', '2024-03-21 12:59:21', '2024-03-21 12:59:21'),
(21, 3, 'add delete confermation popup', 'confermation pop for delete retore buton and delted display', '2024-03-22 05:41:34', '2024-03-22 05:41:34');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(100) NOT NULL,
  `registration_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `first_name` varchar(50) DEFAULT NULL,
  `last_name` varchar(50) DEFAULT NULL,
  `gender` enum('Male','Female','Other') DEFAULT NULL,
  `birthdate` date DEFAULT NULL,
  `profile_image` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `username`, `email`, `password`, `registration_date`, `first_name`, `last_name`, `gender`, `birthdate`, `profile_image`) VALUES
(3, 'test', 'hl997678@gmail.com', '$2y$10$n8Hz2YMfAQAj5JQrbyUhI.DqhkEpHrYllW4x/rBE/GuEKmzm4CbAm', '2024-03-20 00:55:43', 'test', 'test', 'Male', '2000-10-12', 'profile/star (1).png'),
(4, 'shes87', 'sherwin_torrejas@yahoo.com', '$2y$10$MSBTjenqbyA/3B8jjer/y.kvuQSJulQbfQw/qgKXBJJncRo.Iej5i', '2024-03-20 00:57:19', 'sherwin', 'torrejas', 'Male', '2000-12-12', 'profile/plus.png');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `archive`
--
ALTER TABLE `archive`
  ADD PRIMARY KEY (`archive_id`),
  ADD KEY `note_id` (`note_id`);

--
-- Indexes for table `deletednotes`
--
ALTER TABLE `deletednotes`
  ADD PRIMARY KEY (`deleted_note_id`),
  ADD KEY `note_id` (`note_id`);

--
-- Indexes for table `notes`
--
ALTER TABLE `notes`
  ADD PRIMARY KEY (`note_id`),
  ADD KEY `notes_ibfk_1` (`user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `archive`
--
ALTER TABLE `archive`
  MODIFY `archive_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `deletednotes`
--
ALTER TABLE `deletednotes`
  MODIFY `deleted_note_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `notes`
--
ALTER TABLE `notes`
  MODIFY `note_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `archive`
--
ALTER TABLE `archive`
  ADD CONSTRAINT `archive_ibfk_1` FOREIGN KEY (`note_id`) REFERENCES `notes` (`note_id`);

--
-- Constraints for table `deletednotes`
--
ALTER TABLE `deletednotes`
  ADD CONSTRAINT `deletednotes_ibfk_1` FOREIGN KEY (`note_id`) REFERENCES `notes` (`note_id`);

--
-- Constraints for table `notes`
--
ALTER TABLE `notes`
  ADD CONSTRAINT `notes_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
