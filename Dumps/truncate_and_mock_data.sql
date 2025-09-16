-- Truncate all tables and insert mock data for previous 4 months
-- Date range: May 11, 2025 to September 11, 2025

USE `blush-d`;

-- Disable foreign key checks to allow truncation
SET FOREIGN_KEY_CHECKS = 0;

-- Truncate all tables
TRUNCATE TABLE `cart`;
TRUNCATE TABLE `order_item`;
TRUNCATE TABLE `payment`;
TRUNCATE TABLE `order`;
TRUNCATE TABLE `review`;
TRUNCATE TABLE `product`;
TRUNCATE TABLE `category`;
TRUNCATE TABLE `user`;

-- Re-enable foreign key checks
SET FOREIGN_KEY_CHECKS = 1;

-- Insert Categories
INSERT INTO `category` (`category_id`, `name`) VALUES
(1, 'Skincare'),
(2, 'Makeup'),
(3, 'Fragrance'),
(4, 'Haircare'),
(5, 'Body Care'),
(6, 'Tools');

-- Insert Users (mix of customers, managers, and admins)
INSERT INTO `user` (`user_id`, `first_name`, `last_name`, `email`, `password`, `address`, `phone_number`, `role`, `department`, `birth_day`, `start_day`) VALUES
-- Customers
(1, 'Emma', 'Watson', 'emma.watson@email.com', '$2y$10$hashedpassword1', '123 Hollywood Blvd, Los Angeles, CA', '555-0101', 'CUSTOMER', NULL, '1990-04-15', NULL),
(2, 'Olivia', 'Johnson', 'olivia.j@email.com', '$2y$10$hashedpassword2', '456 Park Avenue, New York, NY', '555-0102', 'CUSTOMER', NULL, '1988-07-22', NULL),
(3, 'Sophia', 'Miller', 'sophia.miller@email.com', '$2y$10$hashedpassword3', '789 Main Street, Chicago, IL', '555-0103', 'CUSTOMER', NULL, '1992-11-03', NULL),
(4, 'Isabella', 'Davis', 'isabella.d@email.com', '$2y$10$hashedpassword4', '321 Oak Avenue, Houston, TX', '555-0104', 'CUSTOMER', NULL, '1995-01-18', NULL),
(5, 'Ava', 'Wilson', 'ava.wilson@email.com', '$2y$10$hashedpassword5', '654 Pine Street, Miami, FL', '555-0105', 'CUSTOMER', NULL, '1993-09-27', NULL),
(6, 'Mia', 'Garcia', 'mia.garcia@email.com', '$2y$10$hashedpassword6', '987 Elm Street, Seattle, WA', '555-0106', 'CUSTOMER', NULL, '1991-06-12', NULL),
(7, 'Charlotte', 'Martinez', 'charlotte.m@email.com', '$2y$10$hashedpassword7', '147 Cedar Lane, Boston, MA', '555-0107', 'CUSTOMER', NULL, '1989-03-08', NULL),
(8, 'Amelia', 'Anderson', 'amelia.a@email.com', '$2y$10$hashedpassword8', '258 Maple Drive, Denver, CO', '555-0108', 'CUSTOMER', NULL, '1994-12-14', NULL),

-- Managers
(9, 'Sarah', 'Thompson', 'sarah.thompson@blushd.com', '$2y$10$hashedpassword9', '369 Business Ave, Corporate City', '555-0201', 'MANAGER', 'Skincare', '1985-02-20', '2020-03-15'),
(10, 'Michael', 'Roberts', 'michael.roberts@blushd.com', '$2y$10$hashedpassword10', '741 Executive St, Corporate City', '555-0202', 'MANAGER', 'Makeup', '1983-08-11', '2019-07-01'),
(11, 'Jessica', 'Lee', 'jessica.lee@blushd.com', '$2y$10$hashedpassword11', '852 Manager Blvd, Corporate City', '555-0203', 'MANAGER', 'Fragrance', '1987-05-30', '2021-01-10'),

-- Admins
(12, 'David', 'Admin', 'david.admin@blushd.com', '$2y$10$hashedpassword12', '963 Admin Plaza, Corporate City', '555-0301', 'ADMIN', 'Administration', '1980-10-05', '2018-01-01'),
(13, 'Rachel', 'Super', 'rachel.super@blushd.com', '$2y$10$hashedpassword13', '159 Control Center, Corporate City', '555-0302', 'ADMIN', 'IT', '1982-12-25', '2017-09-15');

