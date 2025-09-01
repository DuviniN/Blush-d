
-- Stores product categories.
-- -----------------------------------------------------
CREATE TABLE Category (
  category_id INT PRIMARY KEY AUTO_INCREMENT,
  name VARCHAR(255) NOT NULL
  
);

-- -----------------------------------------------------
-- Table `User`
-- Stores user account information.
-- -----------------------------------------------------
CREATE TABLE User (
  user_id INT PRIMARY KEY AUTO_INCREMENT,
  first_name VARCHAR(100) NOT NULL,
  last_name VARCHAR(100) NOT NULL,
  email VARCHAR(255) NOT NULL UNIQUE,
  password VARCHAR(255) NOT NULL, -- In a real application, this should be a hashed password.
  address VARCHAR(255),
  phone_number VARCHAR(20)
);

-- -----------------------------------------------------
-- Table `Product`
-- Stores information about individual products.
-- It has a foreign key relationship with the Category table.
-- -----------------------------------------------------
CREATE TABLE Product (
  product_id INT PRIMARY KEY AUTO_INCREMENT,
  name VARCHAR(255) NOT NULL, -- Added a name field, which is essential for a product.
  description TEXT,
  price DECIMAL(10, 2) NOT NULL,
  stock INT NOT NULL,
  category_id INT,
  FOREIGN KEY (category_id) REFERENCES Category(category_id) ON DELETE SET NULL
);

-- -----------------------------------------------------
-- Table `Order`
-- Stores information about customer orders.
-- It has a foreign key relationship with the User table.
-- -----------------------------------------------------
CREATE TABLE `Order` ( -- Using backticks because Order is a reserved keyword in SQL
  order_id INT PRIMARY KEY AUTO_INCREMENT,
  order_date DATETIME NOT NULL,
  total_price DECIMAL(10, 2) NOT NULL,
  user_id INT,
  FOREIGN KEY (user_id) REFERENCES User(user_id) ON DELETE CASCADE
);

-- -----------------------------------------------------
-- Table `Order_Item`
-- This is a junction table linking Orders and Products.
-- It stores details about each product within an order.
-- -----------------------------------------------------
CREATE TABLE Order_Item (
  order_item_id INT PRIMARY KEY AUTO_INCREMENT,
  quantity INT NOT NULL,
  price DECIMAL(10, 2) NOT NULL, -- Price per unit at the time of purchase
  order_id INT,
  product_id INT,
  FOREIGN KEY (order_id) REFERENCES `Order`(order_id) ON DELETE CASCADE,
  FOREIGN KEY (product_id) REFERENCES Product(product_id) ON DELETE SET NULL
);

-- -----------------------------------------------------
-- Table `Payment`
-- Stores payment details for each order.
-- -----------------------------------------------------
CREATE TABLE Payment (
  payment_id INT PRIMARY KEY AUTO_INCREMENT,
  payment_date DATETIME NOT NULL,
  payment_method VARCHAR(100) NOT NULL,
  amount DECIMAL(10, 2) NOT NULL,
  order_id INT,
  user_id INT,
  FOREIGN KEY (user_id) REFERENCES User(user_id),
  FOREIGN KEY (order_id) REFERENCES `Order`(order_id)
);

-- -----------------------------------------------------
-- Table `Review`
-- Stores customer reviews for products.
-- -----------------------------------------------------
CREATE TABLE Review (
  review_id INT PRIMARY KEY AUTO_INCREMENT,
  rating INT NOT NULL CHECK (rating >= 1 AND rating <= 5),
  comments TEXT,
  review_date DATETIME NOT NULL,
  user_id INT,
  product_id INT,
  FOREIGN KEY (user_id) REFERENCES User(user_id) ON DELETE CASCADE,
  FOREIGN KEY (product_id) REFERENCES Product(product_id) ON DELETE CASCADE
);

-- -----------------------------------------------------
-- Table `Cart`
-- Stores the items in a user's shopping cart.
-- Each row represents a product in a user's cart.
-- -----------------------------------------------------
CREATE TABLE Cart (
  cart_id INT PRIMARY KEY AUTO_INCREMENT,
  user_id INT NOT NULL,
  product_id INT NOT NULL,
  quantity INT NOT NULL,
  FOREIGN KEY (user_id) REFERENCES User(user_id) ON DELETE CASCADE,
  FOREIGN KEY (product_id) REFERENCES Product(product_id) ON DELETE CASCADE
);















