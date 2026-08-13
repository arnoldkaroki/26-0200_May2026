-- ============================================================
-- QUICKBITE DELIVERY
-- Database Schema
-- MySQL 8+ / MariaDB
--
-- Import this file into your MySQL database.
-- Safe to re-run because all tables use
-- CREATE TABLE IF NOT EXISTS.
-- ============================================================


/*==============================================================
    USERS
==============================================================*/

CREATE TABLE IF NOT EXISTS users (

    id INT UNSIGNED NOT NULL AUTO_INCREMENT,

    full_name VARCHAR(120) NOT NULL,

    email VARCHAR(190) NOT NULL,

    phone VARCHAR(20) NOT NULL,

    gender ENUM(
        'male',
        'female',
        'other',
        'prefer-not'
    ) NOT NULL,

    created_at TIMESTAMP
        NOT NULL
        DEFAULT CURRENT_TIMESTAMP,

    PRIMARY KEY(id),

    UNIQUE KEY uq_users_email(email)

) ENGINE=InnoDB
DEFAULT CHARSET=utf8mb4
COLLATE=utf8mb4_unicode_ci;


/*==============================================================
    MENU CATEGORIES
==============================================================*/

CREATE TABLE IF NOT EXISTS categories (

    id INT UNSIGNED NOT NULL AUTO_INCREMENT,

    name VARCHAR(80) NOT NULL,

    slug VARCHAR(80) NOT NULL,

    description VARCHAR(255) DEFAULT NULL,

    PRIMARY KEY(id),

    UNIQUE KEY uq_categories_name(name),

    UNIQUE KEY uq_categories_slug(slug)

) ENGINE=InnoDB
DEFAULT CHARSET=utf8mb4
COLLATE=utf8mb4_unicode_ci;


/*==============================================================
    MENU ITEMS
==============================================================*/

CREATE TABLE IF NOT EXISTS products (

    id INT UNSIGNED NOT NULL AUTO_INCREMENT,

    category_id INT UNSIGNED DEFAULT NULL,

    name VARCHAR(150) NOT NULL,

    kind ENUM(
        'food',
        'drink'
    ) NOT NULL DEFAULT 'food',

    description TEXT DEFAULT NULL,

    food_type VARCHAR(80) DEFAULT NULL,

    main_ingredient VARCHAR(80) DEFAULT NULL,

    preparation_time VARCHAR(50) DEFAULT NULL,

    spice_level ENUM(
        'Mild',
        'Medium',
        'Hot',
        'Extra Hot'
    ) DEFAULT NULL,

    price DECIMAL(10,2) NOT NULL,

    availability ENUM(
        'in_stock',
        'limited',
        'out_of_stock'
    ) NOT NULL DEFAULT 'in_stock',

    image_url VARCHAR(500) DEFAULT NULL,

    is_popular TINYINT(1)
        NOT NULL
        DEFAULT 0,

    is_featured TINYINT(1)
        NOT NULL
        DEFAULT 0,

    created_at TIMESTAMP
        NOT NULL
        DEFAULT CURRENT_TIMESTAMP,

    PRIMARY KEY(id),

    UNIQUE KEY uq_products_name(name),

    KEY idx_products_category(category_id),

    CONSTRAINT fk_products_category

        FOREIGN KEY(category_id)

        REFERENCES categories(id)

        ON DELETE SET NULL

        ON UPDATE CASCADE

) ENGINE=InnoDB
DEFAULT CHARSET=utf8mb4
COLLATE=utf8mb4_unicode_ci;


/*==============================================================
    PROMOTIONS
==============================================================*/

CREATE TABLE IF NOT EXISTS promotions (

    id INT UNSIGNED NOT NULL AUTO_INCREMENT,

    code VARCHAR(40) NOT NULL,

    title VARCHAR(150) NOT NULL,

    description VARCHAR(255) DEFAULT NULL,

    discount_percent DECIMAL(5,2)
        NOT NULL
        DEFAULT 0,

    min_order_amount DECIMAL(10,2)
        NOT NULL
        DEFAULT 0,

    is_active TINYINT(1)
        NOT NULL
        DEFAULT 1,

    starts_at DATE DEFAULT NULL,

    ends_at DATE DEFAULT NULL,

    PRIMARY KEY(id),

    UNIQUE KEY uq_promotions_code(code)

) ENGINE=InnoDB
DEFAULT CHARSET=utf8mb4
COLLATE=utf8mb4_unicode_ci;


/*==============================================================
    ORDERS
==============================================================*/

