<?php if (!isset($_GET['page']) || $_GET['page'] === 'menu'): ?>
<!-- Hero Section -->
<div style="background: linear-gradient(135deg, rgba(0,0,0,0.8), rgba(139,69,19,0.3)); border-radius: 1rem; padding: 3rem; margin-bottom: 3rem; box-shadow: 0 20px 25px -5px rgb(0 0 0 / 0.3); border: 1px solid rgba(255,255,255,0.1); overflow: hidden;">
  <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 3rem; align-items: center;">
    <div style="z-index: 2;">
      <h1 style="font-size: 3.5rem; margin-bottom: 1.5rem; background: linear-gradient(135deg, #ff6b6b, #ffd93d); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text; font-weight: 800; line-height: 1.2;">
        Freshly Baked Daily
      </h1>
      <p style="font-size: 1.3rem; color: rgba(255,255,255,0.95); line-height: 1.8; margin-bottom: 2rem; font-weight: 300;">
        Indulge in our artisan breads, pastries, and desserts crafted with premium ingredients and traditional recipes.
      </p>
      
      <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 2.5rem;">
        <div style="display: flex; align-items: center; gap: 0.5rem;">
          <span style="color: #ffd93d; font-size: 1.5rem;">✓</span>
          <span style="color: rgba(255,255,255,0.9);">Fresh Daily</span>
        </div>
        <div style="display: flex; align-items: center; gap: 0.5rem;">
          <span style="color: #ffd93d; font-size: 1.5rem;">✓</span>
          <span style="color: rgba(255,255,255,0.9);">Special Offers</span>
        </div>
        <div style="display: flex; align-items: center; gap: 0.5rem;">
          <span style="color: #ffd93d; font-size: 1.5rem;">✓</span>
          <span style="color: rgba(255,255,255,0.9);">Quick Service</span>
        </div>
        <div style="display: flex; align-items: center; gap: 0.5rem;">
          <span style="color: #ffd93d; font-size: 1.5rem;">✓</span>
          <span style="color: rgba(255,255,255,0.9);">Dine-in & Takeaway</span>
        </div>
      </div>
      
      <a href="index.php?r=customer/index&page=discounts" style="display: inline-block; padding: 1.2rem 2.5rem; background: linear-gradient(135deg, #ff6b6b, #ee5a24); color: white; border-radius: 50px; font-weight: 700; text-decoration: none; box-shadow: 0 8px 15px rgba(255,107,107,0.4); transition: all 0.3s ease; font-size: 1.1rem;">
        🔥 View Special Offers
      </a>
    </div>
    
    <div style="position: relative; height: 400px; border-radius: 1rem; overflow: hidden; box-shadow: 0 20px 40px rgba(0,0,0,0.5);">
      <img src="https://images.unsplash.com/photo-1509440159596-0249088772ff?w=800&q=80" 
           alt="Fresh Bakery Products" 
           style="width: 100%; height: 100%; object-fit: cover; border-radius: 1rem;">
      <div style="position: absolute; bottom: 0; left: 0; right: 0; background: linear-gradient(to top, rgba(0,0,0,0.8), transparent); padding: 2rem; color: white;">
        <p style="font-size: 1.2rem; font-weight: 600; margin: 0;">Artisan Bakery Since 1995</p>
      </div>
    </div>
  </div>
