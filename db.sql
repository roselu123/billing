CREATE TABLE bills (
    id INT AUTO_INCREMENT PRIMARY KEY,
    due_date DATE NOT NULL,
    amount DECIMAL(10, 2) NOT NULL,
    customer_name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL
);
