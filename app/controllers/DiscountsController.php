<?php

class DiscountsController extends Controller {
    
    public function index() {
        $discounts = DB::query("
            SELECT d.*, fi.name as food_name, fi.selling_price
            FROM discounts d 
            JOIN food_items fi ON d.food_item_id = fi.id 
            WHERE d.discount_date >= CURDATE() - INTERVAL 7 DAY
            ORDER BY d.created_at DESC");
            
        $suggestions = $this->getSuggestions();
        
        $this->render('discounts/index', compact('discounts', 'suggestions'));
    }
    
    public function suggestions() {
        $suggestions = $this->getSuggestions();
        echo json_encode($suggestions);
        exit;
    }
    
    private function getSuggestions() {
        $today = date('Y-m-d');
        $current_time = date('H:i:s');
        
        // Get today's leftovers
        $leftovers = DB::query("
            SELECT dp.*, fi.name, fi.selling_price, fi.cost_price
            FROM daily_production dp 
            JOIN food_items fi ON dp.food_item_id = fi.id 
            WHERE dp.production_date = ? AND dp.quantity_leftover > 0", [$today]);
        
        $suggestions = [];
        foreach ($leftovers as $item) {
            $leftover_qty = $item['quantity_leftover'];
            $leftover_percentage = ($leftover_qty / $item['quantity_produced']) * 100;
            
            // Suggest discount based on time and leftover percentage
            $discount = 0;
            if ($current_time >= '18:00:00') {
                $discount = $leftover_percentage > 50 ? 40 : 30;
            } elseif ($current_time >= '15:00:00') {
                $discount = $leftover_percentage > 30 ? 25 : 15;
            } elseif ($leftover_percentage > 70) {
                $discount = 20;
            }
            
            if ($discount > 0) {
                $suggestions[] = [
                    'food_item_id' => $item['food_item_id'],
                    'food_name' => $item['name'],
                    'leftover_qty' => $leftover_qty,
                    'suggested_discount' => $discount,
                    'original_price' => $item['selling_price'],
                    'discounted_price' => $item['selling_price'] * (1 - $discount/100)
                ];
            }
        }
        
        return $suggestions;
    }
    
    public function add() {
        $error = '';
        
        // Get food items with leftover info
        $foodItems = DB::query("
            SELECT fi.id, fi.name, fi.selling_price, 
                   COALESCE(dp.quantity_leftover, 0) as leftover
            FROM food_items fi
            LEFT JOIN daily_production dp ON fi.id = dp.food_item_id 
                AND dp.production_date = CURDATE()
            ORDER BY fi.name");
        
        $this->render('discounts/add', compact('foodItems', 'error'));
    }
    
    public function create() {
        if ($_POST) {
            $food_item_id = (int)($_POST['food_item_id'] ?? 0);
            $discount_percentage = (int)($_POST['discount_percentage'] ?? 0);
            $start_time = $_POST['start_time'] ?? date('H:i:s');
            $end_time = $_POST['end_time'] ?? '23:59:59';
            $leftover_quantity = (int)($_POST['leftover_quantity'] ?? 0);
            
            if ($food_item_id && $discount_percentage > 0) {
                DB::query("INSERT INTO discounts (food_item_id, discount_date, discount_percentage, start_time, end_time, leftover_quantity, status) 
                    VALUES (?, CURDATE(), ?, ?, ?, ?, 'active')", 
                    [$food_item_id, $discount_percentage, $start_time, $end_time, $leftover_quantity]);
                
                // Get food item details
                $food_item = DB::query("SELECT name, selling_price FROM food_items WHERE id = ?", [$food_item_id])[0];
                $discounted_price = $food_item['selling_price'] * (1 - $discount_percentage/100);
                
                // Create notification
                $message = "{$food_item['name']} - {$discount_percentage}% OFF until {$end_time}";
                DB::query("INSERT INTO notifications (title, message, type) VALUES (?, ?, 'discount')", 
                    ["Special Discount Available!", $message]);
                
                // Send emails to subscribers
                $this->sendDiscountEmails($food_item['name'], $discount_percentage, $food_item['selling_price'], $discounted_price, $end_time);
                
                header('Location: index.php?r=discounts/index');
                exit;
            }
        }
        header('Location: index.php?r=discounts/index');
        exit;
    }
    
    public function activate() {
        if ($_POST) {
            $id = (int)($_POST['id'] ?? 0);
            DB::query("UPDATE discounts SET status = 'active' WHERE id = ?", [$id]);
        }
        header('Location: index.php?r=discounts/index');
        exit;
    }
    
    public function cancel() {
        if ($_POST) {
            $id = (int)($_POST['id'] ?? 0);
            DB::query("UPDATE discounts SET status = 'cancelled' WHERE id = ?", [$id]);
        }
        header('Location: index.php?r=discounts/index');
        exit;
    }
    
    private function sendDiscountEmails($food_name, $discount_percentage, $original_price, $discounted_price, $end_time) {
        // Get all active subscribers
        $subscribers = DB::query("SELECT email, unsubscribe_token FROM email_subscribers WHERE active = 1");
        
        if (empty($subscribers)) return;
        
        $subject = "🔥 {$discount_percentage}% OFF on {$food_name}!";
        $savings = $original_price - $discounted_price;
        
        foreach ($subscribers as $sub) {
            $unsubscribe_link = "http://localhost/Food/public/customer.php?unsubscribe={$sub['unsubscribe_token']}";
            
            $message = "<html><body style='font-family: Arial, sans-serif; line-height: 1.6; color: #333;'>
    <div style='max-width: 600px; margin: 0 auto; padding: 20px; background: #f9f9f9;'>
        <div style='background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); padding: 30px; text-align: center; border-radius: 10px;'>
            <h1 style='color: white; margin: 0; font-size: 2rem;'>🍽️ Special Discount Alert!</h1>
        </div>
        <div style='background: white; padding: 30px; margin-top: 20px; border-radius: 10px;'>
            <h2 style='color: #667eea; margin-top: 0;'>{$food_name}</h2>
            <div style='background: #ff6b6b; color: white; padding: 20px; border-radius: 10px; text-align: center; margin: 20px 0;'>
                <div style='font-size: 3rem; font-weight: bold;'>{$discount_percentage}% OFF</div>
                <div style='font-size: 1.2rem; margin-top: 10px;'>Limited Time Only!</div>
            </div>
            <div style='text-align: center; margin: 30px 0;'>
                <div style='font-size: 2rem; font-weight: bold; color: #ff6b6b;'>\$" . number_format($discounted_price, 2) . "</div>
                <div style='font-size: 1.2rem; color: #999; text-decoration: line-through;'>\$" . number_format($original_price, 2) . "</div>
                <div style='font-size: 1rem; color: #28a745; margin-top: 10px; font-weight: bold;'>You save \$" . number_format($savings, 2) . "!</div>
            </div>
            <div style='background: #fff3cd; padding: 15px; border-radius: 5px; margin: 20px 0;'>
                <strong>⏰ Hurry!</strong> This offer is valid until <strong>{$end_time}</strong>
            </div>
            <div style='text-align: center; margin: 30px 0;'>
                <a href='http://localhost/Food/public/customer.php' style='display: inline-block; background: #667eea; color: white; padding: 15px 40px; text-decoration: none; border-radius: 5px; font-weight: bold; font-size: 1.1rem;'>View All Offers</a>
            </div>
        </div>
        <div style='text-align: center; margin-top: 20px; color: #999; font-size: 0.9rem;'>
            <p>You're receiving this because you subscribed to our discount alerts.</p>
            <p><a href='{$unsubscribe_link}' style='color: #667eea;'>Unsubscribe</a></p>
        </div>
    </div>
</body></html>";
            
            $headers = "MIME-Version: 1.0\r\n";
            $headers .= "Content-type:text/html;charset=UTF-8\r\n";
            $headers .= "From: Restaurant Discounts <noreply@restaurant.com>\r\n";
            
            @mail($sub['email'], $subject, $message, $headers);
        }
    }
}