CREATE TABLE IF NOT EXISTS orders (

    id INT UNSIGNED NOT NULL AUTO_INCREMENT,

    user_id INT UNSIGNED DEFAULT NULL,

    customer_name VARCHAR(120) NOT NULL,

    customer_email VARCHAR(190) NOT NULL,

    customer_phone VARCHAR(20) NOT NULL,

    delivery_address VARCHAR(255) NOT NULL,

    delivery_time VARCHAR(80) DEFAULT NULL,

    promo_code VARCHAR(40) DEFAULT NULL,

    subtotal DECIMAL(10,2)
        NOT NULL
        DEFAULT 0,

    discount DECIMAL(10,2)
        NOT NULL
        DEFAULT 0,

    delivery_fee DECIMAL(10,2)
        NOT NULL
        DEFAULT 0,

    total DECIMAL(10,2)
        NOT NULL
        DEFAULT 0,

    status ENUM(

        'pending',

        'confirmed',

        'preparing',

        'out_for_delivery',

        'delivered',

        'cancelled'

    )

    NOT NULL

    DEFAULT 'pending',

    created_at TIMESTAMP

        NOT NULL

        DEFAULT CURRENT_TIMESTAMP,

    PRIMARY KEY(id),

    KEY idx_orders_user(user_id),

    CONSTRAINT fk_orders_user

        FOREIGN KEY(user_id)

        REFERENCES users(id)

        ON DELETE SET NULL

        ON UPDATE CASCADE

) ENGINE=InnoDB
DEFAULT CHARSET=utf8mb4
COLLATE=utf8mb4_unicode_ci;


/*==============================================================
    ORDER ITEMS
==============================================================*/

CREATE TABLE IF NOT EXISTS order_items (

    id INT UNSIGNED NOT NULL AUTO_INCREMENT,

    order_id INT UNSIGNED NOT NULL,

    product_id INT UNSIGNED DEFAULT NULL,

    product_name VARCHAR(150) NOT NULL,

    unit_price DECIMAL(10,2) NOT NULL,

    quantity INT UNSIGNED
        NOT NULL
        DEFAULT 1,

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

) ENGINE=InnoDB
DEFAULT CHARSET=utf8mb4
COLLATE=utf8mb4_unicode_ci;


/*==============================================================
    CONTACT MESSAGES
==============================================================*/

CREATE TABLE IF NOT EXISTS contact_messages (

    id INT UNSIGNED NOT NULL AUTO_INCREMENT,

    name VARCHAR(120) NOT NULL,

    email VARCHAR(190) NOT NULL,

    phone VARCHAR(20) DEFAULT NULL,

    subject VARCHAR(100) DEFAULT NULL,

    message TEXT NOT NULL,

    created_at TIMESTAMP
        NOT NULL
        DEFAULT CURRENT_TIMESTAMP,

    PRIMARY KEY(id)

) ENGINE=InnoDB
DEFAULT CHARSET=utf8mb4
COLLATE=utf8mb4_unicode_ci;


/*==============================================================
    DEPARTMENTS
==============================================================*/

CREATE TABLE IF NOT EXISTS departments (

    id INT UNSIGNED NOT NULL AUTO_INCREMENT,

    name VARCHAR(80) NOT NULL,

    contact_person VARCHAR(120) DEFAULT NULL,

    phone VARCHAR(30) DEFAULT NULL,

    email VARCHAR(190) DEFAULT NULL,

    PRIMARY KEY(id),

    UNIQUE KEY uq_departments_name(name)

) ENGINE=InnoDB
DEFAULT CHARSET=utf8mb4
COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- QUICKBITE DELIVERY
-- Seed Data
-- ============================================================


/*==============================================================
    MENU CATEGORIES
==============================================================*/

INSERT IGNORE INTO categories (name, slug, description) VALUES

('Burgers','burgers','Freshly grilled beef and chicken burgers.'),

('Pizza','pizza','Stone baked pizzas with premium toppings.'),

('Chicken','chicken','Crispy fried and grilled chicken meals.'),

('Fries','fries','French fries and potato snacks.'),

('Wraps','wraps','Chicken and beef wraps.'),

('Drinks','drinks','Soft drinks, juices and bottled water.'),

('Desserts','desserts','Sweet treats and ice cream.');


/*==============================================================
    MENU ITEMS
==============================================================*/

INSERT IGNORE INTO products
(
category_id,
name,
kind,
description,
food_type,
main_ingredient,
preparation_time,
spice_level,
price,
availability,
image_url,
is_popular,
is_featured
)
VALUES

