-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 02, 2024 at 08:32 AM
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
-- Database: `cafebristo`
--

-- --------------------------------------------------------

--
-- Table structure for table `category`
--

CREATE TABLE `category` (
  `id` int(11) NOT NULL,
  `catagories` enum('hot','cold','light_meal','alternative_drink') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `category`
--

INSERT INTO `category` (`id`, `catagories`) VALUES
(1, 'hot'),
(2, 'cold'),
(3, 'light_meal'),
(4, 'alternative_drink');
(4, 'alternative_drink');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `order_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `fullname` varchar(255) NOT NULL,
  `phone_number` varchar(20) NOT NULL,
  `product_id` int(11) NOT NULL,
  `product_title` varchar(255) NOT NULL,
  `quantity` int(11) NOT NULL DEFAULT 1,
  `total` decimal(10,2) NOT NULL,
  `order_date` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `product`
--

CREATE TABLE `product` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `categories` enum('hot','cold','light_meal','alternative_drink') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `product`
--

INSERT INTO `product` (`id`, `title`, `description`, `price`, `image`, `categories`) VALUES
(2, 'Americano', 'hot drink coffee pore into hot water', 150.00, 'https://assets.beanbox.com/blog_images/AB7ud4YSE6nmOX0iGlgA.jpeg', 'hot'),
(4, 'ice latte', 'iced coffee or cold version of latte', 400.00, 'uploads/iced latte.jpg', 'cold'),
(6, 'lungo', 'coffee', 400.00, 'uploads/cappuccino.jpg', 'hot'),
(7, 'smoothie', 'smoothie are made up with fruites', 200.00, 'https://static.toiimg.com/photo/65224830.cms', 'alternative_drink'),
(8, 'espresso', 'pure form of coffee', 100.00, 'https://cdn.rizopouloscoffee.gr/www/rizopoulos-coffee-espresso.jpg', 'hot'),
(14, 'mocha', 'mixed of coffee with chocolate', 400.00, 'uploads/cappuccino.jpg', 'hot'),
(15, 'momo', 'Nepali cuisine made up of Fine flour AND Meat  ', 100.00, 'https://www.dreamlandgoldresort.com/images/blog/gjMCS-chicken-momos-with-tomato-achar-46671-1.jpg', 'light_meal');

-- --------------------------------------------------------

--
-- Table structure for table `user_reg`
--

CREATE TABLE `user_reg` (
  `id` int(11) NOT NULL,
  `fullname` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(15) NOT NULL,
  `password` varchar(255) NOT NULL,
  `privilege` enum('admin','user','guest') DEFAULT 'user'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user_reg`
--

INSERT INTO `user_reg` (`id`, `fullname`, `email`, `phone`, `password`, `privilege`) VALUES
(2, 'Admin', 'rktamang284@gmail.com', '9851331284', '$2y$10$jPrdb/ATNsC4Vgwzxm3u1uc4ojHv0jyNtA459/6E4SaVXSRDRcl3u', 'admin'),
(4, 'Lama', 'lama123@gmail.com', '9851227069', '$2y$10$SGaMb8EUOygPWvIC6t3VHO.LMIQXo9gxr6.Aa3ps.6ZVC6PYDdAhC', 'user');

-- --------------------------------------------------------

--
-- Table structure for table `user_valid`
--

CREATE TABLE `user_valid` (
  `id` int(11) NOT NULL,
  `phone_number` varchar(15) NOT NULL,
  `password` varchar(255) NOT NULL,
  `privilege` enum('admin','user','guest') DEFAULT 'user'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user_valid`
--

INSERT INTO `user_valid` (`id`, `phone_number`, `password`, `privilege`) VALUES
(2, '9851331284', '$2y$10$PIpYAHHIoRUbsmBqqhzVrOAgfgpeLwVi4HByw9ezddyM4F8GtAOUm', 'admin'),
(4, '9851227069', '$2y$10$SGaMb8EUOygPWvIC6t3VHO.LMIQXo9gxr6.Aa3ps.6ZVC6PYDdAhC', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `category`
--
ALTER TABLE `category`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`order_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `product`
--
ALTER TABLE `product`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user_reg`
--
ALTER TABLE `user_reg`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `phone` (`phone`);

--
-- Indexes for table `user_valid`
--
ALTER TABLE `user_valid`
  ADD PRIMARY KEY (`id`),
  ADD KEY `phone_number` (`phone_number`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `category`
--
ALTER TABLE `category`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `order_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `product`
--
ALTER TABLE `product`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `user_reg`
--
ALTER TABLE `user_reg`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `user_valid`
--
ALTER TABLE `user_valid`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user_reg` (`id`),
  ADD CONSTRAINT `orders_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `product` (`id`);

--
-- Constraints for table `user_valid`
--
ALTER TABLE `user_valid`
  ADD CONSTRAINT `user_valid_ibfk_1` FOREIGN KEY (`phone_number`) REFERENCES `user_reg` (`phone`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