-- Insert Products (comprehensive beauty product catalog)
INSERT INTO `product` (`product_id`, `product_name`, `description`, `price`, `stock`, `category_id`, `image_id`, `ingredients`, `mini_description`, `img_src`) VALUES
-- Skincare Products
(1, 'Hydrating Facial Cleanser', 'Gentle foaming cleanser that removes makeup and impurities while maintaining skin moisture balance.', 24.99, 150, 1, NULL, 'Water, Glycerin, Sodium Cocoyl Glutamate, Aloe Vera Extract', 'Gentle daily cleanser for all skin types', 'assets/products/cleanser.jpg'),
(2, 'Vitamin C Brightening Serum', 'Potent antioxidant serum that brightens skin tone and reduces signs of aging.', 39.99, 120, 1, NULL, 'Vitamin C, Hyaluronic Acid, Niacinamide, Rose Hip Oil', 'Brightening serum for radiant skin', 'assets/products/vitamin-c-serum.jpg'),
(3, 'Anti-Aging Night Cream', 'Rich moisturizing cream with retinol and peptides for overnight skin renewal.', 54.99, 80, 1, NULL, 'Retinol, Peptides, Ceramides, Shea Butter', 'Premium anti-aging night treatment', 'assets/products/night-cream.jpg'),
(4, 'Hyaluronic Acid Moisturizer', 'Lightweight daily moisturizer that provides 24-hour hydration.', 32.99, 200, 1, NULL, 'Hyaluronic Acid, Glycerin, Squalane, Vitamin E', 'Daily hydrating moisturizer', 'assets/products/moisturizer.jpg'),
(5, 'Exfoliating Face Scrub', 'Gentle exfoliating scrub with natural ingredients to reveal smoother skin.', 19.99, 100, 1, NULL, 'Jojoba Beads, Walnut Shell, Aloe Vera, Tea Tree Oil', 'Weekly exfoliating treatment', 'assets/products/face-scrub.jpg'),

-- Makeup Products
(6, 'Full Coverage Foundation', 'Long-wearing liquid foundation with buildable coverage for all skin tones.', 42.99, 90, 2, NULL, 'Titanium Dioxide, Iron Oxides, Dimethicone, Glycerin', 'Professional full coverage foundation', 'assets/products/foundation.jpg'),
(7, 'Waterproof Mascara', 'Volumizing and lengthening mascara that withstands water and humidity.', 18.99, 180, 2, NULL, 'Beeswax, Carnauba Wax, Iron Oxides, Vitamin E', 'Long-lasting waterproof mascara', 'assets/products/mascara.jpg'),
(8, 'Matte Liquid Lipstick', 'High-pigment liquid lipstick with comfortable matte finish.', 22.99, 140, 2, NULL, 'Dimethicone, Titanium Dioxide, Iron Oxides, Vitamin E', 'All-day matte liquid lipstick', 'assets/products/lipstick.jpg'),
(9, 'Eyeshadow Palette', '12-shade eyeshadow palette with matte and shimmer finishes.', 34.99, 75, 2, NULL, 'Talc, Mica, Iron Oxides, Titanium Dioxide', 'Versatile eyeshadow palette', 'assets/products/eyeshadow.jpg'),
(10, 'Contouring Kit', 'Professional contouring and highlighting kit for face sculpting.', 29.99, 85, 2, NULL, 'Talc, Mica, Dimethicone, Iron Oxides', 'Complete contouring solution', 'assets/products/contour-kit.jpg'),

-- Fragrance Products
(11, 'Floral Bloom Perfume', 'Elegant floral fragrance with notes of jasmine, rose, and lily.', 68.99, 60, 3, NULL, 'Alcohol Denat, Parfum, Water, Linalool, Geraniol', 'Sophisticated floral fragrance', 'assets/products/floral-perfume.jpg'),
(12, 'Fresh Citrus EDT', 'Refreshing citrus eau de toilette perfect for daily wear.', 45.99, 95, 3, NULL, 'Alcohol Denat, Parfum, Limonene, Citral', 'Energizing citrus fragrance', 'assets/products/citrus-edt.jpg'),
(13, 'Vanilla Musk Cologne', 'Warm and sensual fragrance with vanilla and musk notes.', 52.99, 70, 3, NULL, 'Alcohol Denat, Parfum, Benzyl Alcohol, Coumarin', 'Warm and inviting fragrance', 'assets/products/vanilla-cologne.jpg'),

