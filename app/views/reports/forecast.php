<h1>Demand forecast (moving average)</h1>

<div class="card">
  <form method="get" class="row row--between">
    <input type="hidden" name="r" value="reports/forecast">
    <div class="row">
      <label>Lookback days</label>
      <input type="number" name="days" min="3" max="60" value="<?= (int)$days ?>">
      <label>Forecast horizon (days)</label>
      <input type="number" name="horizon" min="1" max="30" value="<?= (int)$horizon ?>">
      <button class="btn btn--ghost" type="submit">Update</button>
    </div>
    <div class="muted">Forecast is based on sales logs.</div>
  </form>
</div>

<div class="card">
  <canvas id="forecastChart" class="chart"
    data-labels='<?= htmlspecialchars(json_encode(array_map(fn($r) => $r["name"], array_slice($forecast, 0, 10)))) ?>'
    data-values='<?= htmlspecialchars(json_encode(array_map(fn($r) => (float)$r["forecast_qty"], array_slice($forecast, 0, 10)))) ?>'
  ></canvas>

  <div class="table-wrap">
    <table class="table">
      <thead><tr><th>Menu item</th><th>Avg/day</th><th>Forecast (<?= (int)$horizon ?> days)</th></tr></thead>
      <tbody>
        <?php foreach ($forecast as $r): ?>
          <tr>
            <td><?= htmlspecialchars($r['name']) ?></td>
            <td><?= number_format((float)$r['avg_per_day'], 2) ?></td>
            <td><strong><?= number_format((float)$r['forecast_qty'], 2) ?></strong></td>
          </tr>
        <?php endforeach; ?>
        <?php if (empty($forecast)): ?>
          <tr><td colspan="3" class="muted">No menu items yet.</td></tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>
