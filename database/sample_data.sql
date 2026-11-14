-- Sample Data for Food Waste Management System
-- Run this AFTER importing schema.sql

-- Sample Food Items
INSERT INTO food_items (name, category, cost_price, selling_price, active) VALUES
('Chicken Roll', 'Main Course', 2.50, 5.00, 1),
('Vegetable Pizza', 'Main Course', 3.00, 8.00, 1),
('Beef Burger', 'Main Course', 2.80, 6.50, 1),
('Fried Rice', 'Main Course', 1.50, 4.50, 1),
('Spring Rolls', 'Appetizer', 1.00, 3.00, 1),
('French Fries', 'Snack', 0.80, 2.50, 1),
('Chocolate Cake', 'Dessert', 2.00, 5.50, 1),
('Ice Cream', 'Dessert', 1.20, 3.50, 1),
('Cola', 'Beverage', 0.50, 2.00, 1),
('Orange Juice', 'Beverage', 0.80, 3.00, 1);

-- Sample Ingredients
INSERT INTO ingredients (name, unit, cost_per_unit, reorder_level) VALUES
('Chicken', 'kg', 5.00, 10.00),
('Flour', 'kg', 1.50, 20.00),
('Vegetables', 'kg', 2.00, 15.00),
('Beef', 'kg', 8.00, 8.00),
('Rice', 'kg', 1.20, 25.00),
('Oil', 'liter', 3.00, 10.00),
('Cheese', 'kg', 6.00, 5.00),
('Potatoes', 'kg', 1.00, 20.00),
('Chocolate', 'kg', 10.00, 3.00),
('Milk', 'liter', 2.50, 10.00);

-- Sample Production for Today (adjust date as needed)
INSERT INTO daily_production (food_item_id, production_date, quantity_produced, quantity_sold, quantity_leftover) VALUES
(1, CURDATE(), 100, 65, 35),
(2, CURDATE(), 50, 42, 8),
(3, CURDATE(), 80, 70, 10),
(4, CURDATE(), 60, 55, 5),
(5, CURDATE(), 40, 38, 2);

-- Sample Active Discount
INSERT INTO discounts (food_item_id, discount_date, discount_percentage, start_time, end_time, status, leftover_quantity) VALUES
(1, CURDATE(), 30, '18:00:00', '21:00:00', 'active', 35);

-- Sample Notification
INSERT INTO notifications (title, message, type, active) VALUES
('Special Evening Discount!', 'Chicken Roll - 30% OFF until 9:00 PM. Limited stock available!', 'discount', 1);