</div>

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
        <div class="card" style="background: rgba(0,0,0,0.7); padding: 1.5rem; position: relative; border: 1px solid rgba(255,255,255,0.1);">
          <?php if ($has_discount): ?>
            <div style="position: absolute; top: 10px; right: 10px; background: #ff6b6b; color: white; padding: 0.5rem 1rem; border-radius: 2rem; font-weight: 700; font-size: 0.875rem;">
              <?= $food['discount_percentage'] ?>% OFF
            </div>
          <?php endif; ?>
          
          <div style="text-align: center;">
            <div style="font-size: 3rem; margin-bottom: 1rem;">🍽️</div>
            <h3 style="margin-bottom: 0.5rem; color: white;"><?= htmlspecialchars($food['name']) ?></h3>
            <span class="pill" style="margin-bottom: 1rem; display: inline-block; background: rgba(255,255,255,0.1); color: white; border: 1px solid rgba(255,255,255,0.2);"><?= htmlspecialchars($food['category']) ?></span>
            
            <div style="margin-top: 1rem;">
              <?php if ($has_discount): ?>
                <div style="font-size: 1.5rem; font-weight: 700; color: #ff6b6b;">
                  Rs.<?= number_format($display_price, 2) ?>
                </div>
                <div style="font-size: 1rem; color: rgba(255,255,255,0.6);">
                  <strike>Rs.<?= number_format($food['selling_price'], 2) ?></strike>
                </div>
              <?php else: ?>
                <div style="font-size: 1.5rem; font-weight: 700; color: #ffeb3b;">
                  Rs.<?= number_format($display_price, 2) ?>
                </div>
              <?php endif; ?>
            </div>
            
            <?php if ($food['available_qty'] > 0): ?>
              <div style="margin-top: 1rem; padding: 0.5rem; background: rgba(16,185,129,0.2); color: #10b981; border-radius: 0.5rem; font-size: 0.875rem; border: 1px solid rgba(16,185,129,0.3);">
                ✅ Available: <?= $food['available_qty'] ?> items
              </div>
              <button onclick="addToCart(<?= $food['id'] ?>, '<?= htmlspecialchars($food['name'], ENT_QUOTES) ?>', <?= $food['selling_price'] ?>, <?= $food['discount_percentage'] ?? 0 ?>)" 
                      style="width: 100%; margin-top: 1rem; padding: 0.75rem; background: linear-gradient(135deg, #ff6b6b, #ee5a24); color: white; border: none; border-radius: 0.5rem; font-weight: 700; cursor: pointer; font-size: 1rem; transition: all 0.3s;">
                🛒 Add to Cart
              </button>
            <?php else: ?>
              <div style="margin-top: 1rem; padding: 0.5rem; background: rgba(239,68,68,0.2); color: #ef4444; border-radius: 0.5rem; font-size: 0.875rem; border: 1px solid rgba(239,68,68,0.3);">
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
      <div style="text-align: center; margin-bottom: 3rem;">
        <h2 style="color: white; margin-bottom: 0.5rem; font-size: 3rem; font-weight: 800; background: linear-gradient(135deg, #ff6b6b, #ffd93d); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;">
          🔥 Today's Hot Deals
        </h2>
        <p style="color: rgba(255,255,255,0.7); font-size: 1.2rem;">Limited time offers - Grab them before they're gone!</p>
      </div>
      
      <div class="grid grid--2" style="gap: 2rem;">
        <?php foreach ($discounts as $discount): 
          $original_price = $discount['selling_price'];
          $discounted_price = $original_price * (1 - $discount['discount_percentage']/100);
          $savings = $original_price - $discounted_price;
        ?>
          <div class="card" style="position: relative; background: linear-gradient(135deg, rgba(30,30,30,0.95), rgba(50,50,50,0.95)); border-radius: 1.5rem; overflow: hidden; border: 1px solid rgba(255,255,255,0.1); box-shadow: 0 20px 40px rgba(0,0,0,0.5); transition: transform 0.3s ease;">
            
            <!-- Discount Badge -->
            <div style="position: absolute; top: 1.5rem; right: 1.5rem; z-index: 10;">
              <div style="background: linear-gradient(135deg, #ff6b6b, #ee5a24); padding: 1rem 1.5rem; border-radius: 1rem; box-shadow: 0 8px 20px rgba(255,107,107,0.4); transform: rotate(3deg);">
                <div style="font-size: 2.5rem; font-weight: 900; color: white; line-height: 1; text-shadow: 2px 2px 4px rgba(0,0,0,0.3);">
                  <?= $discount['discount_percentage'] ?>%
                </div>
                <div style="font-size: 0.875rem; color: white; font-weight: 700; text-align: center; margin-top: 0.25rem;">OFF</div>
              </div>
            </div>
            
            <!-- Background Pattern -->
            <div style="position: absolute; top: 0; left: 0; right: 0; bottom: 0; opacity: 0.03; background-image: radial-gradient(circle, white 1px, transparent 1px); background-size: 20px 20px;"></div>
            
            <!-- Image Section -->
            <div style="position: relative; height: 200px; background: linear-gradient(135deg, rgba(255,107,107,0.2), rgba(255,217,61,0.2)); overflow: hidden;">
              <img src="https://images.unsplash.com/photo-1555507036-ab1f4038808a?w=600&q=80" 
                   alt="Discount Product" 
                   style="width: 100%; height: 100%; object-fit: cover; opacity: 0.7;">
              <div style="position: absolute; bottom: 0; left: 0; right: 0; background: linear-gradient(to top, rgba(0,0,0,0.8), transparent); padding: 1.5rem;">
                <span style="background: rgba(255,107,107,0.9); color: white; padding: 0.4rem 1rem; border-radius: 50px; font-size: 0.875rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px;">
                  <?= htmlspecialchars($discount['category']) ?>
                </span>
              </div>
            </div>
            
            <!-- Content Section -->
            <div style="padding: 2rem; position: relative; z-index: 2;">
              <h3 style="color: white; margin-bottom: 1.5rem; font-size: 1.75rem; font-weight: 700;">
                <?= htmlspecialchars($discount['name']) ?>
              </h3>
              
              <!-- Price Section -->
              <div style="background: rgba(255,255,255,0.05); padding: 1.5rem; border-radius: 1rem; margin-bottom: 1.5rem; border: 1px solid rgba(255,255,255,0.1);">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">
                  <div>
                    <div style="font-size: 0.875rem; color: rgba(255,255,255,0.6); margin-bottom: 0.25rem; text-transform: uppercase; letter-spacing: 1px;">
                      Sale Price
                    </div>
                    <div style="font-size: 2.5rem; font-weight: 900; background: linear-gradient(135deg, #ffd93d, #ff6b6b); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text; line-height: 1;">
                      Rs.<?= number_format($discounted_price, 2) ?>
                    </div>
                  </div>
                  <div style="text-align: right;">
                    <div style="font-size: 1.125rem; color: rgba(255,255,255,0.5); text-decoration: line-through; margin-bottom: 0.25rem;">
                      Rs.<?= number_format($original_price, 2) ?>
                    </div>
                    <div style="background: linear-gradient(135deg, #06D6A0, #00C9A7); color: white; padding: 0.4rem 1rem; border-radius: 50px; font-size: 0.875rem; font-weight: 700; display: inline-block;">
                      Save Rs.<?= number_format($savings, 2) ?>
                    </div>
                  </div>
                </div>
              </div>
              
              <!-- Time and Stock Section -->
              <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                <div style="background: linear-gradient(135deg, rgba(255,107,107,0.15), rgba(238,90,36,0.15)); padding: 1.25rem; border-radius: 1rem; border: 1px solid rgba(255,107,107,0.3);">
                  <div style="display: flex; align-items: center; gap: 0.75rem;">
                    <span style="font-size: 1.75rem;">⏰</span>
                    <div>
                      <div style="font-size: 0.75rem; color: rgba(255,255,255,0.6); margin-bottom: 0.25rem; text-transform: uppercase;">Ends At</div>
                      <div style="font-size: 1.25rem; font-weight: 700; color: #ffd93d;">
                        <?= date('g:i A', strtotime($discount['end_time'])) ?>
                      </div>
                    </div>
                  </div>
                </div>
                
                <div style="background: linear-gradient(135deg, rgba(255,217,61,0.15), rgba(255,166,43,0.15)); padding: 1.25rem; border-radius: 1rem; border: 1px solid rgba(255,217,61,0.3);">
                  <div style="display: flex; align-items: center; gap: 0.75rem;">
                    <span style="font-size: 1.75rem;">📦</span>
                    <div>
                      <div style="font-size: 0.75rem; color: rgba(255,255,255,0.6); margin-bottom: 0.25rem; text-transform: uppercase;">In Stock</div>
                      <div style="font-size: 1.25rem; font-weight: 700; color: #ffd93d;">
                        <?= $discount['leftover_quantity'] ?> Left
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              
              <!-- Progress Bar for Stock -->
              <div style="margin-top: 1rem; background: rgba(255,255,255,0.1); height: 8px; border-radius: 50px; overflow: hidden;">
                <div style="height: 100%; background: linear-gradient(90deg, #ff6b6b, #ffd93d); width: <?= min(100, ($discount['leftover_quantity'] / 100) * 100) ?>%; border-radius: 50px; transition: width 0.3s ease;"></div>
              </div>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    </div>
  <?php else: ?>
    <div style="background: linear-gradient(135deg, rgba(30,30,30,0.95), rgba(50,50,50,0.95)); padding: 5rem 4rem; border-radius: 2rem; text-align: center; border: 1px solid rgba(255,255,255,0.1); box-shadow: 0 20px 40px rgba(0,0,0,0.5);">
      <div style="margin-bottom: 2rem; position: relative; display: inline-block;">
        <div style="font-size: 8rem; filter: grayscale(1); opacity: 0.3;">🍽️</div>
        <div style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); background: rgba(255,107,107,0.2); width: 150px; height: 150px; border-radius: 50%; filter: blur(30px);"></div>
      </div>
      <h2 style="color: white; margin-bottom: 1rem; font-size: 2.5rem; font-weight: 800;">No Active Deals Right Now</h2>
      <p style="color: rgba(255,255,255,0.6); font-size: 1.25rem; max-width: 500px; margin: 0 auto; line-height: 1.8;">
        Don't worry! Amazing discounts are coming soon. Check back later for exclusive offers!
      </p>
      <div style="margin-top: 2rem; display: inline-block; padding: 1rem 2rem; background: rgba(255,255,255,0.1); border-radius: 50px; border: 1px solid rgba(255,255,255,0.2);">
        <span style="color: rgba(255,255,255,0.8); font-size: 0.95rem;">💡 Pro tip: Subscribe to get notified about new deals!</span>
      </div>
    </div>
  <?php endif; ?>
