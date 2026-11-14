<h1>Inventory</h1>

<div class="grid grid--2">
  <div class="card">
    <h2>Stock movement</h2>
    <form method="post" action="index.php?r=inventory/add" class="form">
      <input type="hidden" name="csrf" value="<?= htmlspecialchars(CSRF::token()) ?>">
      <label>Ingredient</label>
      <select name="ingredient_id" required>
        <?php foreach ($ingredients as $i): ?>
          <option value="<?= (int)$i['id'] ?>"><?= htmlspecialchars($i['name']) ?> (<?= htmlspecialchars($i['unit']) ?>)</option>
        <?php endforeach; ?>
      </select>
      <label>Type</label>
      <select name="txn_type" required>
        <option value="IN">Stock In</option>
        <option value="OUT">Stock Out</option>
        <option value="ADJUST">Adjustment (+/-)</option>
      </select>
      <label>Quantity</label>
      <input type="number" step="0.01" name="quantity" required />
      <label>Date</label>
      <input type="date" name="txn_date" value="<?= htmlspecialchars(date('Y-m-d')) ?>" />
      <label>Note (optional)</label>
      <input type="text" name="note" placeholder="supplier / spoilage / correction..." />
      <button class="btn" type="submit">Add</button>
    </form>
  </div>

  <div class="card">
    <h2>Current stock</h2>
    <div class="table-wrap">
      <table class="table">
        <thead><tr><th>Ingredient</th><th>Stock</th><th>Unit</th><th>Reorder</th><th>Status</th></tr></thead>
        <tbody>
          <?php foreach ($stock as $s): 
            $low = ((float)$s['stock'] < (float)$s['reorder_level']);
          ?>
            <tr>
              <td><?= htmlspecialchars($s['name']) ?></td>
              <td><?= number_format((float)$s['stock'], 2) ?></td>
              <td><?= htmlspecialchars($s['unit']) ?></td>
              <td><?= number_format((float)$s['reorder_level'], 2) ?></td>
              <td><?= $low ? '<span class="pill pill--danger">Low</span>' : '<span class="pill pill--ok">OK</span>' ?></td>
            </tr>
          <?php endforeach; ?>
          <?php if (empty($stock)): ?>
            <tr><td colspan="5" class="muted">No ingredients yet.</td></tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

<div class="card">
  <h2>Recent movements</h2>
  <div class="table-wrap">
    <table class="table">
      <thead><tr><th>Date</th><th>Ingredient</th><th>Type</th><th>Qty</th><th>Unit</th><th>Note</th></tr></thead>
      <tbody>
        <?php foreach ($recent as $r): ?>
          <tr>
            <td><?= htmlspecialchars($r['txn_date']) ?></td>
            <td><?= htmlspecialchars($r['ingredient_name']) ?></td>
            <td><span class="pill"><?= htmlspecialchars($r['txn_type']) ?></span></td>
            <td><?= number_format((float)$r['quantity'], 2) ?></td>
            <td><?= htmlspecialchars($r['unit']) ?></td>
            <td><?= htmlspecialchars($r['note'] ?? '') ?></td>
          </tr>
        <?php endforeach; ?>
        <?php if (empty($recent)): ?>
          <tr><td colspan="6" class="muted">No inventory movements yet.</td></tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>
