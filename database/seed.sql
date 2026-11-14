
-- Optional seed data (run AFTER schema.sql).
-- Note: users are NOT seeded. Create your first user via Register page.

INSERT INTO ingredients (name, unit, cost_per_unit, reorder_level) VALUES
('Rice', 'kg', 250.00, 5.00),
('Chicken', 'kg', 1200.00, 3.00),
('Cooking oil', 'L', 800.00, 2.00),
('Tomato', 'kg', 350.00, 2.00);

INSERT INTO menu_items (name, price, active) VALUES
('Chicken Fried Rice', 1800.00, 1),
('Chicken Curry Rice', 2000.00, 1);

-- Recipes / portion sizes
-- Chicken Fried Rice: 0.25 kg rice + 0.15 kg chicken + 0.02 L oil + 0.05 kg tomato
INSERT INTO recipes (menu_item_id, ingredient_id, qty_per_item) VALUES
(1, 1, 0.25),
(1, 2, 0.15),
(1, 3, 0.02),
(1, 4, 0.05);

-- Chicken Curry Rice: 0.30 kg rice + 0.18 kg chicken + 0.03 L oil + 0.06 kg tomato
INSERT INTO recipes (menu_item_id, ingredient_id, qty_per_item) VALUES
(2, 1, 0.30),
(2, 2, 0.18),
(2, 3, 0.03),
(2, 4, 0.06);

-- Inventory stock-in
INSERT INTO inventory_transactions (ingredient_id, txn_type, quantity, note, txn_date) VALUES
(1, 'IN', 20, 'Initial stock', CURDATE()),
(2, 'IN', 10, 'Initial stock', CURDATE()),
(3, 'IN', 5, 'Initial stock', CURDATE()),
(4, 'IN', 8, 'Initial stock', CURDATE());