-- Haircare Products
(14, 'Moisturizing Shampoo', 'Sulfate-free shampoo that gently cleanses while adding moisture.', 16.99, 200, 4, NULL, 'Water, Sodium Cocoyl Isethionate, Argan Oil, Keratin', 'Gentle moisturizing shampoo', 'assets/products/shampoo.jpg'),
(15, 'Repairing Hair Conditioner', 'Deep conditioning treatment for damaged and dry hair.', 18.99, 180, 4, NULL, 'Water, Cetyl Alcohol, Argan Oil, Protein Complex', 'Intensive hair repair treatment', 'assets/products/conditioner.jpg'),
(16, 'Hair Growth Serum', 'Scientifically formulated serum to promote healthy hair growth.', 49.99, 55, 4, NULL, 'Caffeine, Biotin, Peptides, Rosemary Extract', 'Advanced hair growth formula', 'assets/products/hair-serum.jpg'),
(17, 'Styling Hair Gel', 'Strong hold gel for versatile hair styling without flaking.', 12.99, 150, 4, NULL, 'Water, PVP, Glycerin, Panthenol', 'Professional styling gel', 'assets/products/hair-gel.jpg'),

-- Body Care Products
(18, 'Luxurious Body Lotion', 'Rich body lotion with 24-hour moisturizing power and delicate fragrance.', 26.99, 130, 5, NULL, 'Shea Butter, Cocoa Butter, Vitamin E, Aloe Vera', 'Premium body moisturizer', 'assets/products/body-lotion.jpg'),
(19, 'Exfoliating Body Scrub', 'Invigorating body scrub that removes dead skin cells for smoother skin.', 21.99, 110, 5, NULL, 'Sea Salt, Sugar, Coconut Oil, Essential Oils', 'Revitalizing body scrub', 'assets/products/body-scrub.jpg'),
(20, 'Antibacterial Hand Cream', 'Moisturizing hand cream with antibacterial properties.', 8.99, 250, 5, NULL, 'Glycerin, Shea Butter, Aloe Vera, Vitamin E', 'Protective hand moisturizer', 'assets/products/hand-cream.jpg'),

-- Tools
(21, 'Professional Makeup Brush Set', 'Complete set of 12 premium makeup brushes for professional application.', 79.99, 45, 6, NULL, 'Synthetic bristles, Aluminum ferrule, Wood handle', 'Complete makeup brush collection', 'assets/products/brush-set.jpg'),
(22, 'Beauty Blender Sponge', 'Original beauty blender for flawless foundation application.', 14.99, 200, 6, NULL, 'Non-latex foam, Hydrophilic material', 'Professional makeup sponge', 'assets/products/beauty-blender.jpg'),
(23, 'LED Facial Cleansing Device', 'Advanced sonic facial cleansing brush with LED therapy.', 129.99, 25, 6, NULL, 'Silicone bristles, LED lights, Rechargeable battery', 'High-tech skincare device', 'assets/products/cleansing-device.jpg'),
(24, 'Hair Straightening Brush', 'Ceramic ionic straightening brush for smooth, frizz-free hair.', 89.99, 35, 6, NULL, 'Ceramic plates, Ionic technology, Heat settings', 'Professional hair straightener', 'assets/products/hair-brush.jpg');

-- Insert Orders (spread across 4 months with realistic patterns)
INSERT INTO `order` (`order_id`, `order_date`, `total_price`, `user_id`) VALUES
-- May 2025 Orders
(1, '2025-05-15 10:30:00', 87.97, 1),
(2, '2025-05-18 14:22:00', 156.95, 2),
(3, '2025-05-22 09:45:00', 73.98, 3),
(4, '2025-05-25 16:15:00', 92.99, 4),
(5, '2025-05-28 11:30:00', 45.98, 5),

