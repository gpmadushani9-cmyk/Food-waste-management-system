<h1>Reports</h1>

<div class="card">
  <form method="get" class="row row--between">
    <input type="hidden" name="r" value="reports/index">
    <div class="row">
      <label>From</label>
      <input type="date" name="from" value="<?= htmlspecialchars($from) ?>">
      <label>To</label>
      <input type="date" name="to" value="<?= htmlspecialchars($to) ?>">
      <button class="btn btn--ghost" type="submit">Apply</button>
    </div>
    <div class="muted">Tip: Keep logging Sales + Waste for better insights.</div>
  </form>
</div>

<div class="grid grid--2">
  <div class="card">
    <h2>Waste by ingredient (top)</h2>
    <canvas id="wasteIngChart" class="chart"
      data-labels='<?= htmlspecialchars(json_encode(array_map(fn($r) => $r["name"], array_slice($wasteByIng, 0, 10)))) ?>'
      data-values='<?= htmlspecialchars(json_encode(array_map(fn($r) => (float)$r["cost"], array_slice($wasteByIng, 0, 10)))) ?>'
    ></canvas>

    <div class="table-wrap">
      <table class="table">
        <thead><tr><th>Ingredient</th><th>Qty</th><th>Cost</th></tr></thead>
        <tbody>
          <?php foreach ($wasteByIng as $r): ?>
            <tr>
              <td><?= htmlspecialchars($r['name']) ?></td>
              <td><?= number_format((float)$r['qty'], 2) ?></td>
              <td><?= number_format((float)$r['cost'], 2) ?></td>
            </tr>
          <?php endforeach; ?>
          <?php if (empty($wasteByIng)): ?>
            <tr><td colspan="3" class="muted">No ingredient waste logged in this period.</td></tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>

  <div class="card">
    <h2>Waste by menu item (prepared waste)</h2>
    <div class="table-wrap">
      <table class="table">
        <thead><tr><th>Menu item</th><th>Qty</th><th>Cost</th></tr></thead>
        <tbody>
          <?php foreach ($wasteByMenu as $r): ?>
            <tr>
              <td><?= htmlspecialchars($r['name']) ?></td>
              <td><?= number_format((float)$r['qty'], 2) ?></td>
              <td><?= number_format((float)$r['cost'], 2) ?></td>
            </tr>
          <?php endforeach; ?>
          <?php if (empty($wasteByMenu)): ?>
            <tr><td colspan="3" class="muted">No menu-item waste logged in this period.</td></tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
    <p class="muted">Log menu-item waste when whole prepared portions are discarded.</p>
  </div>
</div>

<div class="card">
  <h2>Gross profit estimate by menu item</h2>
  <p class="muted">Profit uses recipe-based cost (COGS). If you haven't set recipes, COGS will be 0.</p>
  <div class="table-wrap">
    <table class="table">
      <thead><tr><th>Menu item</th><th>Qty sold</th><th>Revenue</th><th>COGS (recipe)</th><th>Gross profit</th></tr></thead>
      <tbody>
        <?php foreach ($profitRows as $r): ?>
          <tr>
            <td><?= htmlspecialchars($r['name']) ?></td>
            <td><?= number_format((float)$r['qty'], 2) ?></td>
            <td><?= number_format((float)$r['revenue'], 2) ?></td>
            <td><?= number_format((float)$r['cogs'], 2) ?></td>
            <td><strong><?= number_format((float)$r['gross_profit'], 2) ?></strong></td>
          </tr>
        <?php endforeach; ?>
        <?php if (empty($profitRows)): ?>
          <tr><td colspan="5" class="muted">No sales in this period.</td></tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>
