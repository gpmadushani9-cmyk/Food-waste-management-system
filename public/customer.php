<?php
// Public customer page - no login required
require_once __DIR__ . '/../app/bootstrap.php';

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
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['email'])) {
    $email = trim($_POST['email']);
    if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $token = bin2hex(random_bytes(32));
        try {
            DB::query("INSERT INTO email_subscribers (email, unsubscribe_token) VALUES (?, ?)", [$email, $token]);
            $subscribe_message = "✅ Successfully subscribed! You will receive discount alerts.";
        } catch (Exception $e) {
            $subscribe_message = "✅ You are already subscribed!";
        }
    } else {
        $subscribe_error = "❌ Invalid email address";
    }
}
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>Today's Special Offers - Food Discounts</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="assets/css/styles.css">
  <style>
    body { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); min-height: 100vh; }
    .customer-nav {
      background: rgba(255,255,255,0.95);
      backdrop-filter: blur(10px);
      padding: 1rem 0;
      margin-bottom: 2rem;
      border-radius: 1rem;
      box-shadow: 0 20px 25px -5px rgb(0 0 0 / 0.1);
    }
    .customer-nav ul {
      display: flex;
      gap: 1rem;
      list-style: none;
      margin: 0;
      padding: 0;
      justify-content: center;
    }
    .customer-nav a {
      padding: 0.75rem 1.5rem;
      border-radius: 0.5rem;
      font-weight: 600;
      transition: all 0.3s;
    }
    .customer-nav a.active {
      background: var(--primary);
      color: white;
    }
    .customer-nav a:not(.active) {
      color: var(--text-primary);
    }
    .customer-nav a:not(.active):hover {
      background: var(--gray-100);
      text-decoration: none;
    }
    .notification-bell {
      position: fixed;
      top: 2rem;
      right: 2rem;
      background: white;
      width: 50px;
      height: 50px;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 1.5rem;
      cursor: pointer;
      box-shadow: 0 4px 6px rgba(0,0,0,0.1);
      z-index: 1000;
    }
    .notification-popup {
      position: fixed;
      top: 5rem;
      right: 2rem;
      background: white;
      border-radius: 1rem;
      box-shadow: 0 20px 25px -5px rgb(0 0 0 / 0.3);
      width: 350px;
      max-height: 400px;
      overflow-y: auto;
      display: none;
      z-index: 999;
    }
    .notification-popup.show { display: block; }
  </style>
