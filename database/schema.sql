
-- Food Waste & Profit Optimization System (MySQL schema)
-- Import this into phpMyAdmin (SQL tab)

CREATE TABLE IF NOT EXISTS users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(120) NOT NULL,
  email VARCHAR(160) NOT NULL UNIQUE,
  password_hash VARCHAR(255) NOT NULL,
  role ENUM('admin','staff') NOT NULL DEFAULT 'staff',
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS ingredients (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(140) NOT NULL,
  unit VARCHAR(30) NOT NULL,
  cost_per_unit DECIMAL(10,2) NOT NULL DEFAULT 0,
  reorder_level DECIMAL(10,2) NOT NULL DEFAULT 0,
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Food Items (Products) - Main items restaurant produces
CREATE TABLE IF NOT EXISTS food_items (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(140) NOT NULL,
  category VARCHAR(60) NOT NULL,
  cost_price DECIMAL(10,2) NOT NULL DEFAULT 0,
  selling_price DECIMAL(10,2) NOT NULL DEFAULT 0,
  active TINYINT(1) NOT NULL DEFAULT 1,
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Daily Production Tracking
CREATE TABLE IF NOT EXISTS daily_production (
  id INT AUTO_INCREMENT PRIMARY KEY,
  food_item_id INT NOT NULL,
  production_date DATE NOT NULL,
  quantity_produced INT NOT NULL,
  quantity_sold INT NOT NULL DEFAULT 0,
  quantity_leftover INT NOT NULL DEFAULT 0,
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  UNIQUE KEY unique_production (food_item_id, production_date),
  CONSTRAINT fk_production_food FOREIGN KEY (food_item_id) REFERENCES food_items(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Discount Management
CREATE TABLE IF NOT EXISTS discounts (
  id INT AUTO_INCREMENT PRIMARY KEY,
  food_item_id INT NOT NULL,
  discount_date DATE NOT NULL,
  discount_percentage INT NOT NULL,
  start_time TIME NOT NULL,
  end_time TIME NOT NULL,
  status ENUM('pending','active','expired','cancelled') NOT NULL DEFAULT 'pending',
  leftover_quantity INT NOT NULL,
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT fk_discount_food FOREIGN KEY (food_item_id) REFERENCES food_items(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Customer Notifications
CREATE TABLE IF NOT EXISTS notifications (
  id INT AUTO_INCREMENT PRIMARY KEY,
  title VARCHAR(200) NOT NULL,
  message TEXT NOT NULL,
  type ENUM('discount','general') NOT NULL DEFAULT 'discount',
  active TINYINT(1) NOT NULL DEFAULT 1,
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Email Subscribers
CREATE TABLE IF NOT EXISTS email_subscribers (
  id INT AUTO_INCREMENT PRIMARY KEY,
  email VARCHAR(160) NOT NULL UNIQUE,
  unsubscribe_token VARCHAR(64) NOT NULL UNIQUE,
  active TINYINT(1) NOT NULL DEFAULT 1,
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS menu_items (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(140) NOT NULL,
  price DECIMAL(10,2) NOT NULL DEFAULT 0,
  active TINYINT(1) NOT NULL DEFAULT 1,
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Recipes = portion sizes (ingredient qty needed for 1 menu item)
CREATE TABLE IF NOT EXISTS recipes (
  menu_item_id INT NOT NULL,
  ingredient_id INT NOT NULL,
  qty_per_item DECIMAL(10,2) NOT NULL DEFAULT 0,
  PRIMARY KEY (menu_item_id, ingredient_id),
  CONSTRAINT fk_recipes_menu FOREIGN KEY (menu_item_id) REFERENCES menu_items(id) ON DELETE CASCADE,
  CONSTRAINT fk_recipes_ing FOREIGN KEY (ingredient_id) REFERENCES ingredients(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS inventory_transactions (
  id INT AUTO_INCREMENT PRIMARY KEY,
  ingredient_id INT NOT NULL,
  txn_type ENUM('IN','OUT','ADJUST') NOT NULL,
  quantity DECIMAL(10,2) NOT NULL,
  note VARCHAR(255) NULL,
  txn_date DATE NOT NULL,
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT fk_inv_ing FOREIGN KEY (ingredient_id) REFERENCES ingredients(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS sales (
  id INT AUTO_INCREMENT PRIMARY KEY,
  sale_date DATE NOT NULL,
  menu_item_id INT NOT NULL,
  quantity DECIMAL(10,2) NOT NULL,
  revenue DECIMAL(12,2) NOT NULL,
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  INDEX idx_sales_date (sale_date),
  CONSTRAINT fk_sales_menu FOREIGN KEY (menu_item_id) REFERENCES menu_items(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Waste logs: can be tied to an ingredient or a menu item (prepared waste)
CREATE TABLE IF NOT EXISTS waste_logs (
  id INT AUTO_INCREMENT PRIMARY KEY,
  waste_date DATE NOT NULL,
  ingredient_id INT NULL,
  menu_item_id INT NULL,
  waste_type VARCHAR(40) NOT NULL,
  quantity DECIMAL(10,2) NOT NULL,
  cost DECIMAL(12,2) NOT NULL DEFAULT 0,
  note VARCHAR(255) NULL,
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  INDEX idx_waste_date (waste_date),
  CONSTRAINT fk_waste_ing FOREIGN KEY (ingredient_id) REFERENCES ingredients(id) ON DELETE SET NULL,
  CONSTRAINT fk_waste_menu FOREIGN KEY (menu_item_id) REFERENCES menu_items(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
