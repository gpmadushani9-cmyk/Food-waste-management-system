<div class="flex flex--between flex--center mb-lg">
  <div>
    <h1><?= $mode === 'create' ? 'Add New Ingredient' : 'Edit Ingredient' ?></h1>
    <p class="text-muted mb-0"><?= $mode === 'create' ? 'Add a new ingredient to your inventory system' : 'Update ingredient details and pricing' ?></p>
  </div>
  <a href="index.php?r=ingredients/index" class="btn btn--outline">
    <span>←</span> Back to Ingredients
  </a>
</div>

<div class="grid grid--2">
  <div class="card">
    <div class="card__header">
      <div>
        <h3 class="card__title">Ingredient Details</h3>
        <p class="card__subtitle">Enter the basic information for this ingredient</p>
      </div>
      <span style="font-size: 2rem;">🥬</span>
    </div>

    <?php if (!empty($error)): ?>
      <div class="alert alert--danger">
        <strong>Error:</strong> <?= htmlspecialchars($error) ?>
      </div>
    <?php endif; ?>

    <form method="post" class="form">
      <input type="hidden" name="csrf" value="<?= htmlspecialchars(CSRF::token()) ?>">
      
      <div class="form__row">
        <div class="form__group">
          <label for="name">Ingredient Name *</label>
          <input type="text" 
                 id="name" 
                 name="name" 
                 required 
                 placeholder="e.g., Tomatoes, Chicken Breast, Olive Oil"
                 value="<?= htmlspecialchars($item['name'] ?? '') ?>" />
        </div>
        
        <div class="form__group">
          <label for="unit">Unit of Measurement *</label>
          <select id="unit" name="unit" required>
            <option value="">Select unit type</option>
            <option value="kg" <?= ($item['unit'] ?? '') === 'kg' ? 'selected' : '' ?>>Kilograms (kg)</option>
            <option value="g" <?= ($item['unit'] ?? '') === 'g' ? 'selected' : '' ?>>Grams (g)</option>
            <option value="L" <?= ($item['unit'] ?? '') === 'L' ? 'selected' : '' ?>>Liters (L)</option>
            <option value="ml" <?= ($item['unit'] ?? '') === 'ml' ? 'selected' : '' ?>>Milliliters (ml)</option>
            <option value="pcs" <?= ($item['unit'] ?? '') === 'pcs' ? 'selected' : '' ?>>Pieces (pcs)</option>
            <option value="lbs" <?= ($item['unit'] ?? '') === 'lbs' ? 'selected' : '' ?>>Pounds (lbs)</option>
            <option value="oz" <?= ($item['unit'] ?? '') === 'oz' ? 'selected' : '' ?>>Ounces (oz)</option>
          </select>
        </div>
      </div>
      
      <div class="form__row">
        <div class="form__group">
          <label for="cost_per_unit">Cost per Unit ($)</label>
          <input type="number" 
                 id="cost_per_unit" 
                 name="cost_per_unit" 
                 step="0.01" 
                 min="0"
                 placeholder="0.00"
                 value="<?= htmlspecialchars($item['cost_per_unit'] ?? '0') ?>" />
          <small class="text-muted">Enter the cost for one unit of this ingredient</small>
        </div>
        
        <div class="form__group">
          <label for="reorder_level">Reorder Level</label>
          <input type="number" 
                 id="reorder_level" 
                 name="reorder_level" 
                 step="0.01" 
                 min="0"
                 placeholder="0.00"
                 value="<?= htmlspecialchars($item['reorder_level'] ?? '0') ?>" />
          <small class="text-muted">Alert when stock falls below this level</small>
        </div>
      </div>
      
      <div class="flex gap-md" style="margin-top: 2rem; padding-top: 2rem; border-top: 1px solid var(--border-light);">
        <button class="btn btn--primary" type="submit">
          <span><?= $mode === 'create' ? '➕' : '✓' ?></span> 
          <?= $mode === 'create' ? 'Add Ingredient' : 'Update Ingredient' ?>
        </button>
        <a class="btn btn--outline" href="index.php?r=ingredients/index">
          <span>❌</span> Cancel
        </a>
      </div>
    </form>
  </div>
  
  <div class="card">
    <div class="card__header">
      <div>
        <h3 class="card__title">Tips & Guidelines</h3>
        <p class="card__subtitle">Best practices for ingredient management</p>
      </div>
      <span style="font-size: 2rem;">💡</span>
    </div>
    
    <div class="space-y-4">
      <div class="p-4 bg-light rounded">
        <h4 class="text-sm font-weight-600 mb-2">🏷️ Naming Convention</h4>
        <p class="text-muted text-sm mb-0">Use clear, descriptive names. Include brand or quality grade if relevant (e.g., "Premium Olive Oil", "Organic Tomatoes").</p>
      </div>
      
      <div class="p-4 bg-light rounded">
        <h4 class="text-sm font-weight-600 mb-2">📊 Cost Tracking</h4>
        <p class="text-muted text-sm mb-0">Update costs regularly to maintain accurate profit calculations. Consider seasonal price variations.</p>
      </div>
      
      <div class="p-4 bg-light rounded">
        <h4 class="text-sm font-weight-600 mb-2">⚠️ Reorder Levels</h4>
        <p class="text-muted text-sm mb-0">Set reorder levels based on lead time and usage patterns. Higher levels for critical ingredients.</p>
      </div>
      
      <div class="p-4 bg-light rounded">
        <h4 class="text-sm font-weight-600 mb-2">📦 Unit Consistency</h4>
        <p class="text-muted text-sm mb-0">Use consistent units across your inventory. Convert supplier units to your standard units for accuracy.</p>
      </div>
    </div>
    
    <?php if ($mode === 'edit' && !empty($item)): ?>
    <div style="margin-top: 2rem; padding-top: 2rem; border-top: 1px solid var(--border-light);">
      <h4 class="mb-md">Ingredient History</h4>
      <div class="text-sm text-muted">
        <p><strong>Created:</strong> <?= date('M j, Y g:i A', strtotime($item['created_at'] ?? 'now')) ?></p>
        <p><strong>Last Updated:</strong> <?= date('M j, Y g:i A', strtotime($item['updated_at'] ?? 'now')) ?></p>
        <p><strong>ID:</strong> #<?= $item['id'] ?? 'New' ?></p>
      </div>
    </div>
    <?php endif; ?>
  </div>
</div>
