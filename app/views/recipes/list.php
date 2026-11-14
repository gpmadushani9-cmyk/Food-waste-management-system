<h1>Recipes / Portion sizes</h1>

<div class="card">
  <form method="get" class="row row--between">
    <input type="hidden" name="r" value="recipes/index">
    <div class="row">
      <label class="muted">Select menu item:</label>
      <select name="menu_item_id" onchange="this.form.submit()">
        <?php foreach ($menu as $m): ?>
          <option value="<?= (int)$m['id'] ?>" <?= (int)$m['id'] === (int)$menuId ? 'selected' : '' ?>>
            <?= htmlspecialchars($m['name']) ?>
          </option>
        <?php endforeach; ?>
      </select>
    </div>
    <div class="muted">
      Recipe cost per item: <strong><?= number_format((float)$recipeCost, 2) ?></strong>
    </div>
  </form>
</div>

<div class="grid grid--2">
  <div class="card">
    <h2>Add / update ingredient</h2>
    <form method="post" action="index.php?r=recipes/upsert" class="form">
      <input type="hidden" name="csrf" value="<?= htmlspecialchars(CSRF::token()) ?>">
      <input type="hidden" name="menu_item_id" value="<?= (int)$menuId ?>">
      <label>Ingredient</label>
      <select name="ingredient_id" required>
        <?php foreach ($ingredients as $i): ?>
          <option value="<?= (int)$i['id'] ?>"><?= htmlspecialchars($i['name']) ?> (<?= htmlspecialchars($i['unit']) ?>)</option>
        <?php endforeach; ?>
      </select>
      <label>Qty per 1 item (in ingredient unit)</label>
      <input type="number" step="0.01" name="qty_per_item" required placeholder="e.g., 0.20 (kg)" />
      <button class="btn" type="submit">Save</button>
    </form>
    <p class="muted">This is used for profit calculation and ingredient demand forecasting.</p>
  </div>

  <div class="card">
    <h2>Current recipe</h2>
    <div class="table-wrap">
      <table class="table">
        <thead><tr><th>Ingredient</th><th>Qty/item</th><th>Unit</th><th>Unit cost</th><th></th></tr></thead>
        <tbody>
          <?php foreach ($rows as $r): ?>
            <tr>
              <td><?= htmlspecialchars($r['ingredient_name']) ?></td>
              <td><?= number_format((float)$r['qty_per_item'], 2) ?></td>
              <td><?= htmlspecialchars($r['unit']) ?></td>
              <td><?= number_format((float)$r['cost_per_unit'], 2) ?></td>
              <td class="td-actions">
                <form method="post" action="index.php?r=recipes/delete" onsubmit="return confirm('Remove from recipe?')">
                  <input type="hidden" name="csrf" value="<?= htmlspecialchars(CSRF::token()) ?>">
                  <input type="hidden" name="menu_item_id" value="<?= (int)$menuId ?>">
                  <input type="hidden" name="ingredient_id" value="<?= (int)$r['ingredient_id'] ?>">
                  <button class="btn btn--danger btn--ghost" type="submit">Remove</button>
                </form>
              </td>
            </tr>
          <?php endforeach; ?>
          <?php if (empty($rows)): ?>
            <tr><td colspan="5" class="muted">No recipe rows yet for this menu item.</td></tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>