-- June 2025 Orders
(6, '2025-06-02 13:20:00', 129.97, 6),
(7, '2025-06-05 15:45:00', 67.98, 7),
(8, '2025-06-08 10:15:00', 234.95, 1),
(9, '2025-06-12 14:30:00', 89.98, 8),
(10, '2025-06-15 09:20:00', 112.97, 2),
(11, '2025-06-18 16:45:00', 78.99, 3),
(12, '2025-06-22 11:15:00', 145.96, 4),
(13, '2025-06-25 13:30:00', 56.98, 5),
(14, '2025-06-28 15:20:00', 203.94, 6),

-- July 2025 Orders
(15, '2025-07-01 10:45:00', 94.98, 7),
(16, '2025-07-04 14:15:00', 167.96, 8),
(17, '2025-07-08 09:30:00', 123.97, 1),
(18, '2025-07-11 16:20:00', 89.99, 2),
(19, '2025-07-15 12:45:00', 178.95, 3),
(20, '2025-07-18 11:30:00', 67.98, 4),
(21, '2025-07-22 15:15:00', 145.97, 5),
(22, '2025-07-25 13:45:00', 92.98, 6),
(23, '2025-07-28 10:20:00', 156.96, 7),

-- August 2025 Orders
(24, '2025-08-02 14:30:00', 234.93, 8),
(25, '2025-08-05 11:45:00', 89.98, 1),
(26, '2025-08-08 16:15:00', 123.97, 2),
(27, '2025-08-12 09:30:00', 167.95, 3),
(28, '2025-08-15 13:20:00', 78.99, 4),
(29, '2025-08-18 15:45:00', 201.94, 5),
(30, '2025-08-22 12:15:00', 145.96, 6),
(31, '2025-08-25 10:30:00', 94.98, 7),
(32, '2025-08-28 14:45:00', 289.92, 8),

-- September 2025 Orders (up to current date)
(33, '2025-09-01 11:20:00', 156.97, 1),
(34, '2025-09-04 15:30:00', 89.98, 2),
(35, '2025-09-07 13:45:00', 178.95, 3),
(36, '2025-09-10 10:15:00', 234.94, 4);

-- Insert Order Items (detailed breakdown of each order)
INSERT INTO `order_item` (`order_item_id`, `quantity`, `price`, `order_id`, `product_id`) VALUES
-- Order 1 items
(1, 2, 24.99, 1, 1),
(2, 1, 39.99, 1, 2),
(3, 1, 22.99, 1, 8),

-- Order 2 items
(4, 1, 68.99, 2, 11),
(5, 2, 42.99, 2, 6),
(6, 1, 14.99, 2, 22),

-- Order 3 items
(7, 1, 54.99, 3, 3),
(8, 1, 18.99, 3, 7),

-- Order 4 items
(9, 1, 79.99, 4, 21),
(10, 1, 12.99, 4, 17),

-- Order 5 items
(11, 1, 26.99, 5, 18),
(12, 1, 18.99, 5, 15),

-- Order 6 items
(13, 1, 129.99, 6, 23),

-- Order 7 items
(14, 2, 32.99, 7, 4),
(15, 1, 21.99, 7, 19),

-- Order 8 items
(16, 2, 52.99, 8, 13),
(17, 1, 129.99, 8, 23),

-- Order 9 items
(18, 3, 29.99, 9, 10),

-- Order 10 items
(19, 1, 49.99, 10, 16),
(20, 2, 32.99, 10, 4),

-- Order 11 items
(21, 1, 45.99, 11, 12),
(22, 2, 16.99, 11, 14),

-- Order 12 items
(23, 1, 89.99, 12, 24),
(24, 2, 26.99, 12, 18),
(25, 1, 21.99, 12, 19),

-- Order 13 items
(26, 3, 18.99, 13, 7),

-- Order 14 items
(27, 1, 79.99, 14, 21),
(28, 1, 129.99, 14, 23),

-- Order 15 items
(29, 2, 34.99, 15, 9),
(30, 1, 24.99, 15, 1),

-- Order 16 items
(31, 1, 68.99, 16, 11),
(32, 1, 52.99, 16, 13),
(33, 2, 22.99, 16, 8),

-- Order 17 items
(34, 1, 54.99, 17, 3),
(35, 1, 39.99, 17, 2),
(36, 1, 29.99, 17, 10),

