CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    telegram_id VARCHAR(255) NOT NULL UNIQUE,
    wallet_address VARCHAR(255),
    balance DECIMAL(18, 4) DEFAULT 0, 
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
