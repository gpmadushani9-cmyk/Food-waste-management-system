<div class="flex flex--between flex--center mb-lg">
  <div>
    <h1>Create Custom Discount</h1>
    <p class="text-muted mb-0">Set your own discount percentage and time period</p>
  </div>
  <a href="index.php?r=discounts/index" class="btn btn--outline">
    <span>←</span> Back to Discounts
  </a>
</div>

<div class="grid grid--2">
  <div class="card">
    <div class="card__header">
      <div>
        <h3 class="card__title">Discount Details</h3>
        <p class="card__subtitle">Configure your custom discount offer</p>
      </div>
      <span style="font-size: 2rem;">💸</span>
    </div>

    <?php if (!empty($error)): ?>
      <div class="alert alert--danger">
        <strong>Error:</strong> <?= htmlspecialchars($error) ?>
      </div>
    <?php endif; ?>

    <form method="post" action="index.php?r=discounts/create" class="form">
      <input type="hidden" name="csrf" value="<?= htmlspecialchars(CSRF::token()) ?>">
      
      <div class="form__group">
        <label for="food_item_id">Food Item *</label>
        <select id="food_item_id" name="food_item_id" required onchange="updatePricePreview()">
          <option value="">Select food item</option>
          <?php foreach ($foodItems as $item): ?>
            <option value="<?= $item['id'] ?>" 
                    data-price="<?= $item['selling_price'] ?>"
                    data-leftover="<?= $item['leftover'] ?? 0 ?>">
              <?= htmlspecialchars($item['name']) ?> 
              (Rs.<?= number_format($item['selling_price'], 2) ?>)
              <?php if (isset($item['leftover']) && $item['leftover'] > 0): ?>
                - <?= $item['leftover'] ?> left
              <?php endif; ?>
            </option>
          <?php endforeach; ?>
        </select>
        <small class="text-muted">Choose the item to discount</small>
      </div>
      
      <div class="form__row">
        <div class="form__group">
          <label for="discount_percentage">Discount Percentage (%) *</label>
          <input type="number" 
                 id="discount_percentage" 
                 name="discount_percentage" 
                 min="1" 
                 max="90" 
                 step="1"
                 required 
                 placeholder="e.g., 20"
                 oninput="updatePricePreview()" />
          <small class="text-muted">Enter discount from 1% to 90%</small>
        </div>
        
        <div class="form__group">
          <label for="leftover_quantity">Available Quantity *</label>
          <input type="number" 
                 id="leftover_quantity" 
                 name="leftover_quantity" 
                 min="1" 
                 required 
                 placeholder="e.g., 10" />
          <small class="text-muted">Number of items available</small>
        </div>
      </div>
      
      <div class="form__row">
        <div class="form__group">
          <label for="start_time">Start Time *</label>
          <input type="time" 
                 id="start_time" 
                 name="start_time" 
                 required 
                 value="<?= date('H:i') ?>" />
          <small class="text-muted">When discount starts</small>
        </div>
        
        <div class="form__group">
          <label for="end_time">End Time *</label>
          <input type="time" 
                 id="end_time" 
                 name="end_time" 
                 required 
                 value="23:59" />
          <small class="text-muted">When discount ends</small>
        </div>
      </div>
      
      <div class="flex gap-md" style="margin-top: 2rem; padding-top: 2rem; border-top: 1px solid var(--border-light);">
        <button class="btn btn--primary" type="submit">
          <span>✅</span> Create & Activate Discount
        </button>
        <a class="btn btn--outline" href="index.php?r=discounts/index">
          <span>❌</span> Cancel
        </a>
      </div>
    </form>
  </div>
  
  <div class="card">
    <div class="card__header">
      <div>
        <h3 class="card__title">Price Preview</h3>
        <p class="card__subtitle">Live discount calculation</p>
      </div>
      <span style="font-size: 2rem;">🏷️</span>
    </div>
    
    <div id="price-preview">
      <div class="kpi">
        <div class="kpi__label">Original Price</div>
        <div class="kpi__value" id="original-price">Rs.0.00</div>
      </div>
      <div class="kpi">
        <div class="kpi__label">Discount Amount</div>
        <div class="kpi__value text-warning" id="discount-amount">Rs.0.00</div>
      </div>
      <div class="kpi">
        <div class="kpi__label">Final Price</div>
        <div class="kpi__value text-success" id="final-price">Rs.0.00</div>
      </div>
      <div class="kpi">
        <div class="kpi__label">Customer Saves</div>
        <div class="kpi__value text-primary" id="savings-amount">Rs.0.00</div>
      </div>
    </div>
    
    <div style="margin-top: 2rem; padding: 1rem; background: rgba(99, 102, 241, 0.1); border-radius: 0.5rem; border-left: 3px solid var(--primary);">
      <h4 style="margin-bottom: 0.5rem;">💡 Discount Tips</h4>
      <ul style="margin: 0; padding-left: 1.5rem; font-size: 0.875rem; color: var(--text-secondary);">
        <li>15-25% for afternoon leftovers</li>
        <li>30-40% for evening leftovers</li>
        <li>50%+ for items near closing time</li>
        <li>Higher discounts = faster sales</li>
      </ul>
    </div>
  </div>
</div>

<script>
function updatePricePreview() {
  const foodSelect = document.getElementById('food_item_id');
  const discountInput = document.getElementById('discount_percentage');
  const leftoverInput = document.getElementById('leftover_quantity');
  
  const selectedOption = foodSelect.options[foodSelect.selectedIndex];
  const originalPrice = parseFloat(selectedOption.getAttribute('data-price')) || 0;
  const leftover = parseInt(selectedOption.getAttribute('data-leftover')) || 0;
  const discountPercent = parseFloat(discountInput.value) || 0;
  
  // Auto-fill leftover quantity if available
  if (leftover > 0 && !leftoverInput.value) {
    leftoverInput.value = leftover;
  }
  
  const discountAmount = originalPrice * (discountPercent / 100);
  const finalPrice = originalPrice - discountAmount;
  
  document.getElementById('original-price').textContent = 'Rs.' + originalPrice.toFixed(2);
  document.getElementById('discount-amount').textContent = 'Rs.' + discountAmount.toFixed(2);
  document.getElementById('final-price').textContent = 'Rs.' + finalPrice.toFixed(2);
  document.getElementById('savings-amount').textContent = 'Rs.' + discountAmount.toFixed(2);
}

// Initialize preview
document.addEventListener('DOMContentLoaded', function() {
  updatePricePreview();
});
</script>
