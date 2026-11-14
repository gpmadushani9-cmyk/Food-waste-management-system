<?php

class OrdersController extends Controller {
    
    public function index() {
        Auth::requireLogin();
        
        $orders = DB::query("
            SELECT o.*, 
                   COUNT(oi.id) as item_count
            FROM orders o
            LEFT JOIN order_items oi ON o.id = oi.order_id
            GROUP BY o.id
            ORDER BY o.order_date DESC
            LIMIT 100");
        
        $this->render('orders/index', compact('orders'));
    }
    
    public function view() {
        Auth::requireLogin();
        
        $id = (int)($_GET['id'] ?? 0);
        $order = DB::query("SELECT * FROM orders WHERE id = ?", [$id])[0] ?? null;
        
        if (!$order) {
            header('Location: index.php?r=orders/index');
            exit;
        }
        
        $items = DB::query("SELECT * FROM order_items WHERE order_id = ?", [$id]);
        
        $this->render('orders/view', compact('order', 'items'));
    }
    
    public function updateStatus() {
        Auth::requireLogin();
        
        if ($_POST) {
            $id = (int)($_POST['id'] ?? 0);
            $status = $_POST['status'] ?? '';
            
            if ($id && $status) {
                DB::query("UPDATE orders SET status = ? WHERE id = ?", [$status, $id]);
            }
        }
        
        header('Location: index.php?r=orders/index');
        exit;
    }
    
    public function cancel() {
        Auth::requireLogin();
        
        if ($_POST) {
            $id = (int)($_POST['id'] ?? 0);
            DB::query("UPDATE orders SET status = 'cancelled' WHERE id = ?", [$id]);
        }
        
        header('Location: index.php?r=orders/index');
        exit;
    }
}
