<div class="flex flex--between flex--center mb-lg">
  <div>
    <h1><?= $mode === 'create' ? 'Add New Food Item' : 'Edit Food Item' ?></h1>
    <p class="text-muted mb-0">Manage your restaurant's food products</p>
  </div>
  <a href="index.php?r=food_items/index" class="btn btn--outline">
    <span>←</span> Back to Food Items
  </a>
</div>

<div class="grid grid--2">
  <div class="card">
    <div class="card__header">
      <div>
        <h3 class="card__title">Food Item Details</h3>
        <p class="card__subtitle">Enter the product information</p>
      </div>
      <span style="font-size: 2rem;">🍽️</span>
    </div>

    <?php if (!empty($error)): ?>
      <div class="alert alert--danger">
        <strong>Error:</strong> <?= htmlspecialchars($error) ?>
      </div>
    <?php endif; ?>

    <form method="post" class="form">
      <input type="hidden" name="csrf" value="<?= htmlspecialchars(CSRF::token()) ?>">
      
      <div class="form__row">
        <div class="form__group">
          <label for="name">Food Item Name *</label>
          <input type="text" 
                 id="name" 
                 name="name" 
                 required 
                 placeholder="e.g., Chicken Roll, Pizza, Burger"
                 value="<?= htmlspecialchars($item['name'] ?? '') ?>" />
        </div>
        
        <div class="form__group">
          <label for="category">Category *</label>
          <select id="category" name="category" required>
            <option value="">Select category</option>
            <option value="Main Course" <?= ($item['category'] ?? '') === 'Main Course' ? 'selected' : '' ?>>Main Course</option>
            <option value="Appetizer" <?= ($item['category'] ?? '') === 'Appetizer' ? 'selected' : '' ?>>Appetizer</option>
            <option value="Dessert" <?= ($item['category'] ?? '') === 'Dessert' ? 'selected' : '' ?>>Dessert</option>
            <option value="Beverage" <?= ($item['category'] ?? '') === 'Beverage' ? 'selected' : '' ?>>Beverage</option>
            <option value="Snack" <?= ($item['category'] ?? '') === 'Snack' ? 'selected' : '' ?>>Snack</option>
          </select>
        </div>
      </div>
      
      <div class="form__row">
        <div class="form__group">
          <label for="cost_price">Cost Price (Rs.)</label>
          <input type="number" 
                 id="cost_price" 
                 name="cost_price" 
                 step="0.01" 
                 min="0"
                 placeholder="0.00"
                 value="<?= htmlspecialchars($item['cost_price'] ?? '0') ?>" />
          <small class="text-muted">Total cost to make this item</small>
        </div>
        
        <div class="form__group">
          <label for="selling_price">Selling Price (Rs.)</label>
          <input type="number" 
                 id="selling_price" 
                 name="selling_price" 
                 step="0.01" 
                 min="0"
                 placeholder="0.00"
                 value="<?= htmlspecialchars($item['selling_price'] ?? '0') ?>" />
          <small class="text-muted">Price customers pay</small>
        </div>
      </div>
      
      <div class="flex gap-md" style="margin-top: 2rem; padding-top: 2rem; border-top: 1px solid var(--border-light);">
        <button class="btn btn--primary" type="submit">
          <span><?= $mode === 'create' ? '➕' : '✓' ?></span> 
          <?= $mode === 'create' ? 'Add Food Item' : 'Update Food Item' ?>
        </button>
        <a class="btn btn--outline" href="index.php?r=food_items/index">
          <span>❌</span> Cancel
        </a>
      </div>
    </form>
  </div>
  
  <div class="card">
    <div class="card__header">
      <div>
        <h3 class="card__title">Profit Calculator</h3>
        <p class="card__subtitle">Live profit calculation</p>
      </div>
      <span style="font-size: 2rem;">💰</span>
    </div>
    
    <div id="profit-calculator">
      <div class="kpi">
        <div class="kpi__label">Profit per Item</div>
        <div class="kpi__value" id="profit-amount">Rs.0.00</div>
      </div>
      <div class="kpi">
        <div class="kpi__label">Profit Margin</div>
        <div class="kpi__value" id="profit-margin">0%</div>
      </div>
    </div>
    
    <div style="margin-top: 2rem;">
      <h4>Food Categories</h4>
      <div class="space-y-2">
        <div class="pill">🍖 Main Course</div>
        <div class="pill">🥗 Appetizer</div>
        <div class="pill">🍰 Dessert</div>
        <div class="pill">🥤 Beverage</div>
        <div class="pill">🍿 Snack</div>
      </div>
    </div>
  </div>
</div>

<script>
function updateProfitCalculator() {
  const costPrice = parseFloat(document.getElementById('cost_price').value) || 0;
  const sellingPrice = parseFloat(document.getElementById('selling_price').value) || 0;
  
  const profit = sellingPrice - costPrice;
  const margin = sellingPrice > 0 ? (profit / sellingPrice) * 100 : 0;
  
  document.getElementById('profit-amount').textContent = 'Rs.' + profit.toFixed(2);
  document.getElementById('profit-margin').textContent = margin.toFixed(1) + '%';
  
  // Color coding
  const profitElement = document.getElementById('profit-amount');
  const marginElement = document.getElementById('profit-margin');
  
  if (profit > 0) {
    profitElement.style.color = 'var(--success)';
    marginElement.style.color = 'var(--success)';
  } else {
    profitElement.style.color = 'var(--danger)';
    marginElement.style.color = 'var(--danger)';
  }
}

document.getElementById('cost_price').addEventListener('input', updateProfitCalculator);
document.getElementById('selling_price').addEventListener('input', updateProfitCalculator);

// Initial calculation
updateProfitCalculator();
</script>