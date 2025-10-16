USE shopdb;

INSERT INTO categories (name) VALUES ('Apparel'), ('Home'), ('Stickers');

INSERT INTO products (category_id, name, description, price) VALUES
(1, 'T-shirt', 'Comfortable cotton t-shirt', 19.99),
(2, 'Ceramic Mug', '12oz mug', 9.50),
(3, 'Sticker Pack', 'Set of 5 stickers', 2.00);

-- demo user (password should be hashed, this is plain for demo only)
INSERT INTO users (email, password, name) VALUES ('demo@school.edu', 'password', 'Demo User');
