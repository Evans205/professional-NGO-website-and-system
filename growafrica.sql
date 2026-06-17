-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 16, 2026 at 08:52 PM
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
-- Database: `growafrica`
--

-- --------------------------------------------------------

--
-- Table structure for table `posts`
--

CREATE TABLE `posts` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `category` enum('news','story','event') NOT NULL,
  `content` text NOT NULL,
  `images` text NOT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `posts`
--

INSERT INTO `posts` (`id`, `title`, `category`, `content`, `images`, `created_at`) VALUES
(1, 'Meet John: From Dropout to Avocado Farmer', 'story', 'John used our training program to start a thriving avocado export business. He now employs 5 other youths. [Full story here...]', '[\"uploads/sample1.jpg\"]', '2026-06-16 15:17:15'),
(2, 'testing title', 'story', 'storystorystorystorystorystorystorystorystorystorystorystorystorystorystorystorystorystorystorystorystorystorystoryvvvvvvstorystorystorystorystorystorystorystorystorystorystorystorystorystorystorystorystory', '[\"uploads\\/6a3140824ab39.jpg\",\"uploads\\/6a3140824ce65.jpeg\",\"uploads\\/6a3140824d4ee.jpeg\",\"uploads\\/6a3140824dbf9.jpeg\",\"uploads\\/6a3140824e0c9.jpeg\"]', '2026-06-16 15:24:34'),
(3, 'title title title title title title title title title title title title title title title title title ', 'story', 'chickschicks chicks chicks chicks chicks chicks chicks chicks chicks chicks chicks chicks chicks chicks chicks chicks chicks chicks chicks chicks chicks chicks chicks chicks chicks chicks chicks chicks chicks chicks chicks chicks chicks chicks chicks chicks chicks chicks chicks chicks chicks chicks chicks chicks chicks chicks chicks chicks chicks chicks chicks chicks chicks chicks chicks chicks chicks chicks chicks chicks chicks chicks chicks chicks chicks chicks chicks chicks chicks chicks chicks chicks chicks chicks chicks chicks chicks chicks ', '[\"uploads\\/6a31440eee3c3.mp4\"]', '2026-06-16 15:39:42');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `posts`
--
ALTER TABLE `posts`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `posts`
--
ALTER TABLE `posts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
