<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>Today's Special Offers - Food Discounts</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="assets/css/styles.css">
  <style>
    body { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); min-height: 100vh; }
    .customer-nav {
      background: linear-gradient(135deg, #ff6b6b, #ee5a24, #ff8c42);
      backdrop-filter: blur(10px);
      padding: 1rem 0;
      margin-bottom: 2rem;
      border-radius: 1rem;
      box-shadow: 0 20px 25px -5px rgb(0 0 0 / 0.1);
    }
    .customer-nav ul {
      display: flex;
      gap: 1rem;
      list-style: none;
      margin: 0;
      padding: 0;
      justify-content: center;
    }
    .customer-nav a {
      padding: 0.75rem 1.5rem;
      border-radius: 0.5rem;
      font-weight: 600;
      transition: all 0.3s;
      color: white;
    }
    .customer-nav a.active {
      background: rgba(255,255,255,0.3);
      color: white;
    }
    .customer-nav a:not(.active) {
      color: rgba(255,255,255,0.8);
    }
    .customer-nav a:not(.active):hover {
      background: rgba(255,255,255,0.2);
      text-decoration: none;
      color: white;
    }
    .notification-bell {
      position: fixed;
      top: 2rem;
      right: 2rem;
      background: white;
      width: 50px;
      height: 50px;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 1.5rem;
      cursor: pointer;
      box-shadow: 0 4px 6px rgba(0,0,0,0.1);
      z-index: 1000;
    }
    .notification-popup {
      position: fixed;
      top: 5rem;
      right: 2rem;
      background: white;
      border-radius: 1rem;
      box-shadow: 0 20px 25px -5px rgb(0 0 0 / 0.3);
      width: 350px;
      max-height: 400px;
      overflow-y: auto;
      display: none;
      z-index: 999;
    }
    .notification-popup.show { display: block; }
  </style>
</head>
<body>
<div class="container" style="max-width: 1200px; padding: 2rem;">
  <!-- Navigation -->
  <div class="customer-nav">
    <ul>
      <li><a href="index.php?r=customer/index" class="<?= !isset($_GET['page']) || $_GET['page'] === 'menu' ? 'active' : '' ?>">🏠 HOME</a></li>
      <li><a href="index.php?r=customer/index&page=discounts" class="<?= isset($_GET['page']) && $_GET['page'] === 'discounts' ? 'active' : '' ?>">🔥 Special Discounts</a></li>
      <li><a href="index.php?r=customer/index&page=cart" class="<?= isset($_GET['page']) && $_GET['page'] === 'cart' ? 'active' : '' ?>" id="cart-nav">🛒 Cart <span id="cart-count" style="background: #ff6b6b; color: white; border-radius: 50%; padding: 0.2rem 0.5rem; font-size: 0.8rem; margin-left: 0.5rem; display: none;">0</span></a></li>
    </ul>
  </div>

  <!-- Notification Bell -->
  <div class="notification-bell" onclick="toggleNotifications()">
    🔔
    <?php if (!empty($notifications)): ?>
      <span style="position: absolute; top: 5px; right: 5px; background: red; color: white; border-radius: 50%; width: 20px; height: 20px; font-size: 0.75rem; display: flex; align-items: center; justify-content: center;">
        <?= count($notifications) ?>
      </span>
    <?php endif; ?>
  </div>

  <!-- Notification Popup -->
  <div class="notification-popup" id="notificationPopup">
    <div style="padding: 1.5rem; border-bottom: 1px solid var(--border-light); background: linear-gradient(135deg, #ff6b6b, #ee5a24);">
      <h3 style="margin: 0; color: white;">📢 Latest Updates</h3>
    </div>
    <?php if (!empty($notifications)): ?>
      <?php foreach ($notifications as $notification): ?>
        <div style="padding: 1rem; border-bottom: 1px solid var(--border-light);">
          <h4 style="margin: 0 0 0.5rem 0; font-size: 0.875rem; color: #ff6b6b; font-weight: 700;"><?= htmlspecialchars($notification['title']) ?></h4>
          <p style="margin: 0; font-size: 0.75rem; color: var(--text-muted);"><?= htmlspecialchars($notification['message']) ?></p>
          <small style="color: var(--text-light);"><?= date('g:i A', strtotime($notification['created_at'])) ?></small>
        </div>
      <?php endforeach; ?>
    <?php else: ?>
      <div style="padding: 2rem; text-align: center; color: var(--text-muted);">
        No notifications
      </div>
    <?php endif; ?>
  </div>

  <?php include __DIR__ . '/../' . $viewFile . '.php'; ?>
  
  <div style="text-align: center; margin-top: 3rem; color: rgba(255,255,255,0.8);">
    <p>🔔 Subscribe to never miss a discount!</p>
    <p style="font-size: 0.875rem;">Visit our restaurant for the freshest food experience</p>
  </div>
</div>

<script>
function toggleNotifications() {
  document.getElementById('notificationPopup').classList.toggle('show');
}

document.addEventListener('click', function(e) {
  const popup = document.getElementById('notificationPopup');
  const bell = document.querySelector('.notification-bell');
  if (!popup.contains(e.target) && !bell.contains(e.target)) {
    popup.classList.remove('show');
  }
});

setInterval(() => {
  const currentPage = new URLSearchParams(window.location.search).get('page');
  if (currentPage !== 'cart') {
    location.reload();
  }
}, 30000);
</script>
<script src="assets/js/cart.js"></script>
</body>
</html>