-- Order 18 items
(37, 2, 42.99, 18, 6),
(38, 1, 14.99, 18, 22),

-- Order 19 items
(39, 1, 129.99, 19, 23),
(40, 2, 24.99, 19, 1),

-- Order 20 items
(41, 1, 45.99, 20, 12),
(42, 1, 21.99, 20, 19),

-- Order 21 items
(43, 1, 79.99, 21, 21),
(44, 2, 32.99, 21, 4),

-- Order 22 items
(45, 1, 49.99, 22, 16),
(46, 2, 21.99, 22, 19),

-- Order 23 items
(47, 1, 68.99, 23, 11),
(48, 2, 42.99, 23, 6),
(49, 1, 21.99, 23, 19),

-- Order 24 items
(50, 1, 129.99, 24, 23),
(51, 1, 89.99, 24, 24),
(52, 1, 14.99, 24, 22),

-- Order 25 items
(53, 2, 42.99, 25, 6),
(54, 1, 14.99, 25, 22),

-- Order 26 items
(55, 1, 54.99, 26, 3),
(56, 1, 39.99, 26, 2),
(57, 1, 29.99, 26, 10),

-- Order 27 items
(58, 1, 79.99, 27, 21),
(59, 2, 42.99, 27, 6),
(60, 1, 21.99, 27, 19),

-- Order 28 items
(61, 1, 45.99, 28, 12),
(62, 2, 16.99, 28, 14),

-- Order 29 items
(63, 1, 129.99, 29, 23),
(64, 1, 49.99, 29, 16),
(65, 1, 21.99, 29, 19),

-- Order 30 items
(66, 1, 79.99, 30, 21),
(67, 2, 32.99, 30, 4),

-- Order 31 items
(68, 2, 34.99, 31, 9),
(69, 1, 24.99, 31, 1),

-- Order 32 items
(70, 1, 129.99, 32, 23),
(71, 1, 89.99, 32, 24),
(72, 1, 49.99, 32, 16),
(73, 1, 19.99, 32, 5),

-- Order 33 items
(74, 1, 68.99, 33, 11),
(75, 2, 42.99, 33, 6),
(76, 1, 21.99, 33, 19),

-- Order 34 items
(77, 2, 42.99, 34, 6),
(78, 1, 14.99, 34, 22),

-- Order 35 items
(79, 1, 129.99, 35, 23),
(80, 2, 24.99, 35, 1),

-- Order 36 items
(81, 1, 79.99, 36, 21),
(82, 1, 129.99, 36, 23),
(83, 1, 24.99, 36, 1),
(84, 1, 19.99, 36, 5);

