<div class="flex flex--between flex--center mb-lg">
  <div>
    <h1>Daily Production</h1>
    <p class="text-muted mb-0">Track daily food production and sales</p>
  </div>
  <div class="flex gap-sm">
    <input type="date" value="<?= $date ?>" onchange="window.location.href='index.php?r=production/index&date='+this.value" class="btn btn--outline">
    <a class="btn btn--primary" href="index.php?r=discounts/index">
      <span>💸</span> Manage Discounts
    </a>
  </div>
</div>

<!-- Add Production Form -->
<div class="card mb-lg">
  <div class="card__header">
    <div>
      <h3 class="card__title">Add Production</h3>
      <p class="card__subtitle">Record today's food production</p>
    </div>
  </div>
  
  <form method="post" action="index.php?r=production/add" class="form">
    <input type="hidden" name="csrf" value="<?= htmlspecialchars(CSRF::token()) ?>">
    <input type="hidden" name="production_date" value="<?= $date ?>">
    
    <div class="form__row">
      <div class="form__group">
        <label for="food_item_id">Food Item</label>
        <select id="food_item_id" name="food_item_id" required>
          <option value="">Select food item</option>
          <?php foreach ($food_items as $item): ?>
            <option value="<?= $item['id'] ?>"><?= htmlspecialchars($item['name']) ?> (<?= htmlspecialchars($item['category']) ?>)</option>
          <?php endforeach; ?>
        </select>
      </div>
      
      <div class="form__group">
        <label for="quantity_produced">Quantity Produced</label>
        <input type="number" id="quantity_produced" name="quantity_produced" required min="1" placeholder="0">
      </div>
      
      <div class="form__group">
        <button class="btn btn--primary" type="submit">
          <span>➕</span> Add Production
        </button>
      </div>
    </div>
  </form>
</div>

<!-- Production List -->
<div class="card">
  <div class="card__header">
    <div>
      <h3 class="card__title">Production for <?= date('M j, Y', strtotime($date)) ?></h3>
      <p class="card__subtitle">Track production, sales, and leftovers</p>
    </div>
  </div>
  
  <div class="table-container">
    <table class="table">
      <thead>
        <tr>
          <th>Food Item</th>
          <th>Produced</th>
          <th>Sold</th>
          <th>Leftover</th>
          <th>Revenue</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($productions as $prod): 
          $leftover_percentage = $prod['quantity_produced'] > 0 ? ($prod['quantity_leftover'] / $prod['quantity_produced']) * 100 : 0;
          $revenue = $prod['quantity_sold'] * $prod['selling_price'];
        ?>
          <tr>
            <td>
              <div class="flex flex--center gap-sm">
                <span>🍽️</span>
                <div>
                  <strong><?= htmlspecialchars($prod['food_name']) ?></strong>
                  <br><small class="text-muted"><?= htmlspecialchars($prod['category']) ?></small>
                </div>
              </div>
            </td>
            <td>
              <span class="font-mono text-lg"><?= $prod['quantity_produced'] ?></span>
            </td>
            <td>
              <div style="display: flex; align-items: center; gap: 0.5rem;">
                <input type="number" 
                       id="sold_<?= $prod['id'] ?>" 
                       value="<?= $prod['quantity_sold'] ?>" 
                       min="0" 
                       max="<?= $prod['quantity_produced'] ?>" 
                       style="width: 80px;">
                <button onclick="updateSold(<?= $prod['id'] ?>)" 
                        class="btn btn--sm btn--primary" 
                        style="padding: 0.25rem 0.5rem;">
                  ✓
                </button>
              </div>
            </td>
            <td>
              <div>
                <span class="font-mono <?= $leftover_percentage > 30 ? 'text-danger' : ($leftover_percentage > 10 ? 'text-warning' : 'text-success') ?>">
                  <?= $prod['quantity_leftover'] ?>
                </span>
                <br><small class="text-muted"><?= number_format($leftover_percentage, 1) ?>%</small>
              </div>
            </td>
            <td>
              <span class="font-mono text-success">Rs.<?= number_format($revenue, 2) ?></span>
            </td>
            <td>
              <?php if ($prod['quantity_leftover'] > 0): ?>
                <form method="post" action="index.php?r=discounts/create" style="display: inline;">
                  <input type="hidden" name="csrf" value="<?= htmlspecialchars(CSRF::token()) ?>">
                  <input type="hidden" name="food_item_id" value="<?= $prod['food_item_id'] ?>">
                  <input type="hidden" name="discount_percentage" value="30">
                  <input type="hidden" name="start_time" value="<?= date('H:i:s') ?>">
                  <input type="hidden" name="end_time" value="23:59:59">
                  <input type="hidden" name="leftover_quantity" value="<?= $prod['quantity_leftover'] ?>">
                  <button type="submit" class="btn btn--sm btn--warning">
                    <span>💸</span> Create 30% Discount
                  </button>
                </form>
              <?php else: ?>
                <span class="pill pill--success">Sold Out</span>
              <?php endif; ?>
            </td>
          </tr>
        <?php endforeach; ?>
        <?php if (empty($productions)): ?>
          <tr>
            <td colspan="6" class="text-center" style="padding: 3rem;">
              <div style="font-size: 3rem; margin-bottom: 1rem;">📊</div>
              <h4>No production recorded</h4>
              <p class="text-muted">Start by adding today's production above</p>
            </td>
          </tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>

<script>
function updateSold(id) {
  const quantity = document.getElementById('sold_' + id).value;
  const form = document.createElement('form');
  form.method = 'POST';
  form.action = 'index.php?r=production/update_sales';
  
  const csrf = document.createElement('input');
  csrf.type = 'hidden';
  csrf.name = 'csrf';
  csrf.value = '<?= htmlspecialchars(CSRF::token()) ?>';
  
  const idInput = document.createElement('input');
  idInput.type = 'hidden';
  idInput.name = 'id';
  idInput.value = id;
  
  const qtyInput = document.createElement('input');
  qtyInput.type = 'hidden';
  qtyInput.name = 'quantity_sold';
  qtyInput.value = quantity;
  
  form.appendChild(csrf);
  form.appendChild(idInput);
  form.appendChild(qtyInput);
  document.body.appendChild(form);
  form.submit();
}
</script>