CREATE DATABASE task_manager;

-- Use the created database
USE task_manager;
-- Users Table
CREATE TABLE  users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    name VARCHAR(100),
    email VARCHAR(100)
);

-- Tasks Table (linked with users)
CREATE TABLE  tasks (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    title VARCHAR(255) NOT NULL,
    priority ENUM('High', 'Medium', 'Low') NOT NULL,
    status ENUM('Pending', 'In Progress', 'Done') DEFAULT 'Pending',
    deadline DATETIME DEFAULT NULL,
    reminder DATETIME DEFAULT NULL,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);
ALTER TABLE tasks 
ADD arrival_time INT DEFAULT 0, 
ADD burst_time INT DEFAULT 1;

