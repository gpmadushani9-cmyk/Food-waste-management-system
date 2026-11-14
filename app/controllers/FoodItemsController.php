<?php

class FoodItemsController extends Controller {
    
    public function index() {
        $items = DB::query("SELECT * FROM food_items ORDER BY name");
        $this->render('food_items/list', compact('items'));
    }
    
    public function create() {
        $error = '';
        if ($_POST) {
            $name = trim($_POST['name'] ?? '');
            $category = trim($_POST['category'] ?? '');
            $cost_price = (float)($_POST['cost_price'] ?? 0);
            $selling_price = (float)($_POST['selling_price'] ?? 0);
            
            if ($name && $category) {
                DB::query("INSERT INTO food_items (name, category, cost_price, selling_price) VALUES (?, ?, ?, ?)", 
                    [$name, $category, $cost_price, $selling_price]);
                header('Location: index.php?r=food_items/index');
                exit;
            }
            $error = 'Please fill all required fields';
        }
        
        $mode = 'create';
        $item = [];
        $this->render('food_items/form', compact('mode', 'item', 'error'));
    }
    
    public function edit() {
        $id = (int)($_GET['id'] ?? 0);
        $results = DB::query("SELECT * FROM food_items WHERE id = ?", [$id]);
        $item = count($results) > 0 ? $results[0] : null;
        $allItems = [];
        $error = '';
        
        if (!$item) {
            header('Location: index.php?r=food_items/index');
            exit;
        }
        
        if ($_POST) {
            $name = trim($_POST['name'] ?? '');
            $category = trim($_POST['category'] ?? '');
            $cost_price = (float)($_POST['cost_price'] ?? 0);
            $selling_price = (float)($_POST['selling_price'] ?? 0);
            
            if ($name && $category) {
                DB::query("UPDATE food_items SET name = ?, category = ?, cost_price = ?, selling_price = ? WHERE id = ?", 
                    [$name, $category, $cost_price, $selling_price, $id]);
                header('Location: index.php?r=food_items/index');
                exit;
            }
            $error = 'Please fill all required fields';
        }
        
        $mode = 'edit';
        $this->render('food_items/form', compact('mode', 'item', 'allItems', 'error'));
    }
    
    public function delete() {
        if ($_POST) {
            $id = (int)($_POST['id'] ?? 0);
            DB::query("DELETE FROM food_items WHERE id = ?", [$id]);
        }
        header('Location: index.php?r=food_items/index');
        exit;
    }
}