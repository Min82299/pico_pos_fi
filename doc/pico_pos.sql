CREATE TABLE sales (
    id INT AUTO_INCREMENT PRIMARY KEY,
    receipt_no VARCHAR(50),
    item_name VARCHAR(100),
    unit_price INT,
    quantity INT,
    total_amount INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
