-- Add this to your database (Run in phpMyAdmin SQL tab)

CREATE TABLE IF NOT EXISTS email_subscribers (
  id INT AUTO_INCREMENT PRIMARY KEY,
  email VARCHAR(255) NOT NULL UNIQUE,
  subscribed_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  active TINYINT(1) DEFAULT 1,
  unsubscribe_token VARCHAR(64) UNIQUE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
