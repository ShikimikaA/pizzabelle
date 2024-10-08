-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 25, 2024 at 06:13 PM
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
-- Database: `pizza_belle`
--

-- --------------------------------------------------------

--
-- Table structure for table `food_items`
--

CREATE TABLE `food_items` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `image_url` varchar(255) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `food_items`
--

INSERT INTO `food_items` (`id`, `name`, `image_url`, `price`, `created_at`) VALUES
(1, 'Manuk', 'uploads/66f423d543d01.jpg', 10000.00, '2024-09-25 14:53:09'),
(2, 'Puting Manok', 'uploads/66f423ed41365.jpg', 10000.00, '2024-09-25 14:53:33'),
(3, 'Chowfan', 'uploads/66f4240376034.jpg', 1234.00, '2024-09-25 14:53:55'),
(4, 'Chowfan', 'uploads/66f4241695e79.jpg', 1234.00, '2024-09-25 14:54:14'),
(5, 'Manok na may tao', 'uploads/66f42424d50e8.jpg', 1234.00, '2024-09-25 14:54:28'),
(6, 'Basta manok', 'uploads/66f4243135fc4.jpg', 1234.00, '2024-09-25 14:54:41');

-- --------------------------------------------------------

--
-- Table structure for table `inventory`
--

CREATE TABLE `inventory` (
  `id` int(11) NOT NULL,
  `item_name` varchar(100) NOT NULL,
  `amount` int(11) NOT NULL,
  `expiration_date` date NOT NULL,
  `category` enum('Freezer','Chiller','Stockroom') NOT NULL,
  `image_url` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `inventory`
--

INSERT INTO `inventory` (`id`, `item_name`, `amount`, `expiration_date`, `category`, `image_url`, `created_at`) VALUES
(1, 'Seth', 0, '2024-09-26', 'Freezer', 'uploads/66f40900ef911.jpg', '2024-09-25 14:56:39'),
(2, 'plastic', 120392, '2024-09-18', 'Stockroom', 'uploads/papel.jpg', '2024-09-25 15:27:11'),
(3, 'Sipon', 12342, '2024-09-26', 'Freezer', 'uploads/sipon.jpg', '2024-09-25 15:27:41'),
(4, 'Ketchup', 242, '2024-09-27', 'Freezer', 'uploads/mayo.jpg', '2024-09-25 15:28:29'),
(5, 'Longganisa', 1232, '2024-10-03', 'Freezer', 'uploads/4_a83b3480-2201-440f-b3a2-5db3e535dacc_600x600.jpg', '2024-09-25 15:30:26'),
(6, 'Longganisa Maker', 3, '2024-10-11', 'Stockroom', 'uploads/Thumbnail_Blog_32_1600x.jpg', '2024-09-25 15:31:32');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('customer','manager') NOT NULL,
  `firstname` varchar(50) DEFAULT NULL,
  `lastname` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `email`, `password`, `role`, `firstname`, `lastname`) VALUES
(1, 'ricabulanadi@gmail.com', '$2y$10$UTz29eKM5A6rUevLnLFqVeTt/ENK4AfFqU.HydVoj79HUwAfElctC', 'manager', 'Rica', 'Bulanadi'),
(2, 'baluyotsethtracy@gmail.com', '$2y$10$V5TrrMWcVIzqFieHoLBuk.MiR1q.IxMFswE2DPStpqZt9.gxE1FXu', 'customer', 'Seth', 'Baluyot'),
(3, 'testing@gmail.com', '$2y$10$osB9dHHNRNPLKu7XW5CsfeabuLQfRACc0znwN10q5DtJiyLut98rO', 'customer', 'testing', 'testing');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `food_items`
--
ALTER TABLE `food_items`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `inventory`
--
ALTER TABLE `inventory`
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
-- AUTO_INCREMENT for table `food_items`
--
ALTER TABLE `food_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `inventory`
--
ALTER TABLE `inventory`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
