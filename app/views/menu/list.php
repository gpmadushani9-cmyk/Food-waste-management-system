<div class="row row--between">
  <h1>Menu items</h1>
  <a class="btn" href="index.php?r=menu/create">+ Add Menu Item</a>
</div>

<div class="card">
  <div class="table-wrap">
    <table class="table">
      <thead>
        <tr><th>Name</th><th>Price</th><th>Status</th><th></th></tr>
      </thead>
      <tbody>
        <?php foreach ($items as $it): ?>
          <tr>
            <td><?= htmlspecialchars($it['name']) ?></td>
            <td><?= number_format((float)$it['price'], 2) ?></td>
            <td><?= (int)$it['active'] ? '<span class="pill pill--ok">Active</span>' : '<span class="pill">Inactive</span>' ?></td>
            <td class="td-actions">
              <a class="btn btn--ghost" href="index.php?r=menu/edit&id=<?= (int)$it['id'] ?>">Edit</a>
              <form method="post" action="index.php?r=menu/delete" onsubmit="return confirm('Delete menu item?')">
                <input type="hidden" name="csrf" value="<?= htmlspecialchars(CSRF::token()) ?>">
                <input type="hidden" name="id" value="<?= (int)$it['id'] ?>">
                <button class="btn btn--danger btn--ghost" type="submit">Delete</button>
              </form>
            </td>
          </tr>
        <?php endforeach; ?>
        <?php if (empty($items)): ?>
          <tr><td colspan="4" class="muted">No menu items yet.</td></tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
  <p class="muted">Tip: Add recipes (portion sizes) in the Recipes page for profit + forecasting.</p>
</div>
