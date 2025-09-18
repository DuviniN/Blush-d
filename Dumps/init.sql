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
INSERT INTO `user` (`user_id`, `first_name`, `last_name`, `email`, `password`, `address`, `phone_number`, `role`, `department`, `birth_day`, `start_day`) VALUES
(1, 'Jessica', 'Anderson', 'admin@example.com', '$2y$10$OELhuUiwHcVxo./6Coo13OaVgcu5NxleVe9EzKV2nZhYgc92wNaw6', '123 Hollywood Blvd, Los Angeles, CA', '555-0101', 'ADMIN', NULL, NULL, '2017-04-15'),
(2, 'Olivia', 'Johnson', 'manager@example.com', '$2y$10$Sz/Di9gZNsBrWxEhn7tzluWtun899awqghs2q8o3qas5kgwGJlS0m', '456 Park Avenue, New York, NY', '555-0102', 'MANAGER', NULL, NULL, '2018-07-22'),
(3, 'Thiseni', 'Ruhansa', 'thiseni@gmail.com', '$2y$10$Cza94X.nKAZ6l8o/frHyOeAHoXn2lTb6HRM7iqt7JSKK2GwyQKGvS', NULL, NULL, 'CUSTOMER', NULL, NULL, NULL);


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
--
-- Dumping data for table `product`
--

