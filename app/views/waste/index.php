<h1>Waste logs</h1>

<div class="grid grid--2">
  <div class="card">
    <h2>Add waste record</h2>
    <form method="post" action="index.php?r=waste/add" class="form">
      <input type="hidden" name="csrf" value="<?= htmlspecialchars(CSRF::token()) ?>">

      <label>Date</label>
      <input type="date" name="waste_date" value="<?= htmlspecialchars(date('Y-m-d')) ?>" />

      <label>Waste type</label>
      <select name="waste_type">
        <option value="overproduction">Overproduction</option>
        <option value="spoilage">Spoilage</option>
        <option value="plate_waste">Plate waste</option>
        <option value="prep_error">Preparation error</option>
        <option value="other">Other</option>
      </select>

      <label>Record waste for</label>
      <div class="row">
        <select name="ingredient_id">
          <option value="">— Ingredient (optional) —</option>
          <?php foreach ($ingredients as $i): ?>
            <option value="<?= (int)$i['id'] ?>"><?= htmlspecialchars($i['name']) ?> (<?= htmlspecialchars($i['unit']) ?>)</option>
          <?php endforeach; ?>
        </select>

        <select name="menu_item_id">
          <option value="">— Prepared food / Menu item (optional) —</option>
          <?php foreach ($menu as $m): ?>
            <option value="<?= (int)$m['id'] ?>"><?= htmlspecialchars($m['name']) ?></option>
          <?php endforeach; ?>
        </select>
      </div>

      <label>Quantity wasted</label>
      <input type="number" step="0.01" name="quantity" required />

      <label>Note (optional)</label>
      <input type="text" name="note" placeholder="Reason / shift / supplier issue..." />

      <button class="btn" type="submit">Add</button>
    </form>

    <p class="muted">
      Cost is auto-calculated using ingredient unit cost or recipe cost (if you set recipes).
      If you choose both ingredient + menu item, ingredient will be used.
    </p>
  </div>

  <div class="card">
    <h2>Recent waste records</h2>
    <div class="table-wrap">
      <table class="table">
        <thead><tr><th>Date</th><th>Item</th><th>Type</th><th>Qty</th><th>Cost</th><th>Note</th></tr></thead>
        <tbody>
          <?php foreach ($recent as $r): ?>
            <tr>
              <td><?= htmlspecialchars($r['waste_date']) ?></td>
              <td>
                <?= htmlspecialchars($r['ingredient_name'] ?: $r['menu_item_name'] ?: '—') ?>
              </td>
              <td><span class="pill"><?= htmlspecialchars($r['waste_type']) ?></span></td>
              <td><?= number_format((float)$r['quantity'], 2) ?></td>
              <td><?= number_format((float)$r['cost'], 2) ?></td>
              <td><?= htmlspecialchars($r['note'] ?? '') ?></td>
            </tr>
          <?php endforeach; ?>
          <?php if (empty($recent)): ?>
            <tr><td colspan="6" class="muted">No waste logged yet.</td></tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>