(
(SELECT id FROM categories WHERE slug='burgers'),
'Classic Beef Burger',
'food',
'Juicy grilled beef burger served with cheese and lettuce.',
'Burger',
'Beef',
'20 mins',
'Medium',
650.00,
'in_stock',
'images/beef-burger.jpg',
1,
1
),

(
(SELECT id FROM categories WHERE slug='burgers'),
'Chicken Burger',
'food',
'Crispy chicken fillet with fresh vegetables.',
'Burger',
'Chicken',
'18 mins',
'Mild',
600.00,
'in_stock',
'images/chicken-burger.jpg',
1,
0
),

(
(SELECT id FROM categories WHERE slug='pizza'),
'Pepperoni Pizza',
'food',
'Large pepperoni pizza topped with mozzarella.',
'Pizza',
'Pepperoni',
'30 mins',
'Medium',
1200.00,
'in_stock',
'images/pepperoni-pizza.jpg',
1,
1
),

(
(SELECT id FROM categories WHERE slug='pizza'),
'BBQ Chicken Pizza',
'food',
'Chicken pizza with BBQ sauce.',
'Pizza',
'Chicken',
'30 mins',
'Mild',
1300.00,
'in_stock',
'images/bbq-pizza.jpg',
0,
1
),

(
(SELECT id FROM categories WHERE slug='chicken'),
'Fried Chicken Meal',
'food',
'Three pieces of crispy fried chicken with fries.',
'Chicken',
'Chicken',
'25 mins',
'Hot',
900.00,
'in_stock',
'images/fried-chicken.jpg',
1,
0
),

(
(SELECT id FROM categories WHERE slug='fries'),
'Large Fries',
'food',
'Crispy golden French fries.',
'Fries',
'Potatoes',
'10 mins',
'Mild',
300.00,
'in_stock',
'images/fries.jpg',
0,
0
),

(
(SELECT id FROM categories WHERE slug='wraps'),
'Chicken Wrap',
'food',
'Grilled chicken wrap with fresh vegetables.',
'Wrap',
'Chicken',
'15 mins',
'Mild',
550.00,
'in_stock',
'images/chicken-wrap.jpg',
0,
0
),

(
(SELECT id FROM categories WHERE slug='drinks'),
'Coca-Cola',
'drink',
'500ml Coca-Cola.',
'Soft Drink',
'Carbonated Drink',
'Ready',
NULL,
150.00,
'in_stock',
'images/coke.jpg',
0,
0
),

(
(SELECT id FROM categories WHERE slug='drinks'),
'Orange Juice',
'drink',
'Fresh orange juice.',
'Juice',
'Orange',
'Ready',
NULL,
250.00,
'in_stock',
'images/orange-juice.jpg',
0,
0
),

(
(SELECT id FROM categories WHERE slug='desserts'),
'Vanilla Ice Cream',
'food',
'Creamy vanilla ice cream.',
'Dessert',
'Milk',
'Ready',
NULL,
350.00,
'in_stock',
'images/icecream.jpg',
1,
0
);


/*==============================================================
    PROMOTIONS
==============================================================*/

INSERT IGNORE INTO promotions
(
code,
title,
description,
discount_percent,
min_order_amount,
is_active
)
VALUES

(
'WELCOME10',
'Welcome Offer',
'10% off your first QuickBite order.',
10,
0,
1
),

(
'QUICK20',
'QuickBite Special',
'20% discount on orders above KES 2,500.',
20,
2500,
1
),

(
'FREEDEL',
'Free Delivery',
'Free delivery on orders above KES 2,500.',
0,
2500,
1
);


/*==============================================================
    DEPARTMENTS
==============================================================*/

INSERT IGNORE INTO departments
(
name,
contact_person,
phone,
email
)
VALUES

(
'Orders',
'Brian Mwangi',
'0712345678',
'orders@quickbite.co.ke'
),

(
'Customer Support',
'Faith Njeri',
'0723456789',
'support@quickbite.co.ke'
),

(
'Deliveries',
'Kevin Otieno',
'0734567890',
'delivery@quickbite.co.ke'
),

(
'Finance',
'Jane Wambui',
'0745678901',
'finance@quickbite.co.ke'
),

(
'General Enquiries',
'Front Desk',
'0756789012',
'info@quickbite.co.ke'
);

-- =====================================================
-- QUICKBITE FOOD DELIVERY
-- Part 3: Seed Data
-- =====================================================

