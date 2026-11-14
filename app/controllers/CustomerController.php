<?php

class CustomerController extends Controller {
    
    public function index() {
        $today = date('Y-m-d');
        $current_time = date('H:i:s');
        
        // Get active discounts
        $discounts = DB::query("
            SELECT d.*, fi.name, fi.selling_price, fi.category
            FROM discounts d 
            JOIN food_items fi ON d.food_item_id = fi.id 
            WHERE d.discount_date = ? 
            AND d.status = 'active' 
            AND d.start_time <= ? 
            AND d.end_time >= ?
            ORDER BY d.discount_percentage DESC", 
            [$today, $current_time, $current_time]);
        
        // Get all food items
        $all_foods = DB::query("
            SELECT fi.*, 
                   COALESCE(dp.quantity_leftover, 0) as available_qty,
                   d.discount_percentage,
                   d.status as discount_status
            FROM food_items fi
            LEFT JOIN daily_production dp ON fi.id = dp.food_item_id AND dp.production_date = ?
            LEFT JOIN discounts d ON fi.id = d.food_item_id 
                AND d.discount_date = ? 
                AND d.status = 'active'
                AND d.start_time <= ?
                AND d.end_time >= ?
            WHERE fi.active = 1
            ORDER BY d.discount_percentage DESC, fi.name", 
            [$today, $today, $current_time, $current_time]);
        
        // Get notifications
        $notifications = DB::query("SELECT * FROM notifications WHERE active = 1 ORDER BY created_at DESC LIMIT 5");
        
        // Handle subscription
        if ($_POST && isset($_POST['email'])) {
            $email = trim($_POST['email']);
            if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $token = bin2hex(random_bytes(32));
                try {
                    DB::query("INSERT INTO email_subscribers (email, unsubscribe_token) VALUES (?, ?)", [$email, $token]);
                    $_SESSION['subscribe_success'] = 'Successfully subscribed! You will receive discount alerts.';
                } catch (Exception $e) {
                    $_SESSION['subscribe_error'] = 'Email already subscribed or invalid.';
                }
            } else {
                $_SESSION['subscribe_error'] = 'Invalid email address.';
            }
            header('Location: index.php?r=customer/index');
            exit;
        }
        
        $this->renderCustomer('customer/index', compact('all_foods', 'discounts', 'notifications'));
    }
    
    public function subscribe() {
        if ($_POST) {
            $email = trim($_POST['email'] ?? '');
            
            if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $token = bin2hex(random_bytes(32));
                
                try {
                    DB::query("INSERT INTO email_subscribers (email, unsubscribe_token) VALUES (?, ?)", [$email, $token]);
                    $_SESSION['subscribe_success'] = 'Successfully subscribed! You will receive discount alerts.';
                } catch (Exception $e) {
                    $_SESSION['subscribe_error'] = 'Email already subscribed or invalid.';
                }
            } else {
                $_SESSION['subscribe_error'] = 'Invalid email address.';
            }
        }
        header('Location: index.php?r=customer/index');
        exit;
    }
    
    public function unsubscribe() {
        $token = $_GET['token'] ?? '';
        if ($token) {
            DB::query("UPDATE email_subscribers SET active = 0 WHERE unsubscribe_token = ?", [$token]);
            echo '<h2>Successfully unsubscribed from discount alerts.</h2>';
        }
        exit;
    }
    
    public function api() {
        $today = date('Y-m-d');
        $current_time = date('H:i:s');
        
        $discounts = DB::query("
            SELECT d.*, fi.name, fi.selling_price, fi.category
            FROM discounts d 
            JOIN food_items fi ON d.food_item_id = fi.id 
            WHERE d.discount_date = ? 
            AND d.status = 'active' 
            AND d.start_time <= ? 
            AND d.end_time >= ?
            ORDER BY d.discount_percentage DESC", 
            [$today, $current_time, $current_time]);
        
        header('Content-Type: application/json');
        echo json_encode($discounts);
        exit;
    }
    
    public function placeOrder() {
        header('Content-Type: application/json');
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['success' => false, 'message' => 'Invalid request method']);
            exit;
        }
        
        $cart = json_decode($_POST['cart'] ?? '[]', true);
        $customer_name = trim($_POST['customer_name'] ?? '');
        $customer_email = trim($_POST['customer_email'] ?? '');
        $customer_phone = trim($_POST['customer_phone'] ?? '');
        $customer_address = trim($_POST['customer_address'] ?? '');
        $notes = trim($_POST['notes'] ?? '');
        
        if (empty($cart) || !$customer_name || !$customer_email || !$customer_phone || !$customer_address) {
            echo json_encode(['success' => false, 'message' => 'Please fill all required fields']);
            exit;
        }
        
        try {
            $total_amount = 0;
            
            // Create order
            DB::query("INSERT INTO orders (customer_name, customer_email, customer_phone, customer_address, notes, total_amount, status) 
                VALUES (?, ?, ?, ?, ?, 0, 'pending')", 
                [$customer_name, $customer_email, $customer_phone, $customer_address, $notes]);
            
            $order_id = DB::lastInsertId();
            
            // Add order items
            foreach ($cart as $item) {
                $food_id = (int)$item['id'];
                $quantity = (int)$item['quantity'];
                $price = (float)$item['price'];
                $discount = (int)($item['discount'] ?? 0);
                $subtotal = $price * $quantity * (1 - $discount/100);
                $total_amount += $subtotal;
                
                DB::query("INSERT INTO order_items (order_id, food_item_id, food_name, quantity, price, discount_percentage, subtotal) 
                    VALUES (?, ?, ?, ?, ?, ?, ?)", 
                    [$order_id, $food_id, $item['name'], $quantity, $price, $discount, $subtotal]);
            }
            
            // Update total amount
            DB::query("UPDATE orders SET total_amount = ? WHERE id = ?", [$total_amount, $order_id]);
            
            echo json_encode(['success' => true, 'order_id' => $order_id, 'message' => 'Order placed successfully!']);
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
        }
        exit;
    }
    
    private function renderCustomer($viewFile, $data = []) {
        extract($data);
        include __DIR__ . '/../views/layouts/customer.php';
    }
}