</head>
<body>
<div class="container" style="max-width: 1200px; padding: 2rem;">
  <!-- Navigation -->
  <div class="customer-nav">
    <ul>
      <li><a href="customer.php" class="<?= !isset($_GET['page']) || $_GET['page'] === 'menu' ? 'active' : '' ?>">🍽️ Our Menu</a></li>
      <li><a href="customer.php?page=discounts" class="<?= isset($_GET['page']) && $_GET['page'] === 'discounts' ? 'active' : '' ?>">🔥 Special Discounts</a></li>
    </ul>
  </div>

  <!-- Notification Bell -->
  <div class="notification-bell" onclick="toggleNotifications()">
    🔔
    <?php if (!empty($notifications)): ?>
      <span style="position: absolute; top: 5px; right: 5px; background: red; color: white; border-radius: 50%; width: 20px; height: 20px; font-size: 0.75rem; display: flex; align-items: center; justify-content: center;">
        <?= count($notifications) ?>
      </span>
    <?php endif; ?>
  </div>

  <!-- Notification Popup -->
  <div class="notification-popup" id="notificationPopup">
    <div style="padding: 1.5rem; border-bottom: 1px solid var(--border-light);">
      <h3 style="margin: 0;">📢 Latest Updates</h3>
    </div>
    <?php if (!empty($notifications)): ?>
      <?php foreach ($notifications as $notification): ?>
        <div style="padding: 1rem; border-bottom: 1px solid var(--border-light);">
          <h4 style="margin: 0 0 0.5rem 0; font-size: 0.875rem;"><?= htmlspecialchars($notification['title']) ?></h4>
          <p style="margin: 0; font-size: 0.75rem; color: var(--text-muted);"><?= htmlspecialchars($notification['message']) ?></p>
          <small style="color: var(--text-light);"><?= date('g:i A', strtotime($notification['created_at'])) ?></small>
        </div>
      <?php endforeach; ?>
    <?php else: ?>
      <div style="padding: 2rem; text-align: center; color: var(--text-muted);">
        No notifications
      </div>
    <?php endif; ?>
  </div>
  <div class="customer-header">
    <h1 style="font-size: 3rem; margin-bottom: 1rem;">🍽️ Today's Special Offers</h1>
    <p style="font-size: 1.25rem; color: var(--text-secondary); margin-bottom: 0;">
      Fresh food at amazing prices • Limited time offers
    </p>
    <div style="margin-top: 1rem; font-size: 0.875rem; color: var(--text-muted);">
      Last updated: <span id="last-updated"><?= date('g:i A') ?></span>
    </div>
  </div>

  <?php if (!isset($_GET['page']) || $_GET['page'] === 'menu'): ?>
    <!-- Our Menu Page -->
    <div style="margin-bottom: 3rem;">
      <h2 style="color: white; text-align: center; margin-bottom: 2rem; font-size: 2rem;">🍽️ Our Menu</h2>
      <div class="grid grid--3" style="gap: 1.5rem;">
        <?php foreach ($all_foods as $food): 
          $has_discount = !empty($food['discount_percentage']) && $food['discount_status'] === 'active';
          $display_price = $has_discount 
            ? $food['selling_price'] * (1 - $food['discount_percentage']/100) 
            : $food['selling_price'];
        ?>
          <div class="card" style="background: rgba(255,255,255,0.95); padding: 1.5rem; position: relative;">
            <?php if ($has_discount): ?>
              <div style="position: absolute; top: 10px; right: 10px; background: #ff6b6b; color: white; padding: 0.5rem 1rem; border-radius: 2rem; font-weight: 700; font-size: 0.875rem;">
                <?= $food['discount_percentage'] ?>% OFF
              </div>
            <?php endif; ?>
            
            <div style="text-align: center;">
              <div style="font-size: 3rem; margin-bottom: 1rem;">🍽️</div>
              <h3 style="margin-bottom: 0.5rem; color: var(--text-primary);"><?= htmlspecialchars($food['name']) ?></h3>
              <span class="pill" style="margin-bottom: 1rem; display: inline-block;"><?= htmlspecialchars($food['category']) ?></span>
              
              <div style="margin-top: 1rem;">
                <?php if ($has_discount): ?>
                  <div style="font-size: 1.5rem; font-weight: 700; color: #ff6b6b;">
                    $<?= number_format($display_price, 2) ?>
                  </div>
                  <div style="font-size: 1rem; color: var(--text-muted);">
                    <strike>$<?= number_format($food['selling_price'], 2) ?></strike>
                  </div>
                <?php else: ?>
                  <div style="font-size: 1.5rem; font-weight: 700; color: var(--primary);">
                    $<?= number_format($display_price, 2) ?>
                  </div>
                <?php endif; ?>
              </div>
              
              <?php if ($food['available_qty'] > 0): ?>
                <div style="margin-top: 1rem; padding: 0.5rem; background: #d4edda; color: #155724; border-radius: 0.5rem; font-size: 0.875rem;">
                  ✅ Available: <?= $food['available_qty'] ?> items
                </div>
              <?php else: ?>
                <div style="margin-top: 1rem; padding: 0.5rem; background: #f8d7da; color: #721c24; border-radius: 0.5rem; font-size: 0.875rem;">
                  ⏳ Check back later
                </div>
              <?php endif; ?>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    </div>
  <?php else: ?>
    <!-- Special Discounts Page -->
    <?php if (!empty($discounts)): ?>
      <div style="margin-bottom: 3rem;">
        <h2 style="color: white; text-align: center; margin-bottom: 2rem; font-size: 2rem;">🔥 Special Discounts Today!</h2>
        
        <!-- Carousel -->
        <div style="position: relative; overflow: hidden; border-radius: 1rem; margin-bottom: 2rem;">
          <div id="carousel" style="display: flex; transition: transform 0.5s ease;">
            <?php foreach ($discounts as $index => $discount): 
              $original_price = $discount['selling_price'];
              $discounted_price = $original_price * (1 - $discount['discount_percentage']/100);
              $savings = $original_price - $discounted_price;
            ?>
              <div style="min-width: 100%; background: linear-gradient(135deg, #ff6b6b, #ee5a24); color: white; padding: 3rem; border-radius: 1rem;">
                <div style="text-align: center;">
                  <h2 style="color: white; font-size: 3rem; margin-bottom: 1rem;"><?= htmlspecialchars($discount['name']) ?></h2>
                  <div style="font-size: 5rem; font-weight: 700; color: #ffeb3b; margin: 2rem 0;">
                    <?= $discount['discount_percentage'] ?>% OFF
                  </div>
                  <div style="font-size: 2rem; margin-bottom: 1rem;">
                    <strike style="opacity: 0.7;">$<?= number_format($original_price, 2) ?></strike>
                    <span style="color: #ffeb3b; font-weight: 700; margin-left: 1rem;">$<?= number_format($discounted_price, 2) ?></span>
                  </div>
                  <div style="font-size: 1.5rem; margin-top: 2rem;">
                    ⏰ Until <?= date('g:i A', strtotime($discount['end_time'])) ?> | <?= $discount['leftover_quantity'] ?> items left
                  </div>
                </div>
              </div>
            <?php endforeach; ?>
          </div>
          
          <?php if (count($discounts) > 1): ?>
            <button onclick="prevSlide()" style="position: absolute; left: 1rem; top: 50%; transform: translateY(-50%); background: rgba(255,255,255,0.9); border: none; border-radius: 50%; width: 50px; height: 50px; font-size: 1.5rem; cursor: pointer;">❮</button>
            <button onclick="nextSlide()" style="position: absolute; right: 1rem; top: 50%; transform: translateY(-50%); background: rgba(255,255,255,0.9); border: none; border-radius: 50%; width: 50px; height: 50px; font-size: 1.5rem; cursor: pointer;">❯</button>
          <?php endif; ?>
        </div>
        
        <!-- Discount Grid -->
        <div class="grid grid--2" style="gap: 2rem;">
          <?php foreach ($discounts as $discount): 
            $original_price = $discount['selling_price'];
            $discounted_price = $original_price * (1 - $discount['discount_percentage']/100);
            $savings = $original_price - $discounted_price;
          ?>
            <div class="card" style="background: linear-gradient(135deg, #ff6b6b, #ee5a24); color: white; padding: 2rem;">
              <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">
                <div>
                  <h3 style="color: white; margin-bottom: 0.5rem;"><?= htmlspecialchars($discount['name']) ?></h3>
                  <span style="background: rgba(255,255,255,0.2); color: white; padding: 0.25rem 0.75rem; border-radius: 999px; font-size: 0.875rem;">
                    <?= htmlspecialchars($discount['category']) ?>
                  </span>
                </div>
                <div style="text-align: right;">
                  <div style="font-size: 2.5rem; font-weight: 700; color: #ffeb3b;">
                    <?= $discount['discount_percentage'] ?>%
                  </div>
                  <div style="font-size: 0.875rem; opacity: 0.9;">OFF</div>
                </div>
              </div>
              
              <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
                <div>
                  <div style="font-size: 1.5rem; font-weight: 600; color: white;">
                    $<?= number_format($discounted_price, 2) ?>
                  </div>
                  <div style="font-size: 1rem; opacity: 0.8;">
                    <strike>$<?= number_format($original_price, 2) ?></strike>
                    <span style="color: #ffeb3b; font-weight: 600; margin-left: 0.5rem;">
                      Save $<?= number_format($savings, 2) ?>
                    </span>
                  </div>
                </div>
                <div style="text-align: right;">
                  <div style="font-size: 0.875rem; opacity: 0.9;">Until</div>
                  <div style="font-size: 1.25rem; font-weight: 600; color: #ffeb3b;">
                    <?= date('g:i A', strtotime($discount['end_time'])) ?>
                  </div>
                </div>
              </div>
              
              <div style="background: rgba(255,255,255,0.1); padding: 1rem; border-radius: 0.5rem;">
                <div style="display: flex; justify-content: space-between; align-items: center;">
                  <span style="font-size: 0.875rem; opacity: 0.9;">⏰ Limited Time</span>
                  <span style="font-size: 0.875rem; font-weight: 600; color: #ffeb3b;">
                    <?= $discount['leftover_quantity'] ?> left
                  </span>
                </div>
              </div>
            </div>
          <?php endforeach; ?>
        </div>
      </div>
    <?php else: ?>
      <div style="background: rgba(255,255,255,0.95); padding: 4rem; border-radius: 1rem; text-align: center;">
        <div style="font-size: 4rem; margin-bottom: 2rem;">🍽️</div>
        <h2 style="color: var(--text-primary); margin-bottom: 1rem;">No Special Offers Right Now</h2>
        <p style="color: var(--text-secondary); font-size: 1.125rem;">Check back later for amazing discounts!</p>
      </div>
    <?php endif; ?>
  <?php endif; ?>
  <!-- Email Subscription -->
  <div style="background: rgba(255,255,255,0.95); padding: 2rem; border-radius: 1rem; margin-top: 3rem; box-shadow: 0 20px 25px -5px rgb(0 0 0 / 0.1);">
    <div style="text-align: center;">
      <h3 style="margin-bottom: 1rem; color: var(--primary);">🔔 Get Discount Alerts via Email</h3>
      <p style="color: var(--text-secondary); margin-bottom: 1.5rem;">Subscribe to receive notifications when we have special offers!</p>
      
      <?php if (isset($subscribe_message)): ?>
        <div style="background: #d4edda; color: #155724; padding: 1rem; border-radius: 0.5rem; margin-bottom: 1rem;">
          <?= htmlspecialchars($subscribe_message) ?>
        </div>
      <?php endif; ?>
      
      <?php if (isset($subscribe_error)): ?>
        <div style="background: #f8d7da; color: #721c24; padding: 1rem; border-radius: 0.5rem; margin-bottom: 1rem;">
          <?= htmlspecialchars($subscribe_error) ?>
        </div>
      <?php endif; ?>
      
      <form method="post" style="display: flex; gap: 1rem; max-width: 500px; margin: 0 auto;">
        <input type="email" name="email" placeholder="your@email.com" required 
               style="flex: 1; padding: 0.75rem 1rem; border: 2px solid var(--primary); border-radius: 0.5rem; font-size: 1rem;">
        <button type="submit" 
                style="padding: 0.75rem 2rem; background: var(--primary); color: white; border: none; border-radius: 0.5rem; font-weight: 600; cursor: pointer; font-size: 1rem;">
          Subscribe
        </button>
      </form>
    </div>
  </div>

  <div style="text-align: center; margin-top: 3rem; color: rgba(255,255,255,0.8);">
    <p>🔔 Subscribe above to never miss a discount!</p>
    <p style="font-size: 0.875rem;">Visit our restaurant for the freshest food experience</p>
  </div>
