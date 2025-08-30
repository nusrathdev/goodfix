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
    faculty VARCHAR(50) NOT NULL,
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
INSERT INTO complaints (student_name, student_id, email, faculty, complaint_type, subject, description) VALUES 
('Mohamed Nusrath', 'ict23090', 'ict23090@std.uwu.ac.lk', 'Technological Studies', 'Academic', 'Computers in CAD/CAM Lab Not Working', 'In our faculty CAD/CAM (computer lab), the computers are not working. We couldn’t do our academic practicals properly, and we are not satisfied with it. Staff members are also struggling to conduct practicals.'),
('Mohamed Asfak', 'ict23027', 'ict23027@std.uwu.ac.lk', 'Applied Sciences', 'Facility', 'Broken Chair', 'Chair in lecture room 01 is broken and needs replacement');