INSERT INTO `product` (`product_id`, `product_name`, `description`, `price`, `stock`, `category_id`, `image_id`, `ingredients`, `mini_description`, `img_src`) VALUES
(1, 'Pure Clean Face Wash', 'A mild foaming face wash enriched with green tea extract to control excess oil and prevent acne. Aloe vera soothes the skin, while glycerin keeps it hydrated without stripping natural moisture. Perfect for everyday use.', 1100.00, 25, 1, 673988, 'Tea Extract, Aloe Vera, Glycerin', 'Gentle daily cleanser for fresh, oil-free skin', 'assets/products/product_673988.png'),
(2, 'Charcoal Detox Face Wash', 'Formulated with activated charcoal to draw out impurities and dirt from deep within pores. Neem extract fights bacteria, while tea tree oil prevents breakouts, leaving skin clear and refreshed. Ideal for oily and acne-prone skin.', 1120.00, 24, 1, 897635, 'Activated Charcoal, Neem Extract, Tea Tree Oil', 'Deep cleanses and unclogs pores.', 'assets/products/product_897635.png'),
(3, 'Aqua Glow Hydrating Face Wash', 'A gentle gel-based face wash infused with hyaluronic acid to lock in moisture and vitamin E to nourish the skin. Cucumber extract cools and refreshes, making it ideal for dry and sensitive skin. Leaves skin soft, supple, and glowing.', 1100.00, 25, 1, 668914, 'Hyaluronic Acid, Cucumber Extract, Vitamin E', 'Hydrates and refreshes tired, dry skin.', 'assets/products/product_668914.png'),
(4, 'Citrus Brightening Face Wash', 'Packed with vitamin C and natural orange peel extract, this face wash removes dullness and boosts radiance. Honey soothes and moisturizes, leaving skin bright, smooth, and energized. Best for dull and uneven skin tones.', 1130.00, 25, 1, 465492, 'Vitamin C, Orange Peel Extract, Honey', 'Brightens skin for a radiant glow.', 'assets/products/product_465492.png'),
(5, 'Radiant Glow Day Cream', 'A lightweight day cream that boosts skin radiance with Vitamin C and evens skin tone with Niacinamide. Shea butter provides lasting hydration, leaving skin soft and luminous without greasiness.', 1150.00, 25, 1, 471860, 'Vitamin C, Niacinamide, Shea Butter', 'Brightens and nourishes for an all-day glow.', 'assets/products/product_471860.png'),
(6, 'HydraLock Night Repair Cream', 'An intensive night cream designed to deeply hydrate and repair tired skin. Retinol helps reduce fine lines, while hyaluronic acid locks in moisture and jojoba oil soothes the skin for a fresh, youthful look by morning.', 1120.00, 25, 1, 359586, 'Hyaluronic Acid, Retinol, Jojoba Oil', 'Restores and repairs skin overnight.', 'assets/products/product_359586.png'),
(7, 'Calm & Care Sensitive Skin Cream', 'Specially formulated for sensitive and irritated skin, this cream calms redness and soothes discomfort with chamomile extract and aloe vera. Glycerin maintains moisture balance, leaving skin soft, calm, and protected.', 1140.00, 25, 1, 172942, 'Zinc Oxide, Vitamin E, Green Tea Extract', 'Gentle hydration for sensitive skin.', 'assets/products/product_172942.png'),
(8, 'Vitamin C Brightening Serum', 'A lightweight serum packed with Vitamin C to brighten skin and reduce pigmentation. Hyaluronic acid hydrates deeply, while orange extract gives a fresh, energized glow. Perfect for dull and uneven skin tone.', 1100.00, 24, 1, 740079, 'Vitamin C, Hyaluronic Acid, Orange Extract', 'Boosts radiance and fades dark spots.', 'assets/products/product_740079.png'),
(9, 'Hyaluronic Hydra Serum', 'This ultra-hydrating serum instantly replenishes moisture with pure hyaluronic acid. Aloe vera soothes irritation while glycerin locks in hydration, leaving skin soft, supple, and bouncy. Ideal for dry or sensitive skin.', 1120.00, 25, 1, 882055, 'Hyaluronic Acid, Aloe Vera, Glycerin', 'Intense hydration for plump, dewy skin.', 'assets/products/product_882055.png'),
(10, 'Silk Smooth Body Lotion', 'A rich, non-greasy body lotion that melts into the skin to provide lasting hydration. Shea butter restores moisture, almond oil softens, and vitamin E protects against dryness. Perfect for daily use.', 1130.00, 24, 1, 622766, 'Shea Butter, Almond Oil, Vitamin E', 'Deep nourishment for silky-soft skin.', 'assets/products/product_622766.png'),
(11, 'Silky Shine Shampoo', 'A nourishing shampoo infused with argan oil to tame frizz, keratin to strengthen strands, and aloe vera to soothe the scalp. Leaves hair soft, manageable, and naturally glossy after every wash.', 1300.00, 25, 3, 927852, 'Argan Oil, Keratin, Aloe Vera', 'Gentle cleansing for smooth, shiny hair.', 'assets/products/product_927852.png'),
(12, 'Herbal Strength Shampoo', 'A herbal formula that revitalizes weak hair and promotes healthy growth. Amla and bhringraj strengthen roots, while neem helps maintain a clean, dandruff-free scalp. Best for reducing hair fall naturally.', 1200.00, 25, 3, 367779, 'Amla Extract, Neem, Bhringraj', 'Strengthens roots and reduces hair fall.', 'assets/products/product_367779.png'),
(13, 'Tea Tree Fresh Scalp Shampoo', 'A refreshing shampoo with tea tree oil to fight dandruff and peppermint to cool the scalp. Zinc PCA controls excess oil, leaving hair clean, fresh, and free from flakes. Perfect for oily or itchy scalps.', 1250.00, 25, 3, 960831, 'Tea Tree Oil, Peppermint, Zinc PCA', 'Refreshes scalp and fights dandruff.', 'assets/products/product_960831.png'),
(14, 'Nourish & Shine Coconut Hair Oil', 'Enriched with pure coconut oil, this lightweight formula penetrates deep into the scalp to strengthen roots. Vitamin E restores shine, while aloe extract soothes dryness, leaving hair smooth and healthy', 1400.00, 25, 3, 686496, 'Pure Coconut Oil, Vitamin E, Aloe Extract', 'Deep nourishment for silky, strong hair.', 'assets/products/product_686496.png'),
(15, 'Herbal Growth Hair Oil', 'A traditional blend of Ayurvedic herbs that revitalizes hair follicles and promotes natural growth. Amla and bhringraj strengthen roots, while neem keeps the scalp dandruff-free. Castor oil nourishes and thickens strands.', 1500.00, 25, 3, 780185, 'Amla, Bhringraj, Neem, Castor Oil', 'Boosts hair growth and reduces fall.', 'assets/products/product_780185.png'),
(16, 'Argan Miracle Hair Oil', 'Rich in vitamins and antioxidants, this lightweight argan oil blend tames frizz, adds shine, and repairs split ends. Perfect for dry, damaged, or chemically treated hair that needs extra care.', 1000.00, 25, 3, 706093, 'Argan Oil, Jojoba Oil, Almond Oil', 'Smooth, frizz-free hair with natural shine', 'assets/products/product_706093.png'),
(17, 'Frizz Control Styling Mousse', 'This anti-frizz mousse tames flyaways and provides flexible hold. Argan oil nourishes hair while silk proteins give a silky texture, making it perfect for sleek and polished hairstyles.', 1600.00, 5, 3, 177241, 'Argan Oil, Vitamin E, Silk Proteins', 'Smooths frizz and defines hair', 'assets/products/product_177241.png'),
(18, 'Texturizing Sea Salt Spray', 'Adds volume and natural wave to hair while maintaining softness. Sea salt provides texture, aloe vera soothes, and coconut extract nourishes for effortless, tousled styles.', 1000.00, 25, 3, 635595, 'Sea Salt, Aloe Vera, Coconut Extract', 'Creates beachy waves and texture.', 'assets/products/product_635595.png'),
(19, 'Anti-Frizz Hair Serum', 'Lightweight serum that controls frizz, adds shine, and softens hair. Argan and jojoba oils nourish while vitamin E protects against environmental damage.', 1200.00, 25, 3, 772493, 'Argan Oil, Jojoba Oil, Vitamin E', 'Smooths hair and tames flyaways.', 'assets/products/product_772493.png'),
(20, 'Matte Hold Hair Wax', 'This hair wax provides firm styling without stiffness. Beeswax gives shape and texture, shea butter nourishes hair, and vitamin E protects strands from damage, leaving a soft matte look.', 1300.00, 25, 3, 908864, 'Beeswax, Shea Butter, Vitamin E', 'Strong hold with natural matte finish.', 'assets/products/product_908864.png'),
(21, 'Lush Matte Lipstick', 'A long-lasting matte lipstick that glides smoothly on lips. Enriched with shea butter and vitamin E for hydration, and beeswax for a soft texture. Perfect for all-day vibrant color.', 1500.00, 25, 2, 583648, 'Shea Butter, Vitamin E, Beeswax', 'Bold matte color with comfortable wear.', 'assets/products/product_583648.png'),
(22, 'Glossy Shine Lipstick', 'Provides a high-shine finish while keeping lips soft and moisturized. Castor and jojoba oils nourish, and carnauba wax ensures smooth, even application for a glamorous look.', 1200.00, 25, 2, 950430, 'Castor Oil, Jojoba Oil, Carnauba Wax', 'Hydrating lips with a glossy finish.', 'assets/products/product_950430.png'),
(23, 'Longwear Liquid Lipstick', 'Lightweight liquid lipstick with rich pigmentation that stays put for hours. Coconut oil and vitamin E keep lips hydrated while silica ensures a smooth, velvety finish without cracking.', 1300.00, 25, 2, 415049, 'Vitamin E, Silica, Coconut Oil', 'Intense color that lasts all day.', 'assets/products/product_415049.png'),
(24, 'Radiant Liquid Foundation', 'Provides smooth, even coverage while keeping skin hydrated. Hyaluronic acid retains moisture, vitamin E nourishes, and aloe vera soothes, leaving a radiant, flawless finish suitable for all-day wear.', 1350.00, 25, 2, 269189, 'Hyaluronic Acid, Vitamin E, Aloe Vera', 'Lightweight foundation for a natural glow.', 'assets/products/product_269189.png'),
(25, 'Tinted Moisturizer Foundation', 'A multi-tasking tinted moisturizer that evens skin tone while keeping it moisturized. Aloe vera and glycerin hydrate, and green tea extract provides antioxidant protection for a healthy, fresh finish.', 1400.00, 25, 2, 154781, 'Aloe Vera, Glycerin, Green Tea Extract', 'Light coverage with hydration and glow.', 'assets/products/product_154781.png'),
(26, 'Peach Glow Blush', 'Lightweight powder blush that gives a soft peach glow. Enriched with vitamin E and shea butter to nourish skin, blendable for a natural or buildable finish, perfect for everyday looks.', 1000.00, 25, 2, 200539, 'Mica, Vitamin E, Shea Butter', 'Natural peachy flush for your cheeks.', 'assets/products/product_200539.png'),
(27, 'Golden Glow Highlighter', 'Cream-to-powder highlighter that adds a radiant golden glow to cheekbones, brow bones, and cupid’s bow. Argan oil and vitamin E nourish while mica ensures a smooth, luminous finish.', 1200.00, 25, 2, 506382, 'Mica, Vitamin E, Argan Oil', 'Illuminates skin with a golden shimmer.', 'assets/products/product_506382.png'),
(28, 'Shimmer Nude Eyeshadow Palette', 'A 3-shade palette featuring nude and shimmer tones for natural, everyday looks. Enriched with vitamin E and jojoba oil, it blends easily and stays vibrant all day without creasing.', 1250.00, 25, 2, 199110, 'Mica, Talc, Vitamin E, Jojoba Oil', 'Soft nude shades with subtle shimmer.', 'assets/products/product_199110.png'),
(29, 'Hydrating Glow Primer', 'Infused with hyaluronic acid and aloe vera, this primer hydrates skin while giving a subtle luminous finish. Glycerin locks in moisture, making makeup blend effortlessly and last longer', 1300.00, 25, 2, 626093, 'Hyaluronic Acid, Aloe Vera, Glycerin', 'Adds hydration and a radiant glow.', 'assets/products/product_626093.png'),
(30, 'Mattifying Control Primer', 'Specially formulated for oily and combination skin, this primer absorbs excess oil and reduces shine. Niacinamide helps even skin tone, while kaolin clay and zinc PCA keep makeup intact all day.', 1300.00, 25, 2, 151736, 'Kaolin Clay, Niacinamide, Zinc PCA', 'Controls shine for oily skin.', 'assets/products/product_151736.png'),
(31, 'Beauty Blender Sponge', 'Soft, bouncy sponge that delivers a smooth, airbrushed finish for foundation and concealer. Easily molds to facial contours.', 1000.00, 25, 4, 974451, '', 'Blend makeup seamlessly.', 'assets/products/product_974451.png'),
(32, 'Silicone Face Brush', 'Soft silicone brush gently exfoliates and deep-cleans pores without irritation. Ideal for all skin types.', 1100.00, 25, 4, 795642, '', 'Gentle cleansing tool.', 'assets/products/product_795642.png'),
(33, 'Foundation Brush', 'lat or rounded brush designed for liquid or cream foundation. Delivers an even, streak-free application for a flawless complexion.', 1000.00, 25, 4, 935416, '', 'Smooth, even foundation finish', 'assets/products/product_935416.png'),
(34, 'Blending Eyeshadow Brush', '', 1200.00, 20, 4, 386241, 'Fluffy brush designed to diffuse eyeshadow seamlessly for soft, gradient transitions and professional-looking eye makeup.', 'Smoothly blend eyeshadow colors.', 'assets/products/product_386241.png'),
(35, 'Contour Brush', 'Angled brush perfect for contouring cheekbones, jawline, and nose, giving a natural, sculpted look.', 1000.00, 20, 4, 614204, '', 'Define and sculpt facial features.', 'assets/products/product_614204.png'),
(36, 'Fluffy Soft Powder Brush', 'A large, soft brush designed for loose or pressed powders. Delivers a flawless, natural finish by evenly distributing product without streaks or patchiness.', 1000.00, 20, 4, 852366, '', 'Smooth, even powder application', 'assets/products/product_852366.png'),
(37, 'Fan Powder Brush', 'Light fan-shaped brush gently sweeps powder across the face. Ideal for applying highlighter, blush, or dusting off excess product without disturbing makeup.', 1000.00, 20, 4, 766469, '', 'Highlight and remove excess powder.', 'assets/products/product_766469.png'),
(38, 'Peach Perfection Blusher Set', 'Compact set featuring soft peach tones that blend easily for everyday looks. Includes a mix of matte and shimmer finishes, enriched with vitamin E for smooth, nourished cheeks.', 1500.00, 25, 4, 411888, '', '3 shades for a natural peach glow.', 'assets/products/product_411888.png'),
(39, 'Neutral Glow Blusher Set', 'Contains 4 neutral shades suitable for all skin tones. Softly pigmented and easy to blend, this set gives cheeks a natural, healthy glow without looking heavy.', 1600.00, 20, 4, 506006, '', 'Subtle neutral shades for every skin tone.', 'assets/products/product_506006.png'),
(40, 'Stippling Foundation Brush', 'Dual-fiber brush ideal for layering foundation gradually. Delivers light, buildable coverage while giving a natural, soft-focus finish.', 1300.00, 20, 4, 970393, '', 'Lightweight coverage with flawless blending.', 'assets/products/product_970393.png');

-- --------------------------------------------------------



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
INSERT INTO `order` (`order_id`, `order_date`, `total_price`, `user_id`, `house_no`, `street1`, `street2`, `city`, `postal_code`, `payment_method`) VALUES
(1, '2025-09-18 11:11:14', 1120.00, 3, '12', 'Vijaya Mawatha', 'Randombe', 'Ambalangoda', '80300', 'Cash on Delivery');


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

--
-- Dumping data for table `review`
--

INSERT INTO `review` (`review_id`, `rating`, `comments`, `review_date`, `user_id`, `product_id`) VALUES
(1, 4, '“I’ve been using this face wash for a couple of weeks, and I really like how gentle it is on my skin. It cleanses thoroughly without leaving my face feeling dry or tight', '2025-09-18 11:16:49', 3, 2);

-- --------------------------------------------------------



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

INSERT INTO `order_item` (`order_item_id`, `quantity`, `price`, `order_id`, `product_id`) VALUES
(1, 1, 1120.00, 1, 2);


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
