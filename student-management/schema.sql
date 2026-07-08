

CREATE DATABASE IF NOT EXISTS student_management
  CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

USE student_management;

CREATE TABLE IF NOT EXISTS students (
    id INT AUTO_INCREMENT PRIMARY KEY,
    first_name VARCHAR(50) NOT NULL,
    last_name VARCHAR(50) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    phone VARCHAR(20),
    major VARCHAR(100),
    gpa DECIMAL(3,2) DEFAULT 0.00,
    enrollment_date DATE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

INSERT INTO students (first_name, last_name, email, phone, major, gpa, enrollment_date) VALUES
('Ahmad', 'Khalil', 'ahmad.khalil@example.com', '0599123456', 'Computer Science', 3.75, '2023-09-01'),
('Lina', 'Odeh', 'lina.odeh@example.com', '0598765432', 'Business Administration', 3.40, '2022-09-01'),
('Yousef', 'Hamdan', 'yousef.hamdan@example.com', '0597112233', 'Civil Engineering', 2.95, '2021-09-01'),
('Rana', 'Salem', 'rana.salem@example.com', '0596223344', 'Computer Science', 3.90, '2023-09-01'),
('Omar', 'Nasser', 'omar.nasser@example.com', '0595334455', 'Pharmacy', 3.10, '2022-02-01'),
('Dana', 'Yaseen', 'dana.yaseen@example.com', '0594445566', 'Law', 3.55, '2020-09-01'),
('Khaled', 'Barghouti', 'khaled.barghouti@example.com', '0593556677', 'Computer Science', 2.60, '2024-09-01'),
('Nour', 'Qasem', 'nour.qasem@example.com', '0592667788', 'Architecture', 3.30, '2021-02-01'),
('Sami', 'Awad', 'sami.awad@example.com', '0591778899', 'Business Administration', 2.85, '2023-02-01'),
('Hala', 'Rimawi', 'hala.rimawi@example.com', '0590889900', 'Nursing', 3.65, '2022-09-01'),
('Bilal', 'Amer', 'bilal.amer@example.com', '0599887766', 'Civil Engineering', 3.05, '2020-02-01'),
('Reem', 'Shaheen', 'reem.shaheen@example.com', '0598776655', 'Computer Science', 3.85, '2024-02-01'),
('Tariq', 'Zeidan', 'tariq.zeidan@example.com', '0597665544', 'Pharmacy', 2.70, '2021-09-01'),
('Maya', 'Jaber', 'maya.jaber@example.com', '0596554433', 'Law', 3.20, '2023-09-01'),
('Fadi', 'Homsi', 'fadi.homsi@example.com', '0595443322', 'Architecture', 2.95, '2022-02-01');
