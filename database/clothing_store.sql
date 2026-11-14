-- =====================================================================
-- Threadline Clothing Store - database for XAMPP / phpMyAdmin
-- How to use: phpMyAdmin > Import > choose this file > Import
-- It creates the database `corephp` (the name used in config/app.php),
-- all tables, and demo data.
-- =====================================================================

CREATE DATABASE IF NOT EXISTS corephp CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE corephp;

SET FOREIGN_KEY_CHECKS = 0;
DROP TABLE IF EXISTS order_items, orders, products, categories, users, currency_rates;
SET FOREIGN_KEY_CHECKS = 1;

-- Customers and admins
CREATE TABLE users (
    id         INT AUTO_INCREMENT PRIMARY KEY,
    name       VARCHAR(100) NOT NULL,
    email      VARCHAR(150) NOT NULL UNIQUE,
    password   VARCHAR(255) NOT NULL,
    role       ENUM('customer','admin') NOT NULL DEFAULT 'customer',
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Product categories (T-Shirts, Shirts, ...)
CREATE TABLE categories (
    id   INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(80) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Clothes for sale
CREATE TABLE products (
    id          INT AUTO_INCREMENT PRIMARY KEY,
    category_id INT NOT NULL,
    name        VARCHAR(150) NOT NULL,
    color       VARCHAR(40),
    description TEXT,
    price       DECIMAL(10,2) NOT NULL,
    sizes       VARCHAR(60) NOT NULL,
    stock       INT NOT NULL DEFAULT 0,
    image       VARCHAR(255),
    is_featured TINYINT(1) NOT NULL DEFAULT 0,
    created_at  DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (category_id) REFERENCES categories(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- One row per checkout
CREATE TABLE orders (
    id         INT AUTO_INCREMENT PRIMARY KEY,
    user_id    INT NOT NULL,
    total      DECIMAL(10,2) NOT NULL,
    address    VARCHAR(255) NOT NULL,
    phone      VARCHAR(20) NOT NULL,
    status     ENUM('pending','shipped','delivered','cancelled') NOT NULL DEFAULT 'pending',
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- The products inside each order
CREATE TABLE order_items (
    id         INT AUTO_INCREMENT PRIMARY KEY,
    order_id   INT NOT NULL,
    product_id INT NOT NULL,
    size       VARCHAR(10) NOT NULL,
    quantity   INT NOT NULL,
    price      DECIMAL(10,2) NOT NULL,
    FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Exchange rates saved from the Frankfurter API (1 PHP = rate)
CREATE TABLE currency_rates (
    currency   CHAR(3) PRIMARY KEY,
    rate       DECIMAL(16,8) NOT NULL,
    updated_at DATETIME NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ---------------------------------------------------------------------
-- Demo data. Logins: admin@example.com / admin123
--                    juan@example.com  / password123
-- ---------------------------------------------------------------------
INSERT INTO users (id, name, email, password, role) VALUES
(1, 'Store Admin', 'admin@example.com', '$2y$10$vW4PNTX0oUZC7hLkBj9IeOyHBkjJjEGW8MxSu8rIz8eiOJ/cBkHQW', 'admin'),
(2, 'Juan Dela Cruz', 'juan@example.com', '$2y$10$SmAczozkMXs39XnXpDO6h.SVEkeN9wUQbksw6G0ePUDmTTZWgYmca', 'customer');

INSERT INTO categories (id, name) VALUES
(1, 'T-Shirts'),
(2, 'Shirts'),
(3, 'Hoodies & Sweatshirts'),
(4, 'Pants & Shorts'),
(5, 'Dresses & Skirts'),
(6, 'Outerwear'),
(7, 'Accessories');

INSERT INTO products (id, category_id, name, color, description, price, sizes, stock, image, is_featured) VALUES
(1, 1, 'Essential Crew Tee', 'White', 'Essential Crew Tee in White. Made from 100% combed cotton, 220 gsm. Relaxed everyday fit.', 499, 'XS,S,M,L,XL', 12, 'essential-crew-tee-white.svg', 1),
(2, 1, 'Essential Crew Tee', 'Black', 'Essential Crew Tee in Black. Made from 100% combed cotton, 220 gsm. Relaxed everyday fit.', 499, 'XS,S,M,L,XL', 8, 'essential-crew-tee-black.svg', 0),
(3, 1, 'Boxy Pocket Tee', 'Sage', 'Boxy Pocket Tee in Sage. Made from 100% cotton, 240 gsm. Relaxed everyday fit.', 549, 'XS,S,M,L,XL', 15, 'boxy-pocket-tee-sage.svg', 1),
(4, 1, 'Striped Breton Tee', 'Ecru / Navy', 'Striped Breton Tee in Ecru / Navy. Made from Cotton jersey. Relaxed everyday fit.', 799, 'XS,S,M,L,XL', 20, 'striped-breton-tee-ecru-navy.svg', 0),
(5, 2, 'Oxford Button-Down', 'Light Blue', 'Oxford Button-Down in Light Blue. Made from Cotton oxford cloth. Relaxed everyday fit.', 1299, 'S,M,L,XL', 6, 'oxford-button-down-light-blue.svg', 1),
(6, 2, 'Linen Camp Shirt', 'Sand', 'Linen Camp Shirt in Sand. Made from 100% linen. Relaxed everyday fit.', 1199, 'S,M,L,XL', 10, 'linen-camp-shirt-sand.svg', 0),
(7, 2, 'Flannel Overshirt', 'Rust', 'Flannel Overshirt in Rust. Made from Brushed cotton flannel. Relaxed everyday fit.', 1599, 'S,M,L,XL', 9, 'flannel-overshirt-rust.svg', 0),
(8, 3, 'Heavyweight Hoodie', 'Heather Grey', 'Heavyweight Hoodie in Heather Grey. Made from Cotton-poly fleece, 400 gsm. Relaxed everyday fit.', 1799, 'S,M,L,XL,XXL', 14, 'heavyweight-hoodie-heather-grey.svg', 1),
(9, 3, 'Fleece Zip Hoodie', 'Forest', 'Fleece Zip Hoodie in Forest. Made from Cotton-poly fleece. Relaxed everyday fit.', 1899, 'S,M,L,XL,XXL', 7, 'fleece-zip-hoodie-forest.svg', 0),
(10, 3, 'Relaxed Crew Sweatshirt', 'Oat', 'Relaxed Crew Sweatshirt in Oat. Made from French terry. Relaxed everyday fit.', 1099, 'S,M,L,XL,XXL', 11, 'relaxed-crew-sweatshirt-oat.svg', 0),
(11, 4, 'Straight-Leg Jeans', 'Indigo', 'Straight-Leg Jeans in Indigo. Made from 13 oz denim. Relaxed everyday fit.', 1999, '28,30,32,34,36', 18, 'straight-leg-jeans-indigo.svg', 1),
(12, 4, 'Wide-Leg Trousers', 'Charcoal', 'Wide-Leg Trousers in Charcoal. Made from Poly-viscose twill. Relaxed everyday fit.', 1699, '28,30,32,34,36', 5, 'wide-leg-trousers-charcoal.svg', 0),
(13, 4, 'Utility Cargo Pants', 'Olive', 'Utility Cargo Pants in Olive. Made from Cotton ripstop. Relaxed everyday fit.', 1349, '28,30,32,34,36', 12, 'utility-cargo-pants-olive.svg', 0),
(14, 4, 'Chino Shorts', 'Khaki', 'Chino Shorts in Khaki. Made from Stretch cotton twill. Relaxed everyday fit.', 899, '28,30,32,34', 8, 'chino-shorts-khaki.svg', 0),
(15, 5, 'Linen Midi Dress', 'Terracotta', 'Linen Midi Dress in Terracotta. Made from 100% linen. Relaxed everyday fit.', 2199, 'XS,S,M,L', 15, 'linen-midi-dress-terracotta.svg', 1),
(16, 5, 'Satin Slip Dress', 'Champagne', 'Satin Slip Dress in Champagne. Made from Satin-finish viscose. Relaxed everyday fit.', 1499, 'XS,S,M,L', 20, 'satin-slip-dress-champagne.svg', 0),
(17, 5, 'Pleated Midi Skirt', 'Dusty Rose', 'Pleated Midi Skirt in Dusty Rose. Made from Recycled polyester. Relaxed everyday fit.', 1299, 'XS,S,M,L', 6, 'pleated-midi-skirt-dusty-rose.svg', 0),
(18, 5, 'Denim Mini Skirt', 'Light Wash', 'Denim Mini Skirt in Light Wash. Made from 12 oz denim. Relaxed everyday fit.', 999, 'XS,S,M,L', 10, 'denim-mini-skirt-light-wash.svg', 0),
(19, 6, 'Denim Trucker Jacket', 'Mid Wash', 'Denim Trucker Jacket in Mid Wash. Made from 14 oz denim. Relaxed everyday fit.', 2499, 'S,M,L,XL', 9, 'denim-trucker-jacket-mid-wash.svg', 1),
(20, 6, 'Coach Jacket', 'Black', 'Coach Jacket in Black. Made from Water-resistant nylon. Relaxed everyday fit.', 2299, 'S,M,L,XL', 14, 'coach-jacket-black.svg', 0),
(21, 6, 'Packable Rain Shell', 'Mustard', 'Packable Rain Shell in Mustard. Made from Waterproof ripstop. Relaxed everyday fit.', 2299, 'S,M,L,XL', 7, 'packable-rain-shell-mustard.svg', 0),
(22, 7, 'Six-Panel Cap', 'Navy', 'Six-Panel Cap in Navy. Made from Washed cotton twill. Relaxed everyday fit.', 599, 'OS', 11, 'six-panel-cap-navy.svg', 0),
(23, 7, 'Canvas Tote Bag', 'Natural', 'Canvas Tote Bag in Natural. Made from 12 oz cotton canvas. Relaxed everyday fit.', 499, 'OS', 18, 'canvas-tote-bag-natural.svg', 0),
(24, 7, 'Ribbed Beanie', 'Burgundy', 'Ribbed Beanie in Burgundy. Made from Merino wool blend. Relaxed everyday fit.', 399, 'OS', 5, 'ribbed-beanie-burgundy.svg', 0);

INSERT INTO orders (id, user_id, total, address, phone, status, created_at) VALUES
(1, 2, 2997.00, '123 MacArthur Hwy, Angeles City, Pampanga', '09171234567', 'delivered', '2026-11-01 10:30:00'),
(2, 2, 1799.00, '123 MacArthur Hwy, Angeles City, Pampanga', '09171234567', 'pending', '2026-11-07 15:10:00');

INSERT INTO order_items (order_id, product_id, size, quantity, price) VALUES
(1, 1, 'M', 2, 499.00),
(1, 11, '32', 1, 1999.00),
(2, 8, 'L', 1, 1799.00);

-- Starting rates (2026-11-08). The app refreshes them from the API automatically.
INSERT INTO currency_rates (currency, rate, updated_at) VALUES
('USD', 0.015868, '2026-11-08 00:00:00'),
('EUR', 0.01415, '2026-11-08 00:00:00'),
('JPY', 2.51, '2026-11-08 00:00:00'),
('KRW', 21.462, '2026-11-08 00:00:00'),
('SGD', 0.020294, '2026-11-08 00:00:00');
