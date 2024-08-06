CREATE Database `cafebristo`;
-- Create the categories table with category as the primary key
CREATE TABLE categories (
  category VARCHAR(255) NOT NULL,
  PRIMARY KEY (category)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `categories` (`category`) VALUES
('hot'),
('cold'),
('light_meal'),
('alternative_drink'),
('nepali_cuision');
-- Create the product table with a foreign key reference to the categories table
CREATE TABLE product (
  id INT(11) NOT NULL AUTO_INCREMENT,
  title VARCHAR(255) NOT NULL,
  description TEXT NOT NULL,
  price DECIMAL(10,2) NOT NULL,
  image VARCHAR(255) DEFAULT NULL,
  categories VARCHAR(255) NOT NULL,
  PRIMARY KEY (id),
  FOREIGN KEY (categories) REFERENCES categories(category) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


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


INSERT INTO `user_reg` (`id`, `fullname`, `email`, `phone`, `password`, `privilege`) VALUES
(2, 'Admin', 'rktamang284@gmail.com', '9851331284', '$2y$10$jPrdb/ATNsC4Vgwzxm3u1uc4ojHv0jyNtA459/6E4SaVXSRDRcl3u', 'admin'),
(4, 'Lama', 'lama123@gmail.com', '9851227069', '$2y$10$SGaMb8EUOygPWvIC6t3VHO.LMIQXo9gxr6.Aa3ps.6ZVC6PYDdAhC', 'user');

CREATE TABLE `user_valid` (
  `id` int(11) NOT NULL,
  `phone_number` varchar(15) NOT NULL,
  `password` varchar(255) NOT NULL,
  `privilege` enum('admin','user','guest') DEFAULT 'user'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `user_valid` (`id`, `phone_number`, `password`, `privilege`) VALUES
(2, '9851331284', '$2y$10$PIpYAHHIoRUbsmBqqhzVrOAgfgpeLwVi4HByw9ezddyM4F8GtAOUm', 'admin'),
(4, '9851227069', '$2y$10$SGaMb8EUOygPWvIC6t3VHO.LMIQXo9gxr6.Aa3ps.6ZVC6PYDdAhC', NULL);


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



ALTER TABLE `orders`
  ADD PRIMARY KEY (`order_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `product_id` (`product_id`);



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

ALTER TABLE `orders`
  MODIFY `order_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `product`
--

--
-- AUTO_INCREMENT for table `products`

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

ALTER TABLE `user_valid`
  ADD CONSTRAINT `user_valid_ibfk_1` FOREIGN KEY (`phone_number`) REFERENCES `user_reg` (`phone`) ON DELETE CASCADE;