-- Insert Payments (one payment per order)
INSERT INTO `payment` (`payment_id`, `payment_date`, `payment_method`, `amount`, `order_id`, `user_id`) VALUES
(1, '2025-05-15 10:35:00', 'Credit Card', 87.97, 1, 1),
(2, '2025-05-18 14:27:00', 'PayPal', 156.95, 2, 2),
(3, '2025-05-22 09:50:00', 'Debit Card', 73.98, 3, 3),
(4, '2025-05-25 16:20:00', 'Credit Card', 92.99, 4, 4),
(5, '2025-05-28 11:35:00', 'PayPal', 45.98, 5, 5),
(6, '2025-06-02 13:25:00', 'Credit Card', 129.97, 6, 6),
(7, '2025-06-05 15:50:00', 'Debit Card', 67.98, 7, 7),
(8, '2025-06-08 10:20:00', 'Credit Card', 234.95, 8, 1),
(9, '2025-06-12 14:35:00', 'PayPal', 89.98, 9, 8),
(10, '2025-06-15 09:25:00', 'Credit Card', 112.97, 10, 2),
(11, '2025-06-18 16:50:00', 'Debit Card', 78.99, 11, 3),
(12, '2025-06-22 11:20:00', 'Credit Card', 145.96, 12, 4),
(13, '2025-06-25 13:35:00', 'PayPal', 56.98, 13, 5),
(14, '2025-06-28 15:25:00', 'Credit Card', 203.94, 14, 6),
(15, '2025-07-01 10:50:00', 'Debit Card', 94.98, 15, 7),
(16, '2025-07-04 14:20:00', 'Credit Card', 167.96, 16, 8),
(17, '2025-07-08 09:35:00', 'PayPal', 123.97, 17, 1),
(18, '2025-07-11 16:25:00', 'Credit Card', 89.99, 18, 2),
(19, '2025-07-15 12:50:00', 'Debit Card', 178.95, 19, 3),
(20, '2025-07-18 11:35:00', 'Credit Card', 67.98, 20, 4),
(21, '2025-07-22 15:20:00', 'PayPal', 145.97, 21, 5),
(22, '2025-07-25 13:50:00', 'Credit Card', 92.98, 22, 6),
(23, '2025-07-28 10:25:00', 'Debit Card', 156.96, 23, 7),
(24, '2025-08-02 14:35:00', 'Credit Card', 234.93, 24, 8),
(25, '2025-08-05 11:50:00', 'PayPal', 89.98, 25, 1),
(26, '2025-08-08 16:20:00', 'Credit Card', 123.97, 26, 2),
(27, '2025-08-12 09:35:00', 'Debit Card', 167.95, 27, 3),
(28, '2025-08-15 13:25:00', 'Credit Card', 78.99, 28, 4),
(29, '2025-08-18 15:50:00', 'PayPal', 201.94, 29, 5),
(30, '2025-08-22 12:20:00', 'Credit Card', 145.96, 30, 6),
(31, '2025-08-25 10:35:00', 'Debit Card', 94.98, 31, 7),
(32, '2025-08-28 14:50:00', 'Credit Card', 289.92, 32, 8),
(33, '2025-09-01 11:25:00', 'PayPal', 156.97, 33, 1),
(34, '2025-09-04 15:35:00', 'Credit Card', 89.98, 34, 2),
(35, '2025-09-07 13:50:00', 'Debit Card', 178.95, 35, 3),
(36, '2025-09-10 10:20:00', 'Credit Card', 234.94, 36, 4);

