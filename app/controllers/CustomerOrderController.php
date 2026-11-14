<?php

class CustomerOrderController extends Controller {
    
    public function placeOrder() {
        if ($_POST) {
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
            exit;
        }
        
        echo json_encode(['success' => false, 'message' => 'Invalid request']);
        exit;
    }
}