<?php endif; ?>

<?php if (isset($_GET['page']) && $_GET['page'] === 'cart'): ?>
  <!-- Shopping Cart Page -->
  <div id="cart-view">
    <div style="background: rgba(0,0,0,0.7); padding: 2rem; border-radius: 1rem; margin-bottom: 2rem;">
      <h2 style="color: white; margin-bottom: 1.5rem; font-size: 2rem;">🛒 Your Shopping Cart</h2>
      <div id="cart-items"></div>
      
      <div style="background: rgba(255,255,255,0.1); padding: 1.5rem; border-radius: 1rem; margin-top: 2rem; border: 2px solid rgba(255,255,255,0.2);">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">
          <span style="color: white; font-size: 1.5rem; font-weight: 700;">Total:</span>
          <span id="cart-total" style="color: #ffd93d; font-size: 2rem; font-weight: 900;">Rs.0.00</span>
        </div>
        <div style="display: flex; gap: 1rem;">
          <button onclick="showCheckoutForm()" style="flex: 1; padding: 1rem; background: linear-gradient(135deg, #10b981, #059669); color: white; border: none; border-radius: 0.5rem; font-weight: 700; cursor: pointer; font-size: 1.1rem;">
            ✅ Proceed to Checkout
          </button>
          <button onclick="clearCart()" style="padding: 1rem 2rem; background: rgba(239,68,68,0.2); color: #ef4444; border: 1px solid #ef4444; border-radius: 0.5rem; font-weight: 700; cursor: pointer;">
            🗑️ Clear Cart
          </button>
        </div>
      </div>
    </div>
  </div>
  
  <!-- Checkout Form -->
  <div id="checkout-form" style="display: none;">
    <div style="background: rgba(0,0,0,0.7); padding: 2rem; border-radius: 1rem;">
      <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
        <h2 style="color: white; margin: 0; font-size: 2rem;">📋 Checkout</h2>
        <button onclick="showCart()" style="background: rgba(255,255,255,0.1); color: white; border: none; padding: 0.75rem 1.5rem; border-radius: 0.5rem; cursor: pointer; font-weight: 600;">
          ← Back to Cart
        </button>
      </div>
      
      <form onsubmit="placeOrder(event)" style="max-width: 600px;">
        <input type="hidden" name="csrf" value="<?= htmlspecialchars(CSRF::token()) ?>">
        
        <div style="margin-bottom: 1.5rem;">
          <label style="color: white; display: block; margin-bottom: 0.5rem; font-weight: 600;">Full Name *</label>
          <input type="text" name="customer_name" required 
                 style="width: 100%; padding: 0.75rem; border: 2px solid rgba(255,255,255,0.2); border-radius: 0.5rem; background: rgba(255,255,255,0.1); color: white; font-size: 1rem;" 
                 placeholder="John Doe">
        </div>
        
        <div style="margin-bottom: 1.5rem;">
          <label style="color: white; display: block; margin-bottom: 0.5rem; font-weight: 600;">Email *</label>
          <input type="email" name="customer_email" required 
                 style="width: 100%; padding: 0.75rem; border: 2px solid rgba(255,255,255,0.2); border-radius: 0.5rem; background: rgba(255,255,255,0.1); color: white; font-size: 1rem;" 
                 placeholder="john@example.com">
        </div>
        
        <div style="margin-bottom: 1.5rem;">
          <label style="color: white; display: block; margin-bottom: 0.5rem; font-weight: 600;">Phone Number *</label>
          <input type="tel" name="customer_phone" required 
                 style="width: 100%; padding: 0.75rem; border: 2px solid rgba(255,255,255,0.2); border-radius: 0.5rem; background: rgba(255,255,255,0.1); color: white; font-size: 1rem;" 
                 placeholder="0771234567">
        </div>
        
        <div style="margin-bottom: 1.5rem;">
          <label style="color: white; display: block; margin-bottom: 0.5rem; font-weight: 600;">Delivery Address *</label>
          <textarea name="customer_address" required rows="3" 
                    style="width: 100%; padding: 0.75rem; border: 2px solid rgba(255,255,255,0.2); border-radius: 0.5rem; background: rgba(255,255,255,0.1); color: white; font-size: 1rem; resize: vertical;" 
                    placeholder="123 Main Street, Colombo"></textarea>
        </div>
        
        <div style="margin-bottom: 1.5rem;">
          <label style="color: white; display: block; margin-bottom: 0.5rem; font-weight: 600;">Order Notes (Optional)</label>
          <textarea name="notes" rows="2" 
                    style="width: 100%; padding: 0.75rem; border: 2px solid rgba(255,255,255,0.2); border-radius: 0.5rem; background: rgba(255,255,255,0.1); color: white; font-size: 1rem; resize: vertical;" 
                    placeholder="Any special instructions?"></textarea>
        </div>
        
        <div style="background: rgba(255,255,255,0.1); padding: 1.5rem; border-radius: 1rem; margin-bottom: 1.5rem;">
          <div style="display: flex; justify-content: space-between; align-items: center;">
            <span style="color: white; font-size: 1.25rem; font-weight: 700;">Order Total:</span>
            <span id="checkout-total" style="color: #ffd93d; font-size: 1.75rem; font-weight: 900;"></span>
          </div>
        </div>
        
        <button type="submit" style="width: 100%; padding: 1.25rem; background: linear-gradient(135deg, #10b981, #059669); color: white; border: none; border-radius: 0.5rem; font-weight: 700; cursor: pointer; font-size: 1.2rem;">
          <span>✅</span> Place Order
        </button>
        
        <p style="color: rgba(255,255,255,0.6); text-align: center; margin-top: 1rem; font-size: 0.875rem;">
          💵 No payment required. Pay on delivery.
        </p>
      </form>
    </div>
  </div>
