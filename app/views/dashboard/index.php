<?php
$labels = array_map(fn($r) => $r['sale_date'], $salesByDate);
$values = array_map(fn($r) => (float)$r['revenue'], $salesByDate);
?>

<div class="flex flex--between flex--center mb-lg">
  <div>
    <h1>Dashboard Overview</h1>
    <p class="text-muted mb-0">Monitor your restaurant's performance and key metrics</p>
  </div>
  <div class="flex gap-sm">
    <a href="index.php?r=production/index" class="btn btn--primary">
      <span>📊</span> Production
    </a>
    <a href="index.php?r=discounts/index" class="btn btn--outline">
      <span>💸</span> Discounts
    </a>
  </div>
</div>

<!-- KPI Cards -->
<div class="kpis">
  <div class="kpi">
    <div class="kpi__label">Today's Production</div>
    <div class="kpi__value"><?= number_format($todayProduction['total_produced']) ?></div>
    <div class="kpi__change kpi__change--info">Sold: <?= number_format($todayProduction['total_sold']) ?></div>
  </div>
  <div class="kpi">
    <div class="kpi__label">Today's Leftovers</div>
    <div class="kpi__value"><?= number_format($todayProduction['total_leftover']) ?></div>
    <div class="kpi__change <?= $wastePercentage > 20 ? 'kpi__change--negative' : 'kpi__change--positive' ?>">
      <?= number_format($wastePercentage, 1) ?>% waste
    </div>
  </div>
  <div class="kpi">
    <div class="kpi__label">Active Discounts</div>
    <div class="kpi__value"><?= $activeDiscounts ?></div>
    <div class="kpi__change kpi__change--info">Today's offers</div>
  </div>
  <div class="kpi">
    <div class="kpi__label">Sales (7 days)</div>
    <div class="kpi__value">Rs.<?= number_format($salesTotal, 2) ?></div>
    <div class="kpi__change kpi__change--positive">↑ Revenue</div>
  </div>
</div>

<div class="grid grid--2">
  <!-- Sales Chart -->
  <section class="card">
    <div class="card__header">
      <div>
        <h3 class="card__title">Sales Performance</h3>
        <p class="card__subtitle">Daily revenue trends over the last 7 days</p>
      </div>
      <div class="flex gap-sm">
        <button class="btn btn--sm btn--ghost">7D</button>
        <button class="btn btn--sm btn--outline">30D</button>
      </div>
    </div>
    
    <canvas id="salesChart" class="chart" 
            data-labels='<?= htmlspecialchars(json_encode($labels)) ?>' 
            data-values='<?= htmlspecialchars(json_encode($values)) ?>'></canvas>
    
    <div class="flex flex--between text-muted" style="font-size: 0.75rem; margin-top: 1rem;">
      <span>Period: <?= htmlspecialchars($from) ?> to <?= htmlspecialchars($to) ?></span>
      <span>Average: Rs.<?= count($values) > 0 ? number_format(array_sum($values) / count($values), 2) : '0.00' ?></span>
    </div>
  </section>

  <!-- Inventory Status -->
  <section class="card">
    <div class="card__header">
      <div>
        <h3 class="card__title">Inventory Status</h3>
        <p class="card__subtitle">Current stock levels and reorder alerts</p>
      </div>
      <a href="index.php?r=inventory/index" class="btn btn--sm btn--outline">Manage</a>
    </div>
    
    <div class="table-container">
      <table class="table">
        <thead>
          <tr>
            <th>Ingredient</th>
            <th>Current Stock</th>
            <th>Reorder Level</th>
            <th>Status</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($stock as $s): 
            $current = (float)$s['stock'];
            $reorder = (float)$s['reorder_level'];
            $low = $current < $reorder;
            $critical = $current < ($reorder * 0.5);
          ?>
            <tr>
              <td>
                <div class="flex flex--center gap-sm">
                  <span>🥬</span>
                  <strong><?= htmlspecialchars($s['name']) ?></strong>
                </div>
              </td>
              <td>
                <span class="font-mono"><?= number_format($current, 2) ?></span>
                <small class="text-muted"><?= htmlspecialchars($s['unit']) ?></small>
              </td>
              <td>
                <span class="font-mono"><?= number_format($reorder, 2) ?></span>
                <small class="text-muted"><?= htmlspecialchars($s['unit']) ?></small>
              </td>
              <td>
                <?php if ($critical): ?>
                  <span class="pill pill--danger">Critical</span>
                <?php elseif ($low): ?>
                  <span class="pill pill--warning">Low Stock</span>
                <?php else: ?>
                  <span class="pill pill--success">In Stock</span>
                <?php endif; ?>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
    
    <div class="flex flex--between text-muted" style="font-size: 0.75rem; margin-top: 1rem;">
      <span>Last updated: <?= date('M j, Y g:i A') ?></span>
      <a href="index.php?r=inventory/index" class="text-primary">View all inventory →</a>
    </div>
  </section>
</div>

<!-- Quick Actions -->
<div class="grid grid--4" style="margin-top: 2rem;">
  <div class="card text-center">
    <div style="font-size: 2rem; margin-bottom: 1rem;">🍽️</div>
    <h4>Food Items</h4>
    <p class="text-muted mb-lg">Manage products & pricing</p>
    <a href="index.php?r=food_items/index" class="btn btn--outline">Manage Items</a>
  </div>
  
  <div class="card text-center">
    <div style="font-size: 2rem; margin-bottom: 1rem;">📊</div>
    <h4>Production</h4>
    <p class="text-muted mb-lg">Track daily production</p>
    <a href="index.php?r=production/index" class="btn btn--outline">View Production</a>
  </div>
  
  <div class="card text-center">
    <div style="font-size: 2rem; margin-bottom: 1rem;">💸</div>
    <h4>Discounts</h4>
    <p class="text-muted mb-lg">Manage leftover offers</p>
    <a href="index.php?r=discounts/index" class="btn btn--outline">Manage Discounts</a>
  </div>
  
  <div class="card text-center">
    <div style="font-size: 2rem; margin-bottom: 1rem;">👥</div>
    <h4>Customer View</h4>
    <p class="text-muted mb-lg">Public discount page</p>
    <a href="index.php?r=customer/index" target="_blank" class="btn btn--outline">Open View</a>
  </div>
</div>
