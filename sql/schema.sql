-- ===========================================================
-- QUICKBITE FOOD DELIVERY — Database Schema
-- MySQL 8+ / MariaDB
--
-- Creates the QuickBite Food Delivery database and all tables
-- required by the website.
--
-- Safe to re-run because every table uses
-- CREATE TABLE IF NOT EXISTS.
--
-- Load:
--    mysql -u root -p < sql/schema.sql
--
-- Then load sample data:
--    mysql -u root -p < sql/seed.sql
-- ===========================================================

CREATE DATABASE IF NOT EXISTS quick_bite
CHARACTER SET utf8mb4
COLLATE utf8mb4_unicode_ci;

USE quick_bite;

-- ===========================================================
-- Registered Users
-- ===========================================================
CREATE TABLE IF NOT EXISTS users (
    id INT UNSIGNED NOT NULL AUTO_INCREMENT,
    full_name VARCHAR(120) NOT NULL,
    email VARCHAR(190) NOT NULL,
    phone VARCHAR(20) NOT NULL,
    gender ENUM('female','male','other','prefer-not') NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    PRIMARY KEY (id),
    UNIQUE KEY uq_users_email (email)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ===========================================================
-- Food Categories
-- ===========================================================
CREATE TABLE IF NOT EXISTS categories (
    id INT UNSIGNED NOT NULL AUTO_INCREMENT,
    name VARCHAR(80) NOT NULL,
    slug VARCHAR(80) NOT NULL,
    description VARCHAR(255),

    PRIMARY KEY(id),
    UNIQUE KEY uq_categories_name(name),
    UNIQUE KEY uq_categories_slug(slug)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ===========================================================
-- Food Menu
-- ===========================================================
CREATE TABLE IF NOT EXISTS products (

    id INT UNSIGNED NOT NULL AUTO_INCREMENT,

    category_id INT UNSIGNED DEFAULT NULL,

    name VARCHAR(150) NOT NULL,

    kind ENUM('food','drink','combo') NOT NULL DEFAULT 'food',

    description TEXT,

    price DECIMAL(10,2) NOT NULL,

    availability ENUM('in_stock','limited','out_of_stock')
    NOT NULL DEFAULT 'in_stock',

    image_url VARCHAR(500),

    is_best_seller TINYINT(1) DEFAULT 0,

    is_featured TINYINT(1) DEFAULT 0,

    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    PRIMARY KEY(id),

    UNIQUE KEY uq_products_name(name),

    KEY idx_products_category(category_id),

    CONSTRAINT fk_products_category
        FOREIGN KEY(category_id)
        REFERENCES categories(id)
        ON DELETE SET NULL
        ON UPDATE CASCADE

) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ===========================================================
-- Promotions
-- ===========================================================
CREATE TABLE IF NOT EXISTS promotions (

    id INT UNSIGNED NOT NULL AUTO_INCREMENT,

    code VARCHAR(40) NOT NULL,

    title VARCHAR(150) NOT NULL,

    description VARCHAR(255),

    discount_percent DECIMAL(5,2) DEFAULT 0,

    min_order_amount DECIMAL(10,2) DEFAULT 0,

    is_active TINYINT(1) DEFAULT 1,

    starts_at DATE,

    ends_at DATE,

    PRIMARY KEY(id),

    UNIQUE KEY uq_promotions_code(code)

) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ===========================================================
-- Orders
-- ===========================================================
CREATE TABLE IF NOT EXISTS orders (

    id INT UNSIGNED NOT NULL AUTO_INCREMENT,

    user_id INT UNSIGNED DEFAULT NULL,

    customer_name VARCHAR(120) NOT NULL,

    customer_email VARCHAR(190) NOT NULL,

    customer_phone VARCHAR(20) NOT NULL,

    delivery_address VARCHAR(255) NOT NULL,

    delivery_time VARCHAR(80),

    promo_code VARCHAR(40),

    subtotal DECIMAL(10,2) DEFAULT 0,

    discount DECIMAL(10,2) DEFAULT 0,

    delivery_fee DECIMAL(10,2) DEFAULT 0,

    total DECIMAL(10,2) DEFAULT 0,

    status ENUM(
        'pending',
        'confirmed',
        'preparing',
        'out_for_delivery',
        'delivered',
        'cancelled'
    ) DEFAULT 'pending',

    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    PRIMARY KEY(id),

    KEY idx_orders_user(user_id),

    CONSTRAINT fk_orders_user
        FOREIGN KEY(user_id)
        REFERENCES users(id)
        ON DELETE SET NULL
        ON UPDATE CASCADE

) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ===========================================================
-- Order Items
-- ===========================================================
CREATE TABLE IF NOT EXISTS order_items (

    id INT UNSIGNED NOT NULL AUTO_INCREMENT,

    order_id INT UNSIGNED NOT NULL,

    product_id INT UNSIGNED DEFAULT NULL,

    product_name VARCHAR(150) NOT NULL,

    unit_price DECIMAL(10,2) NOT NULL,

    quantity INT UNSIGNED DEFAULT 1,

    line_total DECIMAL(10,2) NOT NULL,

    PRIMARY KEY(id),

    KEY idx_order_items_order(order_id),

    KEY idx_order_items_product(product_id),

    CONSTRAINT fk_order_items_order
        FOREIGN KEY(order_id)
        REFERENCES orders(id)
        ON DELETE CASCADE
        ON UPDATE CASCADE,

    CONSTRAINT fk_order_items_product
        FOREIGN KEY(product_id)
        REFERENCES products(id)
        ON DELETE SET NULL
        ON UPDATE CASCADE

) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ===========================================================
-- Contact Messages
-- ===========================================================
CREATE TABLE IF NOT EXISTS contact_messages (

    id INT UNSIGNED NOT NULL AUTO_INCREMENT,

    name VARCHAR(120) NOT NULL,

    email VARCHAR(190) NOT NULL,

    phone VARCHAR(20),

    department VARCHAR(80),

    message TEXT NOT NULL,

    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    PRIMARY KEY(id)

) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ===========================================================
-- Departments
-- ===========================================================
CREATE TABLE IF NOT EXISTS departments (

    id INT UNSIGNED NOT NULL AUTO_INCREMENT,

    name VARCHAR(80) NOT NULL,

    contact_person VARCHAR(120),

    phone VARCHAR(30),

    email VARCHAR(190),

    PRIMARY KEY(id),

    UNIQUE KEY uq_departments_name(name)

) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
