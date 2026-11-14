<div class="flex flex--between flex--center mb-lg">
  <div>
    <h1>Orders Management</h1>
    <p class="text-muted mb-0">View and manage customer orders</p>
  </div>
  <div class="flex gap-sm">
    <button onclick="location.reload()" class="btn btn--outline">
      <span>🔄</span> Refresh
    </button>
  </div>
</div>

<!-- Order Stats -->
<div class="grid grid--4 mb-lg">
  <?php
  $pending = count(array_filter($orders, fn($o) => $o['status'] === 'pending'));
  $confirmed = count(array_filter($orders, fn($o) => $o['status'] === 'confirmed'));
  $completed = count(array_filter($orders, fn($o) => $o['status'] === 'completed'));
  $total = count($orders);
  ?>
  <div class="kpi">
    <div class="kpi__label">Pending Orders</div>
    <div class="kpi__value"><?= $pending ?></div>
  </div>
  <div class="kpi">
    <div class="kpi__label">Confirmed</div>
    <div class="kpi__value"><?= $confirmed ?></div>
  </div>
  <div class="kpi">
    <div class="kpi__label">Completed</div>
    <div class="kpi__value"><?= $completed ?></div>
  </div>
  <div class="kpi">
    <div class="kpi__label">Total Orders</div>
    <div class="kpi__value"><?= $total ?></div>
  </div>
</div>

<!-- Orders Table -->
<div class="card">
  <div class="card__header">
    <div>
      <h3 class="card__title">All Orders</h3>
      <p class="card__subtitle">Recent customer orders</p>
    </div>
  </div>
  
  <div class="table-container">
    <table class="table">
      <thead>
        <tr>
          <th>Order #</th>
          <th>Customer</th>
          <th>Contact</th>
          <th>Items</th>
          <th>Total</th>
          <th>Date</th>
          <th>Status</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($orders as $order): ?>
          <tr>
            <td>
              <strong>#<?= str_pad($order['id'], 5, '0', STR_PAD_LEFT) ?></strong>
            </td>
            <td>
              <div>
                <strong><?= htmlspecialchars($order['customer_name']) ?></strong>
                <br><small class="text-muted"><?= htmlspecialchars($order['customer_email']) ?></small>
              </div>
            </td>
            <td>
              <span class="font-mono"><?= htmlspecialchars($order['customer_phone']) ?></span>
            </td>
            <td>
              <span class="pill"><?= $order['item_count'] ?> items</span>
            </td>
            <td>
              <strong>Rs.<?= number_format($order['total_amount'], 2) ?></strong>
            </td>
            <td>
              <div class="text-sm">
                <?= date('M j, Y', strtotime($order['order_date'])) ?>
                <br><small class="text-muted"><?= date('g:i A', strtotime($order['order_date'])) ?></small>
              </div>
            </td>
            <td>
              <?php
              $statusColors = [
                'pending' => 'warning',
                'confirmed' => 'info',
                'preparing' => 'primary',
                'ready' => 'success',
                'completed' => 'success',
                'cancelled' => 'danger'
              ];
              $color = $statusColors[$order['status']] ?? 'info';
              ?>
              <span class="pill pill--<?= $color ?>"><?= ucfirst($order['status']) ?></span>
            </td>
            <td>
              <div class="table__actions">
                <a href="index.php?r=orders/view&id=<?= $order['id'] ?>" class="btn btn--sm btn--outline">
                  <span>👁️</span> View
                </a>
                <?php if ($order['status'] === 'pending'): ?>
                  <form method="post" action="index.php?r=orders/updateStatus" style="display: inline;">
                    <input type="hidden" name="csrf" value="<?= htmlspecialchars(CSRF::token()) ?>">
                    <input type="hidden" name="id" value="<?= $order['id'] ?>">
                    <input type="hidden" name="status" value="confirmed">
                    <button class="btn btn--sm btn--success" type="submit">
                      <span>✅</span> Confirm
                    </button>
                  </form>
                <?php endif; ?>
              </div>
            </td>
          </tr>
        <?php endforeach; ?>
        <?php if (empty($orders)): ?>
          <tr>
            <td colspan="8" class="text-center" style="padding: 3rem;">
              <div style="font-size: 3rem; margin-bottom: 1rem;">📦</div>
              <h4>No orders yet</h4>
              <p class="text-muted">Orders will appear here when customers place them</p>
            </td>
          </tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>

<script>
setInterval(() => location.reload(), 30000);
</script>
