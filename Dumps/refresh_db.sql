USE `blush-d`;

SET FOREIGN_KEY_CHECKS = 0;

TRUNCATE TABLE `cart`;
TRUNCATE TABLE `order_item`;
TRUNCATE TABLE `payment`;
TRUNCATE TABLE `order`;
TRUNCATE TABLE `review`;
TRUNCATE TABLE `product`;
TRUNCATE TABLE `category`;
TRUNCATE TABLE `user`;

SET FOREIGN_KEY_CHECKS = 1;

INSERT INTO `category` (`category_id`, `name`) VALUES
(1, 'Skincare'),
(2, 'Makeup'),
(3, 'Haircare'),
(4, 'Tools');

INSERT INTO `user` (`user_id`, `first_name`, `last_name`, `email`, `password`, `address`, `phone_number`, `role`, `department`, `birth_day`, `start_day`) VALUES
(1, 'Jessica', 'Anderson', 'admin@example.com', '$2y$10$OELhuUiwHcVxo./6Coo13OaVgcu5NxleVe9EzKV2nZhYgc92wNaw6', '123 Hollywood Blvd, Los Angeles, CA', '555-0101', 'ADMIN', NULL, NULL, '2017-04-15'),
(2, 'Olivia', 'Johnson', 'manager@example.com', '$2y$10$Sz/Di9gZNsBrWxEhn7tzluWtun899awqghs2q8o3qas5kgwGJlS0m', '456 Park Avenue, New York, NY', '555-0102', 'MANAGER', NULL, NULL, '2018-07-22');

-- ADMIN password = Admin@1234
-- MANAGER password = Manager@1234