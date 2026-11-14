<div class="flex flex--between flex--center mb-lg">
  <div>
    <h1>Discount Management</h1>
    <p class="text-muted mb-0">Manage automatic discount suggestions and customer offers</p>
  </div>
  <div class="flex gap-sm">
    <a class="btn btn--primary" href="index.php?r=discounts/add">
      <span>➕</span> Create Custom Discount
    </a>
    <a class="btn btn--outline" href="index.php?r=production/index">
      <span>📊</span> Production
    </a>
    <a class="btn btn--outline" href="index.php?r=customer/index" target="_blank">
      <span>👥</span> Customer View
    </a>
  </div>
</div>

<!-- Discount Suggestions -->
<?php if (!empty($suggestions)): ?>
<div class="card mb-lg">
  <div class="card__header">
    <div>
      <h3 class="card__title">🤖 AI Discount Suggestions</h3>
      <p class="card__subtitle">Automatic suggestions based on leftovers and time</p>
    </div>
    <button onclick="refreshSuggestions()" class="btn btn--sm btn--outline">
      <span>🔄</span> Refresh
    </button>
  </div>
  
  <div class="grid grid--2">
    <?php foreach ($suggestions as $suggestion): ?>
      <div class="card" style="border-left: 4px solid var(--warning);">
        <div class="flex flex--between flex--center">
          <div>
            <h4><?= htmlspecialchars($suggestion['food_name']) ?></h4>
            <p class="text-muted">Leftover: <?= $suggestion['leftover_qty'] ?> items</p>
          </div>
          <div class="text-right">
            <div class="text-lg font-weight-600 text-warning"><?= $suggestion['suggested_discount'] ?>% OFF</div>
            <div class="text-sm text-muted">
              <strike>Rs.<?= number_format($suggestion['original_price'], 2) ?></strike>
              → Rs.<?= number_format($suggestion['discounted_price'], 2) ?>
            </div>
          </div>
        </div>
        
        <form method="post" action="index.php?r=discounts/create" class="mt-md">
          <input type="hidden" name="csrf" value="<?= htmlspecialchars(CSRF::token()) ?>">
          <input type="hidden" name="food_item_id" value="<?= $suggestion['food_item_id'] ?>">
          <input type="hidden" name="discount_percentage" value="<?= $suggestion['suggested_discount'] ?>">
          <input type="hidden" name="leftover_quantity" value="<?= $suggestion['leftover_qty'] ?>">
          <input type="hidden" name="start_time" value="<?= date('H:i:s') ?>">
          <input type="hidden" name="end_time" value="23:59:59">
          
          <div class="flex gap-sm">
            <button type="submit" class="btn btn--sm btn--primary">
              <span>✅</span> Accept & Activate
            </button>
            <button type="button" class="btn btn--sm btn--ghost" onclick="this.closest('.card').style.display='none'">
              <span>❌</span> Reject
            </button>
          </div>
        </form>
      </div>
    <?php endforeach; ?>
  </div>
</div>
<?php endif; ?>

<!-- Active Discounts -->
<div class="card">
  <div class="card__header">
    <div>
      <h3 class="card__title">Active Discounts</h3>
      <p class="card__subtitle">Current and recent discount offers</p>
    </div>
  </div>
  
  <div class="table-container">
    <table class="table">
      <thead>
        <tr>
          <th>Food Item</th>
          <th>Discount</th>
          <th>Time Period</th>
          <th>Leftover Qty</th>
          <th>Status</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($discounts as $discount): 
          $current_time = date('H:i:s');
          $is_active = $discount['status'] === 'active' && 
                      $discount['start_time'] <= $current_time && 
                      $discount['end_time'] >= $current_time;
        ?>
          <tr>
            <td>
              <div class="flex flex--center gap-sm">
                <span>🍽️</span>
                <div>
                  <strong><?= htmlspecialchars($discount['food_name']) ?></strong>
                  <br><small class="text-muted">Rs.<?= number_format($discount['selling_price'], 2) ?> original</small>
                </div>
              </div>
            </td>
            <td>
              <div class="text-center">
                <div class="text-lg font-weight-600 text-warning"><?= $discount['discount_percentage'] ?>% OFF</div>
                <div class="text-sm text-success">Rs.<?= number_format($discount['selling_price'] * (1 - $discount['discount_percentage']/100), 2) ?></div>
              </div>
            </td>
            <td>
              <div class="text-sm">
                <div><?= date('g:i A', strtotime($discount['start_time'])) ?> - <?= date('g:i A', strtotime($discount['end_time'])) ?></div>
                <div class="text-muted"><?= date('M j, Y', strtotime($discount['discount_date'])) ?></div>
              </div>
            </td>
            <td>
              <span class="font-mono"><?= $discount['leftover_quantity'] ?></span>
            </td>
            <td>
              <?php if ($is_active): ?>
                <span class="pill pill--success">🟢 Active</span>
              <?php elseif ($discount['status'] === 'expired'): ?>
                <span class="pill pill--info">⏰ Expired</span>
              <?php elseif ($discount['status'] === 'cancelled'): ?>
                <span class="pill pill--danger">❌ Cancelled</span>
              <?php else: ?>
                <span class="pill pill--warning">⏸️ Pending</span>
              <?php endif; ?>
            </td>
            <td>
              <div class="table__actions">
                <?php if ($discount['status'] === 'pending'): ?>
                  <form method="post" action="index.php?r=discounts/activate" style="display: inline;">
                    <input type="hidden" name="csrf" value="<?= htmlspecialchars(CSRF::token()) ?>">
                    <input type="hidden" name="id" value="<?= $discount['id'] ?>">
                    <button class="btn btn--sm btn--success" type="submit">
                      <span>✅</span> Activate
                    </button>
                  </form>
                <?php endif; ?>
                
                <?php if ($discount['status'] === 'active'): ?>
                  <form method="post" action="index.php?r=discounts/cancel" style="display: inline;">
                    <input type="hidden" name="csrf" value="<?= htmlspecialchars(CSRF::token()) ?>">
                    <input type="hidden" name="id" value="<?= $discount['id'] ?>">
                    <button class="btn btn--sm btn--danger" type="submit">
                      <span>❌</span> Cancel
                    </button>
                  </form>
                <?php endif; ?>
              </div>
            </td>
          </tr>
        <?php endforeach; ?>
        <?php if (empty($discounts)): ?>
          <tr>
            <td colspan="6" class="text-center" style="padding: 3rem;">
              <div style="font-size: 3rem; margin-bottom: 1rem;">💸</div>
              <h4>No discounts created yet</h4>
              <p class="text-muted">Discounts will appear here when you have leftovers</p>
            </td>
          </tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>

<script>
function refreshSuggestions() {
  fetch('index.php?r=discounts/suggestions')
    .then(response => response.json())
    .then(data => {
      if (data.length > 0) {
        location.reload();
      } else {
        alert('No new suggestions available');
      }
    });
}

// Auto-refresh suggestions every 5 minutes
setInterval(refreshSuggestions, 300000);
</script>