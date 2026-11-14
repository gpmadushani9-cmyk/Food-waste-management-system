<div class="flex flex--between flex--center mb-lg">
  <div>
    <h1>Food Items Management</h1>
    <p class="text-muted mb-0">Manage your restaurant's food products and pricing</p>
  </div>
  <div class="flex gap-sm">
    <a class="btn btn--outline" href="index.php?r=production/index">
      <span>📊</span> Production
    </a>
    <a class="btn btn--primary" href="index.php?r=food_items/create">
      <span>➕</span> Add Food Item
    </a>
  </div>
</div>

<div class="grid grid--4 mb-lg">
  <div class="kpi">
    <div class="kpi__label">Total Items</div>
    <div class="kpi__value"><?= count($items) ?></div>
  </div>
  <div class="kpi">
    <div class="kpi__label">Avg Selling Price</div>
    <div class="kpi__value">Rs.<?= count($items) > 0 ? number_format(array_sum(array_column($items, 'selling_price')) / count($items), 2) : '0.00' ?></div>
  </div>
  <div class="kpi">
    <div class="kpi__label">Profit Margin</div>
    <div class="kpi__value"><?= count($items) > 0 ? number_format((1 - array_sum(array_column($items, 'cost_price')) / array_sum(array_column($items, 'selling_price'))) * 100, 1) : 0 ?>%</div>
  </div>
  <div class="kpi">
    <div class="kpi__label">Categories</div>
    <div class="kpi__value"><?= count(array_unique(array_column($items, 'category'))) ?></div>
  </div>
</div>

<div class="card">
  <div class="card__header">
    <div>
      <h3 class="card__title">All Food Items</h3>
      <p class="card__subtitle">Manage your restaurant's food products</p>
    </div>
  </div>
  
  <div class="table-container">
    <table class="table">
      <thead>
        <tr>
          <th>Food Item</th>
          <th>Category</th>
          <th>Cost Price</th>
          <th>Selling Price</th>
          <th>Profit</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($items as $item): 
          $profit = $item['selling_price'] - $item['cost_price'];
          $profit_margin = $item['selling_price'] > 0 ? ($profit / $item['selling_price']) * 100 : 0;
        ?>
          <tr>
            <td>
              <div class="flex flex--center gap-sm">
                <span>🍽️</span>
                <div>
                  <strong><?= htmlspecialchars($item['name']) ?></strong>
                  <br><small class="text-muted">ID: #<?= $item['id'] ?></small>
                </div>
              </div>
            </td>
            <td>
              <span class="pill"><?= htmlspecialchars($item['category']) ?></span>
            </td>
            <td>
              <span class="font-mono">Rs.<?= number_format($item['cost_price'], 2) ?></span>
            </td>
            <td>
              <span class="font-mono text-lg">Rs.<?= number_format($item['selling_price'], 2) ?></span>
            </td>
            <td>
              <div>
                <span class="font-mono <?= $profit > 0 ? 'text-success' : 'text-danger' ?>">
                  Rs.<?= number_format($profit, 2) ?>
                </span>
                <br><small class="text-muted"><?= number_format($profit_margin, 1) ?>%</small>
              </div>
            </td>
            <td>
              <div class="table__actions">
                <a class="btn btn--sm btn--outline" href="index.php?r=food_items/edit&id=<?= $item['id'] ?>">
                  <span>✏️</span> Edit
                </a>
                <form method="post" action="index.php?r=food_items/delete" 
                      onsubmit="return confirm('Delete this food item?')" style="display: inline;">
                  <input type="hidden" name="csrf" value="<?= htmlspecialchars(CSRF::token()) ?>">
                  <input type="hidden" name="id" value="<?= $item['id'] ?>">
                  <button class="btn btn--sm btn--danger" type="submit">
                    <span>🗑️</span> Delete
                  </button>
                </form>
              </div>
            </td>
          </tr>
        <?php endforeach; ?>
        <?php if (empty($items)): ?>
          <tr>
            <td colspan="6" class="text-center" style="padding: 3rem;">
              <div style="font-size: 3rem; margin-bottom: 1rem;">🍽️</div>
              <h4>No food items found</h4>
              <p class="text-muted mb-lg">Start by adding your first food item</p>
              <a href="index.php?r=food_items/create" class="btn btn--primary">
                <span>➕</span> Add Your First Food Item
              </a>
            </td>
          </tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>