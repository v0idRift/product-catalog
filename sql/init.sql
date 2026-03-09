SET NAMES utf8mb4;

DROP TABLE IF EXISTS products;
DROP TABLE IF EXISTS categories;

CREATE TABLE categories (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL
) ENGINE=InnoDB;

CREATE TABLE products (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    price DECIMAL(10, 2) NOT NULL,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    category_id INT UNSIGNED NOT NULL,
    FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE CASCADE
) ENGINE=InnoDB;

INSERT INTO categories (name) VALUES
    ('Ноутбуки'),
    ('Планшети'),
    ('Смартфони'),
    ('Аксесуари');

INSERT INTO products (name, price, created_at, category_id) VALUES
    ('Lenovo IdeaPad 3', 15999.00, '2026-01-10 10:00:00', 1),
    ('HP Pavilion 15', 18499.00, '2026-01-15 12:30:00', 1),
    ('Acer Aspire 5', 14299.00, '2026-02-01 09:00:00', 1),
    ('ASUS VivoBook 14', 16799.00, '2026-02-20 14:00:00', 1),
    ('Dell Inspiron 15', 19999.00, '2026-03-01 08:00:00', 1),
    ('Samsung Galaxy Tab A8', 7999.00, '2026-01-05 11:00:00', 2),
    ('Lenovo Tab M10', 6499.00, '2026-01-20 13:00:00', 2),
    ('Apple iPad 10.2', 12999.00, '2026-02-10 10:00:00', 2),
    ('Xiaomi Pad 6', 9499.00, '2026-03-05 16:00:00', 2),
    ('iPhone 15', 34999.00, '2026-01-12 09:30:00', 3),
    ('Samsung Galaxy S24', 29999.00, '2026-01-25 15:00:00', 3),
    ('Xiaomi 14', 18999.00, '2026-02-05 11:00:00', 3),
    ('Google Pixel 8', 24999.00, '2026-02-28 10:00:00', 3),
    ('Чохол для ноутбука', 899.00, '2026-01-08 08:00:00', 4),
    ('Бездротова мишка Logitech', 1299.00, '2026-02-14 12:00:00', 4),
    ('USB-C хаб 7-в-1', 1599.00, '2026-03-02 09:00:00', 4);