-- Insert mock data for Category
INSERT INTO Category (name) VALUES
('Skincare'),
('Makeup'),
('Fragrances'),
('Haircare'),
('Bodycare');

-- Insert mock data for User
INSERT INTO User (first_name, last_name, email, password, address, phone_number) VALUES
('Alice', 'Johnson', 'alice@example.com', 'hashed_pw1', '123 Main St, New York', '1234567890'),
('Bob', 'Smith', 'bob@example.com', 'hashed_pw2', '456 Park Ave, Los Angeles', '2345678901'),
('Charlie', 'Brown', 'charlie@example.com', 'hashed_pw3', '789 Elm St, Chicago', '3456789012'),
('Diana', 'Williams', 'diana@example.com', 'hashed_pw4', '101 Maple Rd, Houston', '4567890123'),
('Ethan', 'Taylor', 'ethan@example.com', 'hashed_pw5', '202 Pine St, Miami', '5678901234');

-- Insert mock data for Product
INSERT INTO Product (name, description, price, stock, category_id) VALUES
('Moisturizing Cream', 'Hydrating cream for dry skin.', 19.99, 50, 1),
('Foundation', 'Liquid foundation for all-day coverage.', 25.50, 100, 2),
('Perfume', 'Floral fragrance for women.', 49.99, 30, 3),
('Shampoo', 'Herbal shampoo for smooth hair.', 12.75, 80, 4),
('Body Lotion', 'Aloe vera body lotion.', 15.00, 60, 5);

-- Insert mock data for Order
INSERT INTO `Order` (order_date, total_price, user_id) VALUES
('2025-08-25 10:30:00', 45.49, 1),
('2025-08-26 14:15:00', 49.99, 2),
('2025-08-27 09:45:00', 38.75, 3),
('2025-08-28 11:20:00', 62.99, 4);

-- Insert mock data for Order_Item
INSERT INTO Order_Item (quantity, price, order_id, product_id) VALUES
(2, 19.99, 1, 1), -- Alice bought 2 Moisturizing Cream
(1, 25.50, 1, 2), -- Alice bought 1 Foundation
(1, 49.99, 2, 3), -- Bob bought Perfume
(1, 12.75, 3, 4), -- Charlie bought Shampoo
(1, 25.50, 4, 2), -- Diana bought Foundation
(1, 15.00, 4, 5); -- Diana bought Body Lotion

-- Insert mock data for Payment
INSERT INTO Payment (payment_date, payment_method, amount, order_id, user_id) VALUES
('2025-08-25 10:35:00', 'Credit Card', 45.49, 1, 1),
('2025-08-26 14:20:00', 'PayPal', 49.99, 2, 2),
('2025-08-27 09:50:00', 'Debit Card', 38.75, 3, 3),
('2025-08-28 11:25:00', 'Credit Card', 62.99, 4, 4);

-- Insert mock data for Review
INSERT INTO Review (rating, comments, review_date, user_id, product_id) VALUES
(5, 'Excellent cream, my skin feels amazing!', '2025-08-26 12:00:00', 1, 1),
(4, 'Good coverage foundation.', '2025-08-27 15:30:00', 1, 2),
(3, 'The perfume is okay, a bit strong.', '2025-08-28 18:00:00', 2, 3),
(5, 'Shampoo makes my hair silky smooth.', '2025-08-29 10:00:00', 3, 4),
(4, 'Nice lotion, refreshing scent.', '2025-08-30 09:45:00', 4, 5);

-- Insert mock data for Cart
INSERT INTO Cart (user_id, product_id, quantity) VALUES
(1, 3, 1), -- Alice has Perfume in her cart
(2, 4, 2), -- Bob has 2 Shampoos in his cart
(3, 5, 1), -- Charlie has Body Lotion
(5, 1, 1), -- Ethan has Moisturizing Cream
(5, 2, 1); -- Ethan also has Foundation