-- Insert Reviews (customers reviewing products they purchased)
INSERT INTO `review` (`review_id`, `rating`, `comments`, `review_date`, `user_id`, `product_id`) VALUES
(1, 5, 'Amazing cleanser! Leaves my skin feeling so soft and clean without drying it out.', '2025-05-20 18:30:00', 1, 1),
(2, 4, 'Great vitamin C serum, I can see my dark spots fading already.', '2025-05-25 14:15:00', 1, 2),
(3, 5, 'This perfume is absolutely divine! The floral scent lasts all day.', '2025-05-22 20:45:00', 2, 11),
(4, 4, 'Good foundation with excellent coverage. Perfect for my skin tone.', '2025-05-28 16:20:00', 2, 6),
(5, 5, 'Best night cream I\'ve ever used! My skin looks so much younger.', '2025-05-30 22:10:00', 3, 3),
(6, 3, 'Mascara is okay, but I expected better waterproof performance.', '2025-06-01 11:30:00', 3, 7),
(7, 5, 'These brushes are professional quality! Worth every penny.', '2025-06-05 19:45:00', 4, 21),
(8, 4, 'Hair gel holds well without making hair feel crunchy.', '2025-06-08 15:20:00', 4, 17),
(9, 5, 'Love this body lotion! Smells amazing and keeps skin moisturized all day.', '2025-06-10 17:30:00', 5, 18),
(10, 4, 'Good conditioner, makes my hair feel silky smooth.', '2025-06-12 14:45:00', 5, 15),
(11, 5, 'This cleansing device is a game changer! My skin has never looked better.', '2025-06-15 21:20:00', 6, 23),
(12, 4, 'Moisturizer is very hydrating, perfect for my dry skin.', '2025-06-18 13:30:00', 7, 4),
(13, 3, 'Body scrub is good but a bit too abrasive for sensitive skin.', '2025-06-20 16:45:00', 7, 19),
(14, 5, 'Eyeshadow palette has amazing color payoff and blends beautifully.', '2025-06-25 19:15:00', 8, 9),
(15, 4, 'Shampoo cleans well and doesn\'t strip my hair of natural oils.', '2025-06-28 12:30:00', 1, 14),
(16, 5, 'Hair growth serum actually works! I can see new baby hairs growing.', '2025-07-02 20:45:00', 2, 16),
(17, 4, 'Citrus EDT is refreshing and perfect for summer days.', '2025-07-05 14:20:00', 2, 12),
(18, 5, 'Contouring kit is perfect for beginners like me. Easy to blend.', '2025-07-08 18:30:00', 3, 10),
(19, 3, 'Vanilla cologne is nice but doesn\'t last as long as I hoped.', '2025-07-12 16:15:00', 3, 13),
(20, 5, 'Beauty blender gives such a flawless finish! Can\'t do makeup without it now.', '2025-07-15 11:45:00', 4, 22),
(21, 4, 'Face scrub leaves skin feeling so smooth and refreshed.', '2025-07-18 19:30:00', 4, 5),
(22, 5, 'Liquid lipstick stays put all day without drying out my lips.', '2025-07-22 15:20:00', 5, 8),
(23, 4, 'Hand cream is very moisturizing and absorbs quickly.', '2025-07-25 13:45:00', 6, 20),
(24, 5, 'Hair straightening brush works amazing! Saves so much time.', '2025-07-28 17:15:00', 7, 24),
(25, 4, 'Foundation blends well and provides good coverage for the price.', '2025-08-02 14:30:00', 8, 6),
(26, 5, 'This night cream has transformed my skin! Highly recommend.', '2025-08-05 21:45:00', 1, 3),
(27, 3, 'Serum is good but takes a while to see results.', '2025-08-08 16:20:00', 2, 2),
(28, 5, 'Brush set quality is outstanding! Professional grade brushes.', '2025-08-12 19:30:00', 3, 21),
(29, 4, 'Perfume has a lovely scent that gets many compliments.', '2025-08-15 15:45:00', 4, 11),
(30, 5, 'Cleansing device deep cleans pores better than any manual method.', '2025-08-18 20:15:00', 5, 23),
(31, 4, 'Hair serum helps with growth and makes hair look healthier.', '2025-08-22 14:30:00', 6, 16),
(32, 5, 'Body scrub exfoliates perfectly and leaves skin so soft.', '2025-08-25 18:45:00', 7, 19),
(33, 3, 'Eyeshadow is good but could use better staying power.', '2025-08-28 16:20:00', 8, 9),
(34, 5, 'Cleanser is gentle yet effective. Perfect for daily use.', '2025-09-01 17:30:00', 1, 1),
(35, 4, 'Foundation matches my skin tone perfectly and lasts all day.', '2025-09-04 13:45:00', 2, 6),
(36, 5, 'Amazing cleansing device! My skin glows after every use.', '2025-09-07 19:15:00', 3, 23),
(37, 4, 'Brush set includes everything I need for a complete makeup look.', '2025-09-10 15:30:00', 4, 21);

-- Insert Cart items (current active cart items for some customers)
INSERT INTO `cart` (`cart_id`, `user_id`, `product_id`, `quantity`) VALUES
(1, 1, 12, 1),  -- Fresh Citrus EDT
(2, 1, 20, 2),  -- Hand cream
(3, 2, 9, 1),   -- Eyeshadow palette
(4, 2, 15, 1),  -- Hair conditioner
(5, 3, 23, 1),  -- LED cleansing device
(6, 4, 8, 2),   -- Liquid lipstick
(7, 4, 18, 1),  -- Body lotion
(8, 5, 21, 1),  -- Brush set
(9, 6, 3, 1),   -- Night cream
(10, 6, 16, 1), -- Hair growth serum
(11, 7, 11, 1), -- Floral perfume
(12, 8, 24, 1), -- Hair straightening brush
(13, 8, 5, 1);  -- Face scrub

-- Display completion message
SELECT 'Database successfully truncated and populated with 4 months of mock data!' AS Status;
SELECT 
    'Data Summary:' AS Info,
    (SELECT COUNT(*) FROM user) AS Total_Users,
    (SELECT COUNT(*) FROM product) AS Total_Products, 
    (SELECT COUNT(*) FROM `order`) AS Total_Orders,
    (SELECT COUNT(*) FROM order_item) AS Total_Order_Items,
    (SELECT COUNT(*) FROM payment) AS Total_Payments,
    (SELECT COUNT(*) FROM review) AS Total_Reviews,
    (SELECT COUNT(*) FROM cart) AS Active_Cart_Items;