</div>

<div style="position: fixed; bottom: 2rem; right: 2rem; background: rgba(0,0,0,0.7); color: white; padding: 0.5rem 1rem; border-radius: 2rem; font-size: 0.75rem;">
  <span id="refresh-indicator">🔄 Auto-updating...</span>
</div>

<script>
function toggleNotifications() {
  document.getElementById('notificationPopup').classList.toggle('show');
}

document.addEventListener('click', function(e) {
  const popup = document.getElementById('notificationPopup');
  const bell = document.querySelector('.notification-bell');
  if (!popup.contains(e.target) && !bell.contains(e.target)) {
    popup.classList.remove('show');
  }
});

let currentSlide = 0;
const carousel = document.getElementById('carousel');
const totalSlides = <?= count($discounts) ?>;

function nextSlide() {
  currentSlide = (currentSlide + 1) % totalSlides;
  updateCarousel();
}

function prevSlide() {
  currentSlide = (currentSlide - 1 + totalSlides) % totalSlides;
  updateCarousel();
}

function updateCarousel() {
  if (carousel) {
    carousel.style.transform = `translateX(-${currentSlide * 100}%)`;
  }
}

if (totalSlides > 1) {
  setInterval(nextSlide, 5000);
}

let refreshCount = 0;
setInterval(() => {
  refreshCount++;
  document.getElementById('refresh-indicator').innerHTML = `🔄 Updated ${refreshCount} times`;
  location.reload();
}, 30000);
</script>
</body>
</html>