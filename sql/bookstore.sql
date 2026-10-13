-- Create database
CREATE DATABASE IF NOT EXISTS bookstore;
USE bookstore;

-- =========================
-- TABLE 1: BOOKS
-- =========================
CREATE TABLE books (
    id INT AUTO_INCREMENT PRIMARY KEY,
    audience VARCHAR(20) NOT NULL,
    category VARCHAR(50) NOT NULL,
    title VARCHAR(255) NOT NULL,
    image VARCHAR(255),
    price DECIMAL(10,2) NOT NULL
);

-- Sample data for books
INSERT INTO books (audience, category, title, image, price) VALUES
('Kids', 'Infants', 'Baby Learning Book', 'images/baby1.jpg', 4.99),
('Kids', 'Junior', 'Junior Adventure Story', 'images/junior1.jpg', 6.99),
('Kids', 'Young', 'Young Readers Book', 'images/young1.jpg', 7.99),
('Adults', 'Classic Novels', 'Pride and Prejudice', 'images/classic1.jpg', 9.99),
('Adults', 'Fiction', 'Modern Fiction Story', 'images/fiction1.jpg', 8.99),
('Adults', 'Comic', 'Superhero Comic', 'images/comic1.jpg', 5.50),
('Adults', 'Crime and Thriller', 'Crime Thriller Book', 'images/crime1.jpg', 10.99);

-- =========================
-- TABLE 2: USERS
-- =========================
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    phone VARCHAR(10) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- =========================
-- TABLE 3: ORDERS
-- =========================
CREATE TABLE orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    customer_name VARCHAR(100) NOT NULL,
    email VARCHAR(255) NOT NULL,
    phone VARCHAR(10) NOT NULL,
    book_id INT,
    ordered_item VARCHAR(255) NOT NULL,
    price DECIMAL(10,2) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (book_id) REFERENCES books(id)
);