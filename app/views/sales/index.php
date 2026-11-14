<div class="flex flex--between flex--center mb-lg">
  <div>
    <h1>Sales</h1>
    <p class="text-muted mb-0">Track daily sales and revenue for each menu item</p>
  </div>
  <a href="index.php?r=dashboard/index" class="btn btn--outline">
    <span>←</span> Dashboard
  </a>
</div>

<!-- How It Works -->
<div class="card mb-lg" style="background: linear-gradient(135deg, rgba(255,107,107,0.1), rgba(238,90,36,0.1)); border-left: 4px solid var(--primary);">
  <h3 style="margin-bottom: 1rem;">📊 How Sales Tracking Works</h3>
  <div class="grid grid--3" style="gap: 1.5rem;">
    <div>
      <div style="font-size: 2rem; margin-bottom: 0.5rem;">1️⃣</div>
      <h4>Select Menu Item</h4>
      <p class="text-muted" style="font-size: 0.875rem;">Choose the food item you sold from the dropdown menu</p>
    </div>
    <div>
      <div style="font-size: 2rem; margin-bottom: 0.5rem;">2️⃣</div>
      <h4>Enter Quantity</h4>
      <p class="text-muted" style="font-size: 0.875rem;">Input how many units were sold on the selected date</p>
    </div>
    <div>
      <div style="font-size: 2rem; margin-bottom: 0.5rem;">3️⃣</div>
      <h4>Auto Calculate</h4>
      <p class="text-muted" style="font-size: 0.875rem;">Revenue is automatically calculated based on menu item price</p>
    </div>
  </div>
</div>

<div class="grid grid--2">
  <div class="card">
    <h2>Add daily sale</h2>
    <form method="post" action="index.php?r=sales/add" class="form">
      <input type="hidden" name="csrf" value="<?= htmlspecialchars(CSRF::token()) ?>">
      <label>Date</label>
      <input type="date" name="sale_date" value="<?= htmlspecialchars(date('Y-m-d')) ?>" />
      <label>Menu item</label>
      <select name="menu_item_id" required>
        <option value="" disabled selected>Select menu item</option>
        <?php foreach ($menu as $m): ?>
          <option value="<?= (int)$m['id'] ?>"><?= htmlspecialchars($m['name']) ?> (Rs.<?= number_format((float)$m['price'],2) ?>)</option>
        <?php endforeach; ?>
      </select>
      <label>Quantity sold</label>
      <input type="number" step="0.01" name="quantity" required />
      <button class="btn btn--primary" type="submit">
        <span>➕</span> Add Sale
      </button>
    </form>
    <div style="background: rgba(59,130,246,0.1); padding: 1rem; border-radius: 0.5rem; margin-top: 1rem; border-left: 3px solid #3b82f6;">
      <p style="margin: 0; font-size: 0.875rem; color: var(--text-secondary);">
        💡 <strong>Tip:</strong> Revenue is automatically calculated by multiplying quantity × menu item price
      </p>
    </div>
  </div>

  <div class="card">
    <h2>Recent sales</h2>
    <div class="table-wrap">
      <table class="table">
        <thead><tr><th>Date</th><th>Menu item</th><th>Qty</th><th>Revenue</th></tr></thead>
        <tbody>
          <?php foreach ($recent as $r): ?>
            <tr>
              <td><?= htmlspecialchars($r['sale_date']) ?></td>
              <td><?= htmlspecialchars($r['menu_item_name']) ?></td>
              <td><?= number_format((float)$r['quantity'], 2) ?></td>
              <td>Rs.<?= number_format((float)$r['revenue'], 2) ?></td>
            </tr>
          <?php endforeach; ?>
          <?php if (empty($recent)): ?>
            <tr><td colspan="4" class="muted">No sales logged yet.</td></tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>
