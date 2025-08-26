-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 26, 2025 at 05:33 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `foodmart`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `category_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `image_url` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`category_id`, `name`, `image_url`) VALUES
(1, 'Chefs_Special', 'Chefs_Special.png'),
(2, 'Chicken', 'Chicken.png'),
(3, 'Burger', 'Burger.png'),
(4, 'Seafood', 'Seafood.png'),
(5, 'Bakery', 'Bakery.png'),
(6, 'Beverage', 'Beverage.png');

-- --------------------------------------------------------

--
-- Table structure for table `customer`
--

CREATE TABLE `customer` (
  `customer_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `address` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `customer`
--

INSERT INTO `customer` (`customer_id`, `name`, `phone`, `address`) VALUES
(1, 'Alice Rahman', '01710000001', 'Banani, Dhaka'),
(2, 'Shahriar Hossain', '01710000002', 'Dhanmondi, Dhaka'),
(3, 'Nusrat Jahan', '01710000003', 'Uttara, Dhaka');

-- --------------------------------------------------------

--
-- Table structure for table `delivery`
--

CREATE TABLE `delivery` (
  `delivery_id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `dispatch_time` datetime DEFAULT NULL,
  `delivery_note` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `order_id` int(11) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `order_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `total_amount` decimal(10,2) NOT NULL DEFAULT 0.00,
  `order_status` varchar(50) DEFAULT 'Pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `order_item`
--

CREATE TABLE `order_item` (
  `order_item_id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `product_id` int(11) DEFAULT NULL,
  `product_name` varchar(100) NOT NULL,
  `quantity` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `product_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `image_url` varchar(255) DEFAULT NULL,
  `is_available` tinyint(1) NOT NULL DEFAULT 1,
  `category_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`product_id`, `name`, `description`, `price`, `image_url`, `is_available`, `category_id`) VALUES
(1, 'Premium Steak', 'A perfectly grilled, 10-ounce sirloin steak seasoned with our secret blend of herbs and spices. Served with roasted vegetables.', 2000.00, 'steak.jpeg', 0, 1),
(2, 'Shahi Kacchi Biryani', 'Tender mutton and potatoes slow-cooked with aromatic basmati rice. A timeless classic from Old Dhaka.', 750.00, 'kacchi_biryani.png', 1, 1),
(3, 'Lobster Thermidor', 'A creamy mixture of cooked lobster meat, egg yolks, and brandy, stuffed into a lobster shell. An ultimate luxury.', 3500.00, 'lobster_thermidor.jpeg', 1, 1),
(4, 'Mutton Rezala', 'A traditional Bengali curry made with mutton, yogurt, and a paste of poppy seeds and cashew nuts. Rich and aromatic.', 850.00, 'mutton_rezala.png', 1, 1),
(5, 'Shahi Beef Haleem', 'A rich and savory stew of beef, lentils, and wheat, slow-cooked for hours to perfection. Served with traditional garnishes.', 650.00, 'beef_haleem.png', 1, 1),
(6, 'Chicken Alfredo Pasta', 'Fettuccine pasta tossed in a rich, creamy parmesan sauce with grilled chicken breast and a hint of garlic.', 680.00, 'pasta_alfredo.png', 1, 2),
(7, 'Grilled Chicken Caesar Salad', 'Crisp romaine lettuce, grilled chicken, croutons, and parmesan cheese tossed in a classic Caesar dressing.', 600.00, 'caesar_salad.png', 1, 2),
(8, 'Tandoori Chicken (Half)', 'Chicken marinated in yogurt and spices, roasted in a tandoor oven until tender and juicy. A smoky delight.', 550.00, 'tandoori_chicken.png', 1, 2),
(9, 'Chicken Sizzling', 'Tender pieces of chicken cooked with bell peppers and onions in a spicy sauce, served on a hot sizzling plate.', 720.00, 'chicken_sizzling.png', 1, 2),
(10, 'Crispy Fried Chicken (8 pcs)', 'A bucket of our signature crispy and juicy fried chicken, perfect for sharing with friends and family.', 1100.00, 'fried_chicken.png', 1, 2),
(11, 'Classic Crispy Chicken Burger', 'A crispy fried chicken patty with fresh lettuce, tomatoes, and our signature sauce in a toasted brioche bun.', 550.00, 'chicken_burger.png', 1, 3),
(12, 'Gourmet Beef Burger', 'A thick, juicy beef patty with melted cheddar cheese, caramelized onions, and pickles on a soft potato bun.', 650.00, 'Gourmet_Beef_Burger.webp', 1, 3),
(13, 'Spicy Naga Burger', 'For the brave! A fiery beef or chicken patty infused with Naga chili sauce, topped with ghost pepper mayo.', 680.00, 'naga_burger.png', 1, 3),
(14, 'Crispy Fish Burger', 'A flaky, crumb-fried fish fillet with tangy tartar sauce and crisp lettuce, served in a soft bun.', 580.00, 'fish_burger.png', 1, 3),
(15, 'Hearty Veggie Burger', 'A delicious patty made from mixed vegetables and chickpeas, topped with fresh avocado and yogurt sauce.', 490.00, 'veggie_burger.png', 1, 3),
(16, 'Garlic Butter Prawns', 'Jumbo prawns sautéed in a fragrant garlic butter sauce, served with a lemon wedge and a side of crusty bread.', 1200.00, 'grilled_prawns.png', 1, 4),
(17, 'Classic Fish and Chips', 'Battered and fried dory fish served with a generous portion of thick-cut fries and tartar sauce.', 850.00, 'fish_chips.png', 1, 4),
(18, 'Grilled Salmon Steak', 'A healthy and delicious salmon steak, grilled to perfection and served with mashed potatoes and asparagus.', 1800.00, 'grilled_salmon.png', 1, 4),
(19, 'Spicy Crab Masala', 'Fresh crab cooked in a rich and spicy onion-tomato gravy. A true coastal delicacy.', 950.00, 'crab_masala.png', 1, 4),
(20, 'Fudge Brownie with Ice Cream', 'A warm, gooey chocolate fudge brownie topped with a scoop of vanilla ice cream and drizzled with chocolate sauce.', 450.00, 'brownie.png', 1, 5),
(21, 'Red Velvet Cake Slice', 'A slice of moist red velvet cake with a rich cream cheese frosting. An elegant and decadent treat.', 480.00, 'red_velvet_cake.png', 1, 5),
(22, 'Butter Croissant', 'A flaky, buttery, and freshly baked croissant, perfect with a cup of coffee or as a light snack.', 250.00, 'croissant.png', 1, 5),
(23, 'Cheesy Garlic Bread', 'Toasted baguette slices topped with garlic butter, melted mozzarella cheese, and a sprinkle of herbs.', 380.00, 'garlic_bread.png', 1, 5),
(24, 'Refreshing Mango Lassi', 'A cool and creamy yogurt-based drink blended with sweet, ripe mangoes. Perfect for a hot day.', 280.00, 'mango_lassi.png', 1, 6),
(25, 'Iced Lemon Tea', 'A classic thirst-quencher. Freshly brewed tea chilled and served with a zesty slice of lemon.', 220.00, 'iced_tea.png', 1, 6),
(26, 'Chocolate Milkshake', 'A thick and creamy milkshake made with premium chocolate ice cream and milk, topped with whipped cream.', 350.00, 'chocolate_milkshake.png', 1, 6),
(27, 'Fresh Lime Soda', 'A bubbly and refreshing drink made with fresh lime juice and soda. Available sweet, salty, or mixed.', 180.00, 'lime_soda.png', 1, 6),
(28, 'Hot Cappuccino', 'A perfect balance of rich espresso, steamed milk, and a smooth layer of foam. The ideal coffee break.', 300.00, 'cappuccino.png', 1, 6),
(29, 'Mineral Water', 'A standard bottle of purified mineral water to keep you hydrated.', 30.00, 'mineral_water.png', 1, 6);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`username`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`category_id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `customer`
--
ALTER TABLE `customer`
  ADD PRIMARY KEY (`customer_id`);

--
-- Indexes for table `delivery`
--
ALTER TABLE `delivery`
  ADD PRIMARY KEY (`delivery_id`),
  ADD KEY `order_id` (`order_id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`order_id`),
  ADD KEY `customer_id` (`customer_id`);

--
-- Indexes for table `order_item`
--
ALTER TABLE `order_item`
  ADD PRIMARY KEY (`order_item_id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `fk_order_item_product` (`product_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`product_id`),
  ADD KEY `category_id` (`category_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `category_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `customer`
--
ALTER TABLE `customer`
  MODIFY `customer_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `delivery`
--
ALTER TABLE `delivery`
  MODIFY `delivery_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `order_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `order_item`
--
ALTER TABLE `order_item`
  MODIFY `order_item_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `product_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `delivery`
--
ALTER TABLE `delivery`
  ADD CONSTRAINT `delivery_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`order_id`);

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`customer_id`) REFERENCES `customer` (`customer_id`);

--
-- Constraints for table `order_item`
--
ALTER TABLE `order_item`
  ADD CONSTRAINT `fk_order_item_product` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`) ON DELETE SET NULL,
  ADD CONSTRAINT `order_item_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`order_id`);

--
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`category_id`) ON DELETE SET NULL ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
