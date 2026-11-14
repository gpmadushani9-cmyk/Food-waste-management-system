<div class="flex flex--between flex--center mb-lg">
  <div>
    <h1>Ingredients Management</h1>
    <p class="text-muted mb-0">Manage your restaurant's ingredient inventory and costs</p>
  </div>
  <div class="flex gap-sm">
    <a class="btn btn--outline" href="index.php?r=inventory/index">
      <span>📦</span> View Inventory
    </a>
    <a class="btn btn--primary" href="index.php?r=ingredients/create">
      <span>➕</span> Add Ingredient
    </a>
  </div>
</div>

<!-- Stats Cards -->
<div class="grid grid--4 mb-lg">
  <div class="kpi">
    <div class="kpi__label">Total Ingredients</div>
    <div class="kpi__value"><?= count($items) ?></div>
  </div>
  <div class="kpi">
    <div class="kpi__label">Avg Cost/Unit</div>
    <div class="kpi__value">$<?= count($items) > 0 ? number_format(array_sum(array_column($items, 'cost_per_unit')) / count($items), 2) : '0.00' ?></div>
  </div>
  <div class="kpi">
    <div class="kpi__label">High Value Items</div>
    <div class="kpi__value"><?= count(array_filter($items, fn($i) => (float)$i['cost_per_unit'] > 10)) ?></div>
  </div>
  <div class="kpi">
    <div class="kpi__label">Categories</div>
    <div class="kpi__value"><?= count(array_unique(array_column($items, 'unit'))) ?></div>
  </div>
</div>

<div class="card">
  <div class="card__header">
    <div>
      <h3 class="card__title">All Ingredients</h3>
      <p class="card__subtitle">Manage ingredient details, costs, and reorder levels</p>
    </div>
    <div class="flex gap-sm">
      <input type="search" placeholder="Search ingredients..." class="form-control" style="width: 200px;">
      <select class="form-control" style="width: 120px;">
        <option>All Units</option>
        <?php foreach (array_unique(array_column($items, 'unit')) as $unit): ?>
          <option><?= htmlspecialchars($unit) ?></option>
        <?php endforeach; ?>
      </select>
    </div>
  </div>
  
  <div class="table-container">
    <table class="table">
      <thead>
        <tr>
          <th>Ingredient</th>
          <th>Unit Type</th>
          <th>Cost per Unit</th>
          <th>Reorder Level</th>
          <th>Status</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($items as $it): 
          $cost = (float)$it['cost_per_unit'];
          $reorder = (float)$it['reorder_level'];
        ?>
          <tr>
            <td>
              <div class="flex flex--center gap-sm">
                <span>🥬</span>
                <div>
                  <strong><?= htmlspecialchars($it['name']) ?></strong>
                  <br><small class="text-muted">ID: #<?= $it['id'] ?></small>
                </div>
              </div>
            </td>
            <td>
              <span class="pill"><?= htmlspecialchars($it['unit']) ?></span>
            </td>
            <td>
              <span class="font-mono text-lg">$<?= number_format($cost, 2) ?></span>
              <br><small class="text-muted">per <?= htmlspecialchars($it['unit']) ?></small>
            </td>
            <td>
              <span class="font-mono"><?= number_format($reorder, 2) ?></span>
              <br><small class="text-muted"><?= htmlspecialchars($it['unit']) ?></small>
            </td>
            <td>
              <?php if ($cost > 20): ?>
                <span class="pill pill--warning">High Cost</span>
              <?php elseif ($cost > 10): ?>
                <span class="pill pill--info">Medium Cost</span>
              <?php else: ?>
                <span class="pill pill--success">Low Cost</span>
              <?php endif; ?>
            </td>
            <td>
              <div class="table__actions">
                <a class="btn btn--sm btn--outline" href="index.php?r=ingredients/edit&id=<?= (int)$it['id'] ?>">
                  <span>✏️</span> Edit
                </a>
                <form method="post" action="index.php?r=ingredients/delete" 
                      onsubmit="return confirm('Are you sure you want to delete this ingredient?')" 
                      style="display: inline;">
                  <input type="hidden" name="csrf" value="<?= htmlspecialchars(CSRF::token()) ?>">
                  <input type="hidden" name="id" value="<?= (int)$it['id'] ?>">
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
              <div style="font-size: 3rem; margin-bottom: 1rem;">🥬</div>
              <h4>No ingredients found</h4>
              <p class="text-muted mb-lg">Start by adding your first ingredient to manage your inventory</p>
              <a href="index.php?r=ingredients/create" class="btn btn--primary">
                <span>➕</span> Add Your First Ingredient
              </a>
            </td>
          </tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
  
  <?php if (!empty($items)): ?>
  <div class="flex flex--between text-muted" style="font-size: 0.75rem; margin-top: 1rem; padding-top: 1rem; border-top: 1px solid var(--border-light);">
    <span>Showing <?= count($items) ?> ingredients</span>
    <span>Last updated: <?= date('M j, Y g:i A') ?></span>
  </div>
  <?php endif; ?>
</div>
