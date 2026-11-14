<div class="card">
  <h1><?= $mode === 'create' ? 'Add Menu Item' : 'Edit Menu Item' ?></h1>

  <?php if (!empty($error)): ?>
    <div class="alert alert--danger"><?= htmlspecialchars($error) ?></div>
  <?php endif; ?>

  <form method="post" class="form">
    <input type="hidden" name="csrf" value="<?= htmlspecialchars(CSRF::token()) ?>">
    <label>Name</label>
    <input type="text" name="name" required value="<?= htmlspecialchars($item['name'] ?? '') ?>" />
    <label>Selling price</label>
    <input type="number" step="0.01" name="price" value="<?= htmlspecialchars($item['price'] ?? '0') ?>" />
    <label class="row row--center">
      <input type="checkbox" name="active" value="1" <?= !empty($item) ? ((int)$item['active'] ? 'checked' : '') : 'checked' ?> />
      <span>Active</span>
    </label>
    <div class="row">
      <button class="btn" type="submit">Save</button>
      <a class="btn btn--ghost" href="index.php?r=menu/index">Cancel</a>
    </div>
  </form>
</div>
