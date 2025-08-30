-- GoodFix Complaint Management System Database
-- Create database
CREATE DATABASE IF NOT EXISTS goodfix_complaints;
USE goodfix_complaints;

-- Admin users table
CREATE TABLE admins (
    id INT PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Complaints table
CREATE TABLE complaints (
    id INT PRIMARY KEY AUTO_INCREMENT,
    student_name VARCHAR(100) NOT NULL,
    student_id VARCHAR(20) NOT NULL,
    email VARCHAR(100) NOT NULL,
    department VARCHAR(50) NOT NULL,
    complaint_type VARCHAR(50) NOT NULL,
    subject VARCHAR(200) NOT NULL,
    description TEXT NOT NULL,
    status ENUM('pending', 'in_progress', 'resolved', 'closed') DEFAULT 'pending',
    priority ENUM('low', 'medium', 'high', 'urgent') DEFAULT 'medium',
    submitted_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Insert default admin
INSERT INTO admins (username, password) VALUES 
('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi'); -- password: password

-- for testing
INSERT INTO complaints (student_name, student_id, email, department, complaint_type, subject, description) VALUES 
('John Doe', 'ST001', 'john@university.edu', 'Computer Science', 'Academic', 'Lab Equipment Issue', 'The computers in Lab 101 are not working properly'),
('Jane Smith', 'ST002', 'jane@university.edu', 'Engineering', 'Facility', 'Broken Chair', 'Chair in classroom 205 is broken and needs replacement');
