-- Fix sales table to reference food_items instead of menu_items

-- Step 1: Drop the existing foreign key constraint
ALTER TABLE sales DROP FOREIGN KEY fk_sales_menu;

-- Step 2: Rename the column to be more clear (optional but recommended)
ALTER TABLE sales CHANGE menu_item_id food_item_id INT NOT NULL;

-- Step 3: Add new foreign key constraint referencing food_items
ALTER TABLE sales ADD CONSTRAINT fk_sales_food 
  FOREIGN KEY (food_item_id) REFERENCES food_items(id) ON DELETE CASCADE;

-- Step 4: Update Sale model queries (done in code)
