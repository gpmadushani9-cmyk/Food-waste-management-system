<h1>Purchase recommendations (ingredients)</h1>

<div class="card">
  <form method="get" class="row row--between">
    <input type="hidden" name="r" value="reports/recommendations">
    <div class="row">
      <label>Lookback days</label>
      <input type="number" name="days" min="3" max="60" value="<?= (int)$days ?>">
      <label>Horizon (days)</label>
      <input type="number" name="horizon" min="1" max="30" value="<?= (int)$horizon ?>">
      <button class="btn btn--ghost" type="submit">Update</button>
    </div>
    <div class="muted">
      Needs are calculated from recipes × forecasted menu-item sales.
    </div>
  </form>
</div>

<div class="card">
  <div class="table-wrap">
    <table class="table">
      <thead>
        <tr>
          <th>Ingredient</th>
          <th>Current stock</th>
          <th>Forecast need</th>
          <th>Reorder level</th>
          <th>Suggested buy</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($rows as $r): ?>
          <?php $buy = (float)$r['suggested_buy']; ?>
          <tr>
            <td><?= htmlspecialchars($r['ingredient']) ?></td>
            <td><?= number_format((float)$r['current_stock'], 2) ?> <?= htmlspecialchars($r['unit']) ?></td>
            <td><?= number_format((float)$r['forecast_need'], 2) ?> <?= htmlspecialchars($r['unit']) ?></td>
            <td><?= number_format((float)$r['reorder_level'], 2) ?> <?= htmlspecialchars($r['unit']) ?></td>
            <td>
              <?php if ($buy > 0): ?>
                <span class="pill pill--danger"><?= number_format($buy, 2) ?> <?= htmlspecialchars($r['unit']) ?></span>
              <?php else: ?>
                <span class="pill pill--ok">0</span>
              <?php endif; ?>
            </td>
          </tr>
        <?php endforeach; ?>
        <?php if (empty($rows)): ?>
          <tr><td colspan="5" class="muted">No ingredients found.</td></tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>

  <p class="muted">
    If Suggested buy is always 0, make sure you: (1) added recipes, (2) logged sales, (3) logged inventory.
  </p>
</div>
