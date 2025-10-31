CREATE DATABASE IF NOT EXISTS bookstore;
USE bookstore;

-- Users table
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Books table
CREATE TABLE books (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    author VARCHAR(255) NOT NULL,
    description TEXT,
    price DECIMAL(10,2) NOT NULL,
    image_url VARCHAR(500),
    category VARCHAR(100),
    stock_quantity INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Cart table
CREATE TABLE cart (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    book_id INT,
    quantity INT DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (book_id) REFERENCES books(id)
);

-- Orders table
CREATE TABLE orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    total_amount DECIMAL(10,2),
    status ENUM('pending', 'confirmed', 'shipped', 'delivered') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id)
);

-- Order items table
CREATE TABLE order_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT,
    book_id INT,
    quantity INT,
    price DECIMAL(10,2),
    FOREIGN KEY (order_id) REFERENCES orders(id),
    FOREIGN KEY (book_id) REFERENCES books(id)
);

-- Insert sample books with Unsplash images
INSERT INTO books (title, author, description, price, image_url, category, stock_quantity) VALUES
('The Great Gatsby', 'F. Scott Fitzgerald', 'A classic novel of the Jazz Age', 12.99, 'https://images.unsplash.com/photo-1543002588-bfa74002ed7e?w=200&h=280&fit=crop', 'Fiction', 50),
('To Kill a Mockingbird', 'Harper Lee', 'A gripping tale of racial injustice', 14.99, 'https://images.unsplash.com/photo-1544947950-fa07a98d237f?w=200&h=280&fit=crop', 'Fiction', 30),
('1984', 'George Orwell', 'Dystopian social science fiction', 10.99, 'https://images.unsplash.com/photo-1495446815901-a7297e633e8d?w=200&h=280&fit=crop', 'Science Fiction', 25),
('Pride and Prejudice', 'Jane Austen', 'Romantic novel of manners', 9.99, 'https://images.unsplash.com/photo-1512820790803-83ca734da794?w=200&h=280&fit=crop', 'Romance', 40);
