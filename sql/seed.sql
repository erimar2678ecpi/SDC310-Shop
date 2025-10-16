USE shopdb;

-- Insert categories
INSERT INTO categories (name) VALUES 
    ('Electronics'),
    ('Clothing'),
    ('Books'),
    ('Accessories'),
    ('Home & Kitchen');

-- Insert products
INSERT INTO products (category_id, name, description, price) VALUES
    (1, 'Gaming Laptop', '15" Gaming Laptop with RTX 3060', 1299.99),
    (1, 'Wireless Earbuds', 'True wireless earbuds with noise cancellation', 149.99),
    (2, 'Classic T-Shirt', 'Cotton crew neck t-shirt', 19.99),
    (2, 'Denim Jeans', 'Classic fit blue jeans', 59.99),
    (3, 'PHP & MySQL Book', 'Complete guide to web development', 45.99);

-- demo user (password should be hashed, this is plain for demo only)
INSERT INTO users (email, password, name) VALUES ('demo@school.edu', 'password', 'Demo User');
