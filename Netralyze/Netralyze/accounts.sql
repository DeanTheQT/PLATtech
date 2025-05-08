-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 24, 2025 at 05:13 AM
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
-- Database: `accounts`
--

-- --------------------------------------------------------

--
-- Table structure for table `job_posts`
--

CREATE TABLE `job_posts` (
  `post_id` int(11) NOT NULL,
  `email` varchar(50) NOT NULL,
  `UserName` varchar(50) NOT NULL,
  `profile_image_path` varchar(255) DEFAULT NULL,
  `post_title` varchar(50) NOT NULL,
  `post_description` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `job_posts`
--

INSERT INTO `job_posts` (`post_id`, `email`, `UserName`, `profile_image_path`, `post_title`, `post_description`, `created_at`) VALUES
(12, 'Cadigoydean@gmail.com', 'Dean', NULL, 'dd', 'dd', '2025-04-24 02:43:59'),
(13, 'mdgcadigoy@tip.edu.ph', 'QT', NULL, 'dd', 'dd', '2025-04-24 02:45:30');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `name` varchar(50) NOT NULL,
  `email` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `profile_image_path` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`name`, `email`, `password`, `description`, `profile_image_path`) VALUES
('Dean', 'Cadigoydean@gmail.com', '$2y$10$kORp2Tva8t7xdQfsyrARkeAY1qj4bl6j2PiqGWYpYqjdg1CKo26Sy', '', NULL),
('QT', 'mdgcadigoy@tip.edu.ph', '$2y$10$BamkW4xPWVlE3pL7gqrH.eOfdk0geBCE8FItUaztqgwXSK/LWV94S', '', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `job_posts`
--
ALTER TABLE `job_posts`
  ADD PRIMARY KEY (`post_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `job_posts`
--
ALTER TABLE `job_posts`
  MODIFY `post_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
