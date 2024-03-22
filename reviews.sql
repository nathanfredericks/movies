-- Create the database
CREATE DATABASE IF NOT EXISTS reviews_db;

-- Switch to the created database
USE reviews_db;

-- Create the reviews table
CREATE TABLE IF NOT EXISTS review (
    id INT AUTO_INCREMENT PRIMARY KEY,
    movie_id INT NOT NULL,
    username VARCHAR(50) NOT NULL,
    title VARCHAR(100) NOT NULL,
    review VARCHAR(500) NOT NULL,
    added TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE USER IF NOT EXISTS 'reviews_user'@'localhost' IDENTIFIED BY 'm0v13s';
GRANT SELECT, INSERT, UPDATE, DELETE, FILE ON *.* TO 'reviews_user'@'localhost';

-- Display the table structure
DESCRIBE review;
