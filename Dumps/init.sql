CREATE DATABASE  IF NOT EXISTS `blush_d_new`;
USE `blush_d_new`;


DROP TABLE IF EXISTS `user`;
CREATE TABLE `user` (
  `user_id` int NOT NULL AUTO_INCREMENT,
  `first_name` varchar(100) NOT NULL,
  `last_name` varchar(100) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255)  NOT NULL,
  `address` varchar(255) DEFAULT NULL,
  `phone_number` varchar(20) DEFAULT NULL,
  `role` varchar(50) DEFAULT 'CUSTOMER',
  `department` varchar(100) DEFAULT NULL,
  `birth_day` date DEFAULT NULL,
  `start_day` date DEFAULT NULL,
  PRIMARY KEY (`user_id`),
  UNIQUE KEY `email` (`email`)
);
INSERT INTO `user` VALUES 
(1,'Jessica','Anderson','admin@example.com','$2y$10$OELhuUiwHcVxo./6Coo13OaVgcu5NxleVe9EzKV2nZhYgc92wNaw6','123 Hollywood Blvd, Los Angeles, CA','555-0101','ADMIN',NULL,NULL,'2017-04-15'),
(2,'Olivia','Johnson','manager@example.com','$2y$10$Sz/Di9gZNsBrWxEhn7tzluWtun899awqghs2q8o3qas5kgwGJlS0m','456 Park Avenue, New York, NY','555-0102','MANAGER',NULL,NULL,'2018-07-22');


DROP TABLE IF EXISTS `category`;
CREATE TABLE `category` (
  `category_id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  PRIMARY KEY (`category_id`)
);
INSERT INTO `category` VALUES 
(1,'Skincare'),
(2,'Makeup'),
(3,'Haircare'),
(4,'Tools');


DROP TABLE IF EXISTS `product`;
CREATE TABLE `product` (
  `product_id` int NOT NULL AUTO_INCREMENT,
  `product_name` varchar(100) DEFAULT NULL,
  `description` text,
  `price` decimal(10,2) NOT NULL,
  `stock` int NOT NULL,
  `category_id` int DEFAULT NULL,
  `image_id` int DEFAULT NULL,
  `ingredients` text,
  `mini_description` varchar(225) DEFAULT NULL,
  `img_src` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`product_id`),
  KEY `category_id` (`category_id`),
  CONSTRAINT `product_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `category` (`category_id`) ON DELETE SET NULL
);


DROP TABLE IF EXISTS `cart`;
CREATE TABLE `cart` (
  `cart_id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `product_id` int NOT NULL,
  `quantity` int NOT NULL,
  PRIMARY KEY (`cart_id`),
  KEY `user_id` (`user_id`),
  KEY `product_id` (`product_id`),
  CONSTRAINT `cart_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`) ON DELETE CASCADE,
  CONSTRAINT `cart_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `product` (`product_id`) ON DELETE CASCADE
);


DROP TABLE IF EXISTS `order`;
CREATE TABLE `order` (
  `order_id` int NOT NULL AUTO_INCREMENT,
  `order_date` datetime NOT NULL,
  `total_price` decimal(10,2) NOT NULL,
  `user_id` int DEFAULT NULL,
  `house_no` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `street1` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `street2` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `city` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `postal_code` varchar(20) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `payment_method` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  PRIMARY KEY (`order_id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `order_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`) ON DELETE CASCADE
);


DROP TABLE IF EXISTS `review`;
CREATE TABLE `review` (
  `review_id` int NOT NULL AUTO_INCREMENT,
  `rating` int NOT NULL,
  `comments` text,
  `review_date` datetime NOT NULL,
  `user_id` int DEFAULT NULL,
  `product_id` int DEFAULT NULL,
  PRIMARY KEY (`review_id`),
  KEY `user_id` (`user_id`),
  KEY `product_id` (`product_id`),
  CONSTRAINT `review_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`) ON DELETE CASCADE,
  CONSTRAINT `review_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `product` (`product_id`) ON DELETE CASCADE,
  CONSTRAINT `review_chk_1` CHECK (((`rating` >= 1) and (`rating` <= 5)))
);


DROP TABLE IF EXISTS `order_item`;
CREATE TABLE `order_item` (
  `order_item_id` int NOT NULL AUTO_INCREMENT,
  `quantity` int NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `order_id` int DEFAULT NULL,
  `product_id` int DEFAULT NULL,
  PRIMARY KEY (`order_item_id`),
  KEY `order_id` (`order_id`),
  KEY `product_id` (`product_id`),
  CONSTRAINT `order_item_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `order` (`order_id`) ON DELETE CASCADE,
  CONSTRAINT `order_item_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `product` (`product_id`) ON DELETE SET NULL
);


DROP TABLE IF EXISTS `payment`;
CREATE TABLE `payment` (
  `payment_id` int NOT NULL AUTO_INCREMENT,
  `payment_date` datetime NOT NULL,
  `payment_method` varchar(100) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `order_id` int DEFAULT NULL,
  `user_id` int DEFAULT NULL,
  PRIMARY KEY (`payment_id`),
  KEY `user_id` (`user_id`),
  KEY `order_id` (`order_id`),
  CONSTRAINT `payment_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`),
  CONSTRAINT `payment_ibfk_2` FOREIGN KEY (`order_id`) REFERENCES `order` (`order_id`)
);
