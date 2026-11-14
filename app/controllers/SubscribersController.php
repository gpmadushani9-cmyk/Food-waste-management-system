<?php

class SubscribersController extends Controller {
    
    public function subscribe() {
        if ($_POST) {
            $email = trim($_POST['email'] ?? '');
            
            if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $existing = DB::query("SELECT id FROM subscribers WHERE email = ?", [$email]);
                
                if (empty($existing)) {
                    DB::query("INSERT INTO subscribers (email) VALUES (?)", [$email]);
                    echo json_encode(['success' => true, 'message' => 'Successfully subscribed!']);
                } else {
                    echo json_encode(['success' => true, 'message' => 'Already subscribed!']);
                }
            } else {
                echo json_encode(['success' => false, 'message' => 'Invalid email address']);
            }
            exit;
        }
    }
    
    public function unsubscribe() {
        $email = $_GET['email'] ?? '';
        
        if ($email) {
            DB::query("UPDATE subscribers SET active = 0 WHERE email = ?", [$email]);
            echo "You have been unsubscribed successfully.";
        }
        exit;
    }
    
    public static function sendDiscountNotification($discount_id) {
        $discount = DB::query("
            SELECT d.*, fi.name, fi.selling_price 
            FROM discounts d 
            JOIN food_items fi ON d.food_item_id = fi.id 
            WHERE d.id = ?", [$discount_id])[0] ?? null;
        
        if (!$discount) return;
        
        $subscribers = DB::query("SELECT email FROM subscribers WHERE active = 1");
        
        $subject = "🔥 Special Discount Alert: {$discount['name']} - {$discount['discount_percentage']}% OFF!";
        $discounted_price = $discount['selling_price'] * (1 - $discount['discount_percentage']/100);
        
        $message = "
        <html>
        <body style='font-family: Arial, sans-serif; background: #f5f5f5; padding: 20px;'>
            <div style='max-width: 600px; margin: 0 auto; background: white; border-radius: 10px; overflow: hidden; box-shadow: 0 4px 6px rgba(0,0,0,0.1);'>
                <div style='background: linear-gradient(135deg, #ff6b6b, #ee5a24); color: white; padding: 30px; text-align: center;'>
                    <h1 style='margin: 0; font-size: 32px;'>🍽️ Special Discount Alert!</h1>
                </div>
                
                <div style='padding: 30px;'>
                    <h2 style='color: #333; margin-top: 0;'>{$discount['name']}</h2>
                    
                    <div style='background: #fff3cd; border-left: 4px solid #ffc107; padding: 15px; margin: 20px 0;'>
                        <div style='font-size: 36px; font-weight: bold; color: #ff6b6b;'>
                            {$discount['discount_percentage']}% OFF
                        </div>
                    </div>
                    
                    <div style='margin: 20px 0;'>
                        <div style='font-size: 14px; color: #666;'>Original Price:</div>
                        <div style='font-size: 20px; text-decoration: line-through; color: #999;'>
                            $" . number_format($discount['selling_price'], 2) . "
                        </div>
                        
                        <div style='font-size: 14px; color: #666; margin-top: 10px;'>Discounted Price:</div>
                        <div style='font-size: 32px; font-weight: bold; color: #28a745;'>
                            $" . number_format($discounted_price, 2) . "
                        </div>
                    </div>
                    
                    <div style='background: #f8f9fa; padding: 15px; border-radius: 5px; margin: 20px 0;'>
                        <div style='font-size: 14px; color: #666;'>⏰ Available until:</div>
                        <div style='font-size: 18px; font-weight: bold; color: #333;'>
                            " . date('g:i A', strtotime($discount['end_time'])) . "
                        </div>
                        
                        <div style='font-size: 14px; color: #666; margin-top: 10px;'>📦 Only:</div>
                        <div style='font-size: 18px; font-weight: bold; color: #ff6b6b;'>
                            {$discount['leftover_quantity']} items left!
                        </div>
                    </div>
                    
                    <div style='text-align: center; margin: 30px 0;'>
                        <a href='http://localhost/Food/public/index.php?r=customer/index' 
                           style='background: linear-gradient(135deg, #ff6b6b, #ee5a24); 
                                  color: white; 
                                  padding: 15px 40px; 
                                  text-decoration: none; 
                                  border-radius: 25px; 
                                  font-size: 18px; 
                                  font-weight: bold;
                                  display: inline-block;'>
                            View All Offers →
                        </a>
                    </div>
                    
                    <div style='text-align: center; margin-top: 30px; padding-top: 20px; border-top: 1px solid #eee;'>
                        <p style='color: #999; font-size: 12px; margin: 5px 0;'>
                            You're receiving this because you subscribed to our discount alerts.
                        </p>
                        <p style='color: #999; font-size: 12px; margin: 5px 0;'>
                            <a href='http://localhost/Food/public/index.php?r=subscribers/unsubscribe&email={{EMAIL}}' 
                               style='color: #666;'>Unsubscribe</a>
                        </p>
                    </div>
                </div>
            </div>
        </body>
        </html>
        ";
        
        $headers = "MIME-Version: 1.0\r\n";
        $headers .= "Content-type: text/html; charset=UTF-8\r\n";
        $headers .= "From: Food Waste Management <noreply@restaurant.com>\r\n";
        
        foreach ($subscribers as $subscriber) {
            $personalized_message = str_replace('{{EMAIL}}', urlencode($subscriber['email']), $message);
            mail($subscriber['email'], $subject, $personalized_message, $headers);
        }
    }
}