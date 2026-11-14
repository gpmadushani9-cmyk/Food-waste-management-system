<div class="flex flex--between flex--center mb-lg">
  <div>
    <h1>Order #<?= str_pad($order['id'], 5, '0', STR_PAD_LEFT) ?></h1>
    <p class="text-muted mb-0">Order details and status</p>
  </div>
  <a href="index.php?r=orders/index" class="btn btn--outline">
    <span>←</span> Back to Orders
  </a>
</div>

<div class="grid grid--2">
  <!-- Customer Information -->
  <div class="card">
    <div class="card__header">
      <h3 class="card__title">Customer Information</h3>
    </div>
    <div style="padding: 1.5rem;">
      <div style="margin-bottom: 1rem;">
        <strong>Name:</strong><br>
        <?= htmlspecialchars($order['customer_name']) ?>
      </div>
      <div style="margin-bottom: 1rem;">
        <strong>Email:</strong><br>
        <?= htmlspecialchars($order['customer_email']) ?>
      </div>
      <div style="margin-bottom: 1rem;">
        <strong>Phone:</strong><br>
        <?= htmlspecialchars($order['customer_phone']) ?>
      </div>
      <div style="margin-bottom: 1rem;">
        <strong>Delivery Address:</strong><br>
        <?= nl2br(htmlspecialchars($order['customer_address'])) ?>
      </div>
      <?php if ($order['notes']): ?>
        <div>
          <strong>Notes:</strong><br>
          <?= nl2br(htmlspecialchars($order['notes'])) ?>
        </div>
      <?php endif; ?>
    </div>
  </div>

  <!-- Order Status -->
  <div class="card">
    <div class="card__header">
      <h3 class="card__title">Order Status</h3>
    </div>
    <div style="padding: 1.5rem;">
      <div style="margin-bottom: 1.5rem;">
        <strong>Current Status:</strong><br>
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
        <span class="pill pill--<?= $color ?>" style="font-size: 1.2rem; padding: 0.5rem 1rem; margin-top: 0.5rem; display: inline-block;">
          <?= ucfirst($order['status']) ?>
        </span>
      </div>
      
      <div style="margin-bottom: 1.5rem;">
        <strong>Order Date:</strong><br>
        <?= date('M j, Y g:i A', strtotime($order['order_date'])) ?>
      </div>
      
      <div style="margin-bottom: 1.5rem;">
        <strong>Update Status:</strong>
        <form method="post" action="index.php?r=orders/updateStatus">
          <input type="hidden" name="csrf" value="<?= htmlspecialchars(CSRF::token()) ?>">
          <input type="hidden" name="id" value="<?= $order['id'] ?>">
          <select name="status" class="form-control" style="margin: 0.5rem 0;">
            <option value="pending" <?= $order['status'] === 'pending' ? 'selected' : '' ?>>Pending</option>
            <option value="confirmed" <?= $order['status'] === 'confirmed' ? 'selected' : '' ?>>Confirmed</option>
            <option value="preparing" <?= $order['status'] === 'preparing' ? 'selected' : '' ?>>Preparing</option>
            <option value="ready" <?= $order['status'] === 'ready' ? 'selected' : '' ?>>Ready</option>
            <option value="completed" <?= $order['status'] === 'completed' ? 'selected' : '' ?>>Completed</option>
            <option value="cancelled" <?= $order['status'] === 'cancelled' ? 'selected' : '' ?>>Cancelled</option>
          </select>
          <button type="submit" class="btn btn--primary" style="width: 100%;">
            <span>✓</span> Update Status
          </button>
        </form>
      </div>
    </div>
  </div>
</div>

<!-- Order Items -->
<div class="card" style="margin-top: 2rem;">
  <div class="card__header">
    <h3 class="card__title">Order Items</h3>
  </div>
  
  <div class="table-container">
    <table class="table">
      <thead>
        <tr>
          <th>Item</th>
          <th>Price</th>
          <th>Discount</th>
          <th>Quantity</th>
          <th>Subtotal</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($items as $item): ?>
          <tr>
            <td>
              <strong><?= htmlspecialchars($item['food_name']) ?></strong>
            </td>
            <td>Rs.<?= number_format($item['price'], 2) ?></td>
            <td>
              <?php if ($item['discount_percentage'] > 0): ?>
                <span class="pill pill--warning"><?= $item['discount_percentage'] ?>% OFF</span>
              <?php else: ?>
                <span class="text-muted">-</span>
              <?php endif; ?>
            </td>
            <td><?= $item['quantity'] ?></td>
            <td><strong>Rs.<?= number_format($item['subtotal'], 2) ?></strong></td>
          </tr>
        <?php endforeach; ?>
        <tr style="background: rgba(99, 102, 241, 0.1); font-weight: bold;">
          <td colspan="4" style="text-align: right;">Total Amount:</td>
          <td><strong style="font-size: 1.2rem;">Rs.<?= number_format($order['total_amount'], 2) ?></strong></td>
        </tr>
      </tbody>
    </table>
  </div>
</div>
