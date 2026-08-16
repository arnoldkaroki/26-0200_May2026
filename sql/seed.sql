-- ===========================================================
-- QUICKBITE FOOD DELIVERY — Seed Data
--
-- Loads the default food categories, menu items,
-- promotions and contact departments.
--
-- Safe to re-run because INSERT IGNORE prevents duplicates.
--
-- Load:
--    mysql -u root -p < sql/seed.sql
-- ===========================================================

USE quick_bite;

-- ===========================================================
-- Food Categories
-- ===========================================================
INSERT IGNORE INTO categories (name, slug, description) VALUES
('Burgers','burgers','Delicious grilled burgers.'),
('Pizza','pizza','Freshly baked pizzas.'),
('Chicken','chicken','Crispy fried and grilled chicken meals.'),
('Drinks','drinks','Soft drinks, juices and bottled water.'),
('Desserts','desserts','Sweet treats and ice cream.'),
('Combos','combos','Complete meals at great value.');

-- ===========================================================
-- Menu Items
-- ===========================================================
INSERT IGNORE INTO products
(category_id,name,kind,description,price,availability,image_url,is_best_seller,is_featured)
VALUES

((SELECT id FROM categories WHERE slug='burgers'),
'Classic Beef Burger',
'food',
'Grilled beef patty, cheese, lettuce and tomato.',
650.00,
'in_stock',
'https://images.unsplash.com/photo-1568901346375-23c9450c58cd?w=500',
1,
1),

((SELECT id FROM categories WHERE slug='burgers'),
'Chicken Burger',
'food',
'Crispy chicken burger served with fresh vegetables.',
600.00,
'in_stock',
'https://images.unsplash.com/photo-1550547660-d9450f859349?w=500',
0,
1),

((SELECT id FROM categories WHERE slug='pizza'),
'Pepperoni Pizza',
'food',
'Large pepperoni pizza with mozzarella cheese.',
1200.00,
'in_stock',
'https://images.unsplash.com/photo-1513104890138-7c749659a591?w=500',
1,
1),

((SELECT id FROM categories WHERE slug='pizza'),
'BBQ Chicken Pizza',
'food',
'Chicken, BBQ sauce and mozzarella cheese.',
1350.00,
'in_stock',
'https://images.unsplash.com/photo-1594007654729-407eedc4be65?w=500',
1,
0),

((SELECT id FROM categories WHERE slug='chicken'),
'8 Piece Chicken Bucket',
'food',
'Crispy fried chicken bucket.',
1500.00,
'in_stock',
'https://images.unsplash.com/photo-1562967916-eb82221dfb36?w=500',
1,
0),

((SELECT id FROM categories WHERE slug='drinks'),
'Coca-Cola 500ml',
'drink',
'Ice cold Coca-Cola.',
120.00,
'in_stock',
'https://images.unsplash.com/photo-1622483767028-3f66f32aef97?w=500',
0,
0),

((SELECT id FROM categories WHERE slug='drinks'),
'Fresh Orange Juice',
'drink',
'Freshly squeezed orange juice.',
250.00,
'in_stock',
'https://images.unsplash.com/photo-1600271886742-f049cd451bba?w=500',
0,
0),

((SELECT id FROM categories WHERE slug='desserts'),
'Chocolate Brownie',
'food',
'Rich chocolate brownie.',
300.00,
'in_stock',
'https://images.unsplash.com/photo-1606313564200-e75d5e30476c?w=500',
0,
0),

((SELECT id FROM categories WHERE slug='desserts'),
'Vanilla Ice Cream',
'food',
'Creamy vanilla ice cream.',
280.00,
'in_stock',
'https://images.unsplash.com/photo-1563805042-7684c019e1cb?w=500',
0,
0),

((SELECT id FROM categories WHERE slug='combos'),
'Burger Combo',
'combo',
'Burger, fries and soda.',
950.00,
'in_stock',
'https://images.unsplash.com/photo-1512152272829-e3139592d56f?w=500',
1,
1),

((SELECT id FROM categories WHERE slug='combos'),
'Pizza Combo',
'combo',
'Medium pizza with two soft drinks.',
1650.00,
'in_stock',
'https://images.unsplash.com/photo-1513104890138-7c749659a591?w=500',
1,
1);

-- ===========================================================
-- Update Images (for existing records)
-- ===========================================================
UPDATE products
SET image_url='https://images.unsplash.com/photo-1568901346375-23c9450c58cd?w=500'
WHERE name='Classic Beef Burger'
AND image_url IS NULL;

UPDATE products
SET image_url='https://images.unsplash.com/photo-1550547660-d9450f859349?w=500'
WHERE name='Chicken Burger'
AND image_url IS NULL;

UPDATE products
SET image_url='https://images.unsplash.com/photo-1513104890138-7c749659a591?w=500'
WHERE name='Pepperoni Pizza'
AND image_url IS NULL;

UPDATE products
SET image_url='https://images.unsplash.com/photo-1594007654729-407eedc4be65?w=500'
WHERE name='BBQ Chicken Pizza'
AND image_url IS NULL;

UPDATE products
SET image_url='https://images.unsplash.com/photo-1562967916-eb82221dfb36?w=500'
WHERE name='8 Piece Chicken Bucket'
AND image_url IS NULL;

-- ===========================================================
-- Promotions
-- ===========================================================
INSERT IGNORE INTO promotions
(code,title,description,discount_percent,min_order_amount,is_active)
VALUES
('WELCOME10',
'Welcome Offer',
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
'20% discount on all pizzas.',
20.00,
1000.00,
1);

-- ===========================================================
-- Contact Departments
-- ===========================================================
INSERT IGNORE INTO departments
(name,contact_person,phone,email)
VALUES
('Customer Support','Mary Wanjiku','0712345678','support@quickbite.co.ke'),
('Orders','James Mwangi','0723456789','orders@quickbite.co.ke'),
('Delivery Team','Kevin Otieno','0734567890','delivery@quickbite.co.ke'),
('Marketing','Faith Njeri','0745678901','marketing@quickbite.co.ke'),
('General Enquiries','Front Desk','0756789012','info@quickbite.co.ke');
