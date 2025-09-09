-- Users Table
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('customer', 'admin') DEFAULT 'customer',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);


-- Glass Categories
CREATE TABLE glass_categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100),
    area_price DECIMAL(10,2)
);

-- Calculator Options (optional extras, shapes etc.)
CREATE TABLE calculator_options (
    id INT AUTO_INCREMENT PRIMARY KEY,
    type ENUM('shape', 'extra', 'vent', 'toughened', 'spacer_color'),
    name VARCHAR(100),
    price_type ENUM('fixed','percent','linear'),
    price_value DECIMAL(10,2),
    image VARCHAR(255) NULL
);

-- Orders
CREATE TABLE orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    total DECIMAL(10,2),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Order Items
CREATE TABLE order_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT,
    product_type ENUM('single','double','triple'),
    details TEXT,
    price DECIMAL(10,2)
);