<?php endif; ?>

<style>
.card:hover {
  transform: translateY(-10px);
}
</style>

<!-- Email Subscription -->
<div style="background: rgba(255,255,255,0.95); padding: 2rem; border-radius: 1rem; margin-top: 3rem; box-shadow: 0 20px 25px -5px rgb(0 0 0 / 0.1);">
  <div style="text-align: center;">
    <h3 style="margin-bottom: 1rem; color: var(--primary);">🔔 Get Discount Alerts via Email</h3>
    <p style="color: var(--text-secondary); margin-bottom: 1.5rem;">Subscribe to receive notifications when we have special offers!</p>
    
    <?php if (isset($_SESSION['subscribe_success'])): ?>
      <div style="background: #d4edda; color: #155724; padding: 1rem; border-radius: 0.5rem; margin-bottom: 1rem;">
        <?= htmlspecialchars($_SESSION['subscribe_success']) ?>
      </div>
      <?php unset($_SESSION['subscribe_success']); ?>
    <?php endif; ?>
    
    <?php if (isset($_SESSION['subscribe_error'])): ?>
      <div style="background: #f8d7da; color: #721c24; padding: 1rem; border-radius: 0.5rem; margin-bottom: 1rem;">
        <?= htmlspecialchars($_SESSION['subscribe_error']) ?>
      </div>
      <?php unset($_SESSION['subscribe_error']); ?>
    <?php endif; ?>
    
    <form method="post" action="index.php?r=customer/index" style="display: flex; gap: 1rem; max-width: 500px; margin: 0 auto;">
      <input type="email" name="email" placeholder="your@email.com" required 
             style="flex: 1; padding: 0.75rem 1rem; border: 2px solid var(--primary); border-radius: 0.5rem; font-size: 1rem;">
      <button type="submit" 
              style="padding: 0.75rem 2rem; background: var(--primary); color: white; border: none; border-radius: 0.5rem; font-weight: 600; cursor: pointer; font-size: 1rem;">
        Subscribe
      </button>
    </form>
  </div>
</div>