-- -----------------------------
-- Food Categories
-- -----------------------------
INSERT IGNORE INTO categories (name, slug, description) VALUES
('Burgers', 'burgers', 'Fresh grilled burgers'),
('Pizza', 'pizza', 'Hot and cheesy pizzas'),
('Chicken', 'chicken', 'Crispy fried chicken'),
('Drinks', 'drinks', 'Soft drinks and beverages'),
('Desserts', 'desserts', 'Sweet treats and ice cream'),
('Combos', 'combos', 'Value meal combinations');

-- -----------------------------
-- Products
-- -----------------------------
INSERT IGNORE INTO products
(category_id, name, kind, description, price, availability, image_url, is_best_seller, is_featured)
VALUES

((SELECT id FROM categories WHERE slug='burgers'),
'Classic Beef Burger',
'food',
'Juicy grilled beef burger with lettuce, tomato and cheese.',
650.00,
'in_stock',
'images/burger1.jpg',
1,
1),

((SELECT id FROM categories WHERE slug='burgers'),
'Double Cheese Burger',
'food',
'Double beef patties with cheddar cheese.',
850.00,
'in_stock',
'images/burger2.jpg',
1,
0),

((SELECT id FROM categories WHERE slug='pizza'),
'Pepperoni Pizza',
'food',
'12-inch pepperoni pizza.',
1200.00,
'in_stock',
'images/pizza1.jpg',
1,
1),

((SELECT id FROM categories WHERE slug='pizza'),
'BBQ Chicken Pizza',
'food',
'Chicken, BBQ sauce and mozzarella.',
1350.00,
'in_stock',
'images/pizza2.jpg',
0,
1),

((SELECT id FROM categories WHERE slug='chicken'),
'8 Piece Fried Chicken',
'food',
'Crispy fried chicken bucket.',
1450.00,
'in_stock',
'images/chicken1.jpg',
1,
0),

((SELECT id FROM categories WHERE slug='drinks'),
'Coca-Cola 500ml',
'drink',
'Cold Coca-Cola.',
120.00,
'in_stock',
'images/coke.jpg',
0,
0),

((SELECT id FROM categories WHERE slug='drinks'),
'Fresh Orange Juice',
'drink',
'Freshly squeezed orange juice.',
250.00,
'in_stock',
'images/orangejuice.jpg',
0,
0),

((SELECT id FROM categories WHERE slug='desserts'),
'Chocolate Brownie',
'food',
'Rich chocolate brownie.',
300.00,
'in_stock',
'images/brownie.jpg',
0,
0),

((SELECT id FROM categories WHERE slug='desserts'),
'Vanilla Ice Cream',
'food',
'Creamy vanilla ice cream.',
280.00,
'in_stock',
'images/icecream.jpg',
0,
0),

((SELECT id FROM categories WHERE slug='combos'),
'Burger Combo',
'combo',
'Classic Burger + Fries + Soda.',
950.00,
'in_stock',
'images/combo1.jpg',
1,
1),

((SELECT id FROM categories WHERE slug='combos'),
'Pizza Combo',
'combo',
'Medium Pizza + 2 Drinks.',
1650.00,
'in_stock',
'images/combo2.jpg',
1,
1);

-- -----------------------------
-- Promotions
-- -----------------------------
INSERT IGNORE INTO promotions
(code, title, description, discount_percent, min_order_amount, is_active)
VALUES
('WELCOME10',
'Welcome Discount',
'10% off your first order.',
10.00,
500.00,
1),

('FREEDEL',
'Free Delivery',
'Free delivery on orders above KES 2,000.',
0.00,
2000.00,
1),

('PIZZA20',
'Pizza Lovers',
'20% off all pizzas.',
20.00,
1000.00,
1),

('COMBO15',
'Combo Deal',
'15% off combo meals.',
15.00,
1200.00,
1);

-- -----------------------------
-- Departments
-- -----------------------------
INSERT IGNORE INTO departments
(name, contact_person, phone, email)
VALUES
('Customer Support',
'Mary Wanjiku',
'0712345678',
'support@quickbite.co.ke'),

('Orders',
'James Mwangi',
'0723456789',
'orders@quickbite.co.ke'),

('Delivery Team',
'Kevin Otieno',
'0734567890',
'delivery@quickbite.co.ke'),

('Marketing',
'Faith Njeri',
'0745678901',
'marketing@quickbite.co.ke'),

('General Enquiries',
'Front Desk',
'0756789012',
'info@quickbite.co.ke');
