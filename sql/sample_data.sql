USE shopdb;

-- Additional products that cover edge cases (low stock, zero stock, premium pricing)
INSERT INTO products (category_id, name, description, price, quantity) VALUES
    (1, '4K Photo Monitor', 'Calibrated 27\" IPS monitor for designers', 699.99, 3),
    (4, 'Travel Backpack', 'Carry-on friendly backpack with laptop sleeve', 129.50, 12),
    (5, 'Espresso Machine', 'Semi-automatic espresso maker with steam wand', 499.00, 2),
    (3, 'Limited Edition Novel', 'Signed hardback; intentionally out of stock for testing', 24.99, 0);

-- Sample non-admin shopper (password hash = "password")
INSERT INTO users (email, password, name, is_admin)
VALUES ('shopper@example.com', '$2y$12$yRyHUWPKFd30bEbRmJBF0OwaLROIUFfsJKEHdeoxYz1ENRDjJmooy', 'Sample Shopper', 0)
ON DUPLICATE KEY UPDATE name = VALUES(name);

-- Sample order history for reporting tests
SET @shopper_id = (SELECT id FROM users WHERE email = 'shopper@example.com' LIMIT 1);
INSERT INTO orders (user_id, total) VALUES (@shopper_id, 1473.48);
SET @order_id = LAST_INSERT_ID();
INSERT INTO order_items (order_id, product_id, quantity, price) VALUES
    (@order_id, 1, 1, 1299.99),
    (@order_id, 2, 1, 149.99),
    (@order_id, 3, 1, 19.99),
    (@order_id, 4, 1, 3.51);
