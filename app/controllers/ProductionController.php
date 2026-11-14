<?php

class ProductionController extends Controller {
    
    public function index() {
        $date = $_GET['date'] ?? date('Y-m-d');
        
        $productions = DB::query("
            SELECT dp.*, fi.name as food_name, fi.category, fi.selling_price
            FROM daily_production dp 
            JOIN food_items fi ON dp.food_item_id = fi.id 
            WHERE dp.production_date = ? 
            ORDER BY fi.name", [$date]);
            
        $food_items = DB::query("SELECT * FROM food_items WHERE active = 1 ORDER BY name");
        
        $this->render('production/index', compact('productions', 'food_items', 'date'));
    }
    
    public function add() {
        if ($_POST) {
            $food_item_id = (int)($_POST['food_item_id'] ?? 0);
            $production_date = $_POST['production_date'] ?? date('Y-m-d');
            $quantity_produced = (int)($_POST['quantity_produced'] ?? 0);
            
            if ($food_item_id && $quantity_produced > 0) {
                DB::query("INSERT INTO daily_production (food_item_id, production_date, quantity_produced) 
                    VALUES (?, ?, ?) ON DUPLICATE KEY UPDATE quantity_produced = ?", 
                    [$food_item_id, $production_date, $quantity_produced, $quantity_produced]);
                
                header('Location: index.php?r=production/index&date=' . $production_date);
                exit;
            }
        }
        header('Location: index.php?r=production/index');
        exit;
    }
    
    public function update_sales() {
        if ($_POST) {
            $id = (int)($_POST['id'] ?? 0);
            $quantity_sold = (int)($_POST['quantity_sold'] ?? 0);
            
            // Calculate leftover
            $production = DB::query("SELECT quantity_produced FROM daily_production WHERE id = ?", [$id])[0] ?? null;
            if ($production) {
                $leftover = max(0, $production['quantity_produced'] - $quantity_sold);
                DB::query("UPDATE daily_production SET quantity_sold = ?, quantity_leftover = ? WHERE id = ?", 
                    [$quantity_sold, $leftover, $id]);
            }
        }
        header('Location: index.php?r=production/index');
        exit;
    }
}