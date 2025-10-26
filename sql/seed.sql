USE shopdb;

-- Insert categories
INSERT INTO categories (name) VALUES 
    ('Electronics'),
    ('Clothing'),
    ('Books'),
    ('Accessories'),
    ('Home & Kitchen');

-- Insert products
INSERT INTO products (category_id, name, description, price, quantity) VALUES
    (1, 'Gaming Laptop', '15" Gaming Laptop with RTX 3060', 1299.99, 5),
    (1, 'Wireless Earbuds', 'True wireless earbuds with noise cancellation', 149.99, 10),
    (2, 'Classic T-Shirt', 'Cotton crew neck t-shirt', 19.99, 20),
    (2, 'Denim Jeans', 'Classic fit blue jeans', 59.99, 15),
    (3, 'PHP & MySQL Book', 'Complete guide to web development', 45.99, 30);
-- demo admin user
INSERT INTO users (email, password, name, is_admin) VALUES ('demo@school.edu', '$2y$12$yRyHUWPKFd30bEbRmJBF0OwaLROIUFfsJKEHdeoxYz1ENRDjJmooy', 'Demo User', 1);
