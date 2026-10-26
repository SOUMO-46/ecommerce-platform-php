CREATE DATABASE IF NOT EXISTS ecommerce CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE ecommerce;

DROP TABLE IF EXISTS order_items;
DROP TABLE IF EXISTS orders;
DROP TABLE IF EXISTS cart_items;
DROP TABLE IF EXISTS cart;
DROP TABLE IF EXISTS products;
DROP TABLE IF EXISTS categories;
DROP TABLE IF EXISTS users;

CREATE TABLE users (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('customer','admin') NOT NULL DEFAULT 'customer',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE categories (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL UNIQUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE products (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    category_id INT UNSIGNED NULL,
    name VARCHAR(150) NOT NULL,
    description TEXT NULL,
    price DECIMAL(10,2) NOT NULL DEFAULT 0,
    stock INT NOT NULL DEFAULT 0,
    image VARCHAR(255) NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT fk_products_category
        FOREIGN KEY (category_id) REFERENCES categories(id)
        ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB;

CREATE TABLE cart (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id INT UNSIGNED NOT NULL UNIQUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_cart_user
        FOREIGN KEY (user_id) REFERENCES users(id)
        ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB;

CREATE TABLE cart_items (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    cart_id INT UNSIGNED NOT NULL,
    product_id INT UNSIGNED NOT NULL,
    quantity INT NOT NULL DEFAULT 1,
    UNIQUE KEY uq_cart_product (cart_id, product_id),
    CONSTRAINT fk_cart_items_cart
        FOREIGN KEY (cart_id) REFERENCES cart(id)
        ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT fk_cart_items_product
        FOREIGN KEY (product_id) REFERENCES products(id)
        ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB;

CREATE TABLE orders (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id INT UNSIGNED NOT NULL,
    total_amount DECIMAL(10,2) NOT NULL DEFAULT 0,
    status ENUM('pending','confirmed','shipped','delivered','cancelled') NOT NULL DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_orders_user
        FOREIGN KEY (user_id) REFERENCES users(id)
        ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB;

CREATE TABLE order_items (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    order_id INT UNSIGNED NOT NULL,
    product_id INT UNSIGNED NULL,
    product_name VARCHAR(150) NOT NULL,
    price DECIMAL(10,2) NOT NULL,
    quantity INT NOT NULL,
    CONSTRAINT fk_order_items_order
        FOREIGN KEY (order_id) REFERENCES orders(id)
        ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT fk_order_items_product
        FOREIGN KEY (product_id) REFERENCES products(id)
        ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB;

INSERT INTO categories (name) VALUES
('Mobiles'), ('Laptops'), ('Accessories');

INSERT INTO products (category_id, name, description, price, stock)
SELECT id, 'Sample Smartphone', 'Demo smartphone for testing.', 15999.00, 20
FROM categories WHERE name = 'Mobiles';

INSERT INTO products (category_id, name, description, price, stock)
SELECT id, 'Sample Laptop', 'Demo laptop for testing.', 54999.00, 10
FROM categories WHERE name = 'Laptops';



-- 30 Demo Products
-- Each product includes category, name, description, price and stock.
INSERT INTO products (category_id, name, description, price, stock) VALUES
((SELECT id FROM categories WHERE name = 'Mobiles'), 'Samsung Galaxy A55 5G', 'Demo mid-range smartphone with a vibrant display, multi-camera setup and 5G connectivity.', 28999.00, 18),
((SELECT id FROM categories WHERE name = 'Mobiles'), 'Apple iPhone 15', 'Demo premium smartphone with advanced photography, smooth performance and long-term software support.', 59900.00, 12),
((SELECT id FROM categories WHERE name = 'Mobiles'), 'OnePlus Nord CE 4', 'Demo 5G phone featuring a large AMOLED display and fast charging.', 24999.00, 20),
((SELECT id FROM categories WHERE name = 'Mobiles'), 'Xiaomi Redmi Note 13 Pro', 'Demo smartphone with a high-resolution camera and bright AMOLED screen.', 24999.00, 25),
((SELECT id FROM categories WHERE name = 'Mobiles'), 'Realme Narzo 70 Pro', 'Demo 5G smartphone designed for everyday use, photography and entertainment.', 19999.00, 16),
((SELECT id FROM categories WHERE name = 'Mobiles'), 'Motorola Edge 50 Fusion', 'Demo phone with a curved pOLED display, clean software experience and 5G.', 22999.00, 14),
((SELECT id FROM categories WHERE name = 'Mobiles'), 'Nothing Phone (2a)', 'Demo smartphone with a distinctive transparent-inspired design and smooth display.', 23999.00, 11),
((SELECT id FROM categories WHERE name = 'Mobiles'), 'Samsung Galaxy M35 5G', 'Demo long-lasting 5G smartphone with a large battery and AMOLED display.', 19999.00, 22),
((SELECT id FROM categories WHERE name = 'Mobiles'), 'Vivo V30e', 'Demo smartphone with portrait-focused camera features and an elegant design.', 27999.00, 9),
((SELECT id FROM categories WHERE name = 'Mobiles'), 'OPPO Reno 11 5G', 'Demo 5G smartphone with portrait photography features and fast charging.', 29999.00, 10),
((SELECT id FROM categories WHERE name = 'Laptops'), 'Dell Inspiron 15', 'Demo everyday laptop suitable for study, office work and web development.', 52999.00, 8),
((SELECT id FROM categories WHERE name = 'Laptops'), 'HP Pavilion 14', 'Demo compact laptop for productivity, video calls and daily computing.', 58999.00, 7),
((SELECT id FROM categories WHERE name = 'Laptops'), 'Lenovo IdeaPad Slim 3', 'Demo lightweight laptop for students, browsing and office applications.', 42990.00, 15),
((SELECT id FROM categories WHERE name = 'Laptops'), 'ASUS VivoBook 15', 'Demo laptop with a roomy display for multitasking and general productivity.', 46990.00, 10),
((SELECT id FROM categories WHERE name = 'Laptops'), 'Acer Aspire 5', 'Demo productivity laptop with balanced performance for work and learning.', 49990.00, 9),
((SELECT id FROM categories WHERE name = 'Laptops'), 'Apple MacBook Air M2', 'Demo thin-and-light laptop with efficient performance and a high-quality display.', 89900.00, 5),
((SELECT id FROM categories WHERE name = 'Laptops'), 'Lenovo LOQ Gaming Laptop', 'Demo gaming laptop intended for gaming, creative work and demanding applications.', 74990.00, 6),
((SELECT id FROM categories WHERE name = 'Laptops'), 'MSI Thin 15', 'Demo entry-level gaming laptop with dedicated graphics for compatible workloads.', 69990.00, 4),
((SELECT id FROM categories WHERE name = 'Laptops'), 'HP 15s', 'Demo general-purpose laptop suitable for home, office and student use.', 45990.00, 12),
((SELECT id FROM categories WHERE name = 'Laptops'), 'ASUS TUF Gaming F15', 'Demo rugged-style gaming laptop designed for demanding games and multitasking.', 82990.00, 5),
((SELECT id FROM categories WHERE name = 'Accessories'), 'Wireless Bluetooth Mouse', 'Demo ergonomic wireless mouse for laptops and desktop computers.', 799.00, 50),
((SELECT id FROM categories WHERE name = 'Accessories'), 'Mechanical Gaming Keyboard', 'Demo wired mechanical-style keyboard for gaming and daily typing.', 2499.00, 24),
((SELECT id FROM categories WHERE name = 'Accessories'), 'USB-C Fast Charger 65W', 'Demo USB-C power adapter for compatible phones, tablets and laptops.', 1899.00, 30),
((SELECT id FROM categories WHERE name = 'Accessories'), 'USB-C Hub 6-in-1', 'Demo multi-port hub adding useful USB and display connections to compatible devices.', 2199.00, 20),
((SELECT id FROM categories WHERE name = 'Accessories'), 'Portable SSD 1TB', 'Demo external solid-state drive for backups and portable file storage.', 6999.00, 12),
((SELECT id FROM categories WHERE name = 'Accessories'), 'Laptop Backpack', 'Demo padded backpack with compartments for a laptop and everyday essentials.', 1499.00, 35),
((SELECT id FROM categories WHERE name = 'Accessories'), 'Full HD Webcam', 'Demo USB webcam for online classes, meetings and video calls.', 2299.00, 18),
((SELECT id FROM categories WHERE name = 'Accessories'), 'Over-Ear Wireless Headphones', 'Demo wireless headphones for music, calls and entertainment.', 3499.00, 22),
((SELECT id FROM categories WHERE name = 'Accessories'), 'Bluetooth Portable Speaker', 'Demo portable wireless speaker for listening to music at home or outdoors.', 1999.00, 17),
((SELECT id FROM categories WHERE name = 'Accessories'), '1080p Monitor 24-inch', 'Demo 24-inch Full HD monitor for office tasks, study and home computing.', 8999.00, 8);

-- After registering your own account, make it admin with:
-- UPDATE users SET role='admin' WHERE email='your@email.com';
