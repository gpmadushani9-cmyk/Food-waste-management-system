-- Fix waste_logs table to reference food_items instead of menu_items

-- Step 1: Drop the existing foreign key constraint
ALTER TABLE waste_logs DROP FOREIGN KEY fk_waste_menu;

-- Step 2: Rename the column to be more clear
ALTER TABLE waste_logs CHANGE menu_item_id food_item_id INT NULL;

-- Step 3: Add new foreign key constraint referencing food_items
ALTER TABLE waste_logs ADD CONSTRAINT fk_waste_food 
  FOREIGN KEY (food_item_id) REFERENCES food_items(id) ON DELETE SET NULL;
