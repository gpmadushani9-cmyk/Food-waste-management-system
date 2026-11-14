<?php
$title = config('app_name', 'Food Waste Management');
$user = Auth::user();
$currentRoute = $_GET['r'] ?? 'dashboard/index';
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title><?= htmlspecialchars($title) ?></title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="assets/css/styles.css?v=5">
  <meta name="description" content="Professional food waste management and profit optimization system">
  <meta name="theme-color" content="#6366f1">
</head>
<body>
<?php if ($user): ?>
<div class="app-layout">
  <!-- Sidebar -->
  <aside class="sidebar">
    <div class="sidebar__header">
      <div class="brand"><?= htmlspecialchars($title) ?></div>
    </div>
    
    <nav class="sidebar__nav">
      <?php 
      $navItems = [
        ['route' => 'dashboard/index', 'label' => 'Dashboard', 'icon' => '📊'],
        ['route' => 'food_items/index', 'label' => 'Food Items', 'icon' => '🍽️'],
        ['route' => 'production/index', 'label' => 'Production', 'icon' => '📈'],
        ['route' => 'discounts/index', 'label' => 'Discounts', 'icon' => '💸'],
        ['route' => 'orders/index', 'label' => 'Orders', 'icon' => '📦'],
        ['route' => 'ingredients/index', 'label' => 'Ingredients', 'icon' => '🥬'],
        ['route' => 'inventory/index', 'label' => 'Inventory', 'icon' => '📦'],
        ['route' => 'sales/index', 'label' => 'Sales', 'icon' => '💰'],
        ['route' => 'waste/index', 'label' => 'Waste', 'icon' => '🗑️'],
        ['route' => 'reports/index', 'label' => 'Reports', 'icon' => '📋']
      ];
      
      foreach ($navItems as $navItem): 
        $isActive = strpos($currentRoute, explode('/', $navItem['route'])[0]) === 0;
      ?>
        <a href="index.php?r=<?= $navItem['route'] ?>" class="sidebar__link <?= $isActive ? 'active' : '' ?>">
          <span class="sidebar__icon"><?= $navItem['icon'] ?></span>
          <span class="sidebar__text"><?= $navItem['label'] ?></span>
        </a>
      <?php endforeach; ?>
    </nav>
    
    <div class="sidebar__footer">
      <div class="sidebar__user">
        <div class="sidebar__user-info">
          <span class="sidebar__user-name">👋 <?= htmlspecialchars($user['name']) ?></span>
          <span class="sidebar__user-role">Administrator</span>
        </div>
      </div>
      <a class="sidebar__logout" href="index.php?r=auth/logout">
        <span class="sidebar__icon">🚪</span>
        <span class="sidebar__text">Logout</span>
      </a>
    </div>
  </aside>
  
  <!-- Main Content -->
  <div class="main-content">
    <header class="topbar">
      <div class="topbar__content">
        <!-- <button class="sidebar-toggle" onclick="toggleSidebar()">
          <span></span><span></span><span></span>
        </button> -->
        <div class="topbar__actions">
          <span class="topbar__time"><?= date('M j, Y g:i A') ?></span>
        </div>
      </div>
    </header>

    <main class="content animate-fade-in">
      <?php include $viewFile; ?>
    </main>
  </div>
</div>
<?php else: ?>
<!-- Auth pages without sidebar -->
<header class="topbar">
  <div class="container topbar__inner">
    <a class="brand" href="index.php?r=dashboard/index"><?= htmlspecialchars($title) ?></a>
    <nav class="nav">
      <a href="index.php?r=auth/login" class="btn btn--outline">Login</a>
      <a href="index.php?r=auth/register" class="btn btn--primary">Register</a>
    </nav>
  </div>
</header>
<main class="container content animate-fade-in">
  <?php include $viewFile; ?>
</main>
<?php endif; ?>

<footer class="footer">
  <div class="container">
    <small>&copy; 2024 Food Waste Management System • Built with PHP + MySQL • Professional Edition</small>
  </div>
</footer>

<script type="module" src="assets/js/app.js"></script>
<script>
  function toggleSidebar() {
    const sidebar = document.querySelector('.sidebar');
    const mainContent = document.querySelector('.main-content');
    
    if (window.innerWidth <= 768) {
      sidebar.classList.toggle('open');
    } else {
      sidebar.classList.toggle('collapsed');
      mainContent.classList.toggle('expanded');
    }
  }
  
  // Add loading states and smooth interactions
  document.addEventListener('DOMContentLoaded', function() {
    // Add loading state to forms
    const forms = document.querySelectorAll('form');
    forms.forEach(form => {
      form.addEventListener('submit', function() {
        const submitBtn = form.querySelector('button[type="submit"]');
        if (submitBtn) {
          submitBtn.innerHTML = '<span class="spinner"></span> Processing...';
          submitBtn.disabled = true;
        }
      });
    });
    
    // Add hover effects to cards
    const cards = document.querySelectorAll('.card');
    cards.forEach(card => {
      card.addEventListener('mouseenter', function() {
        this.style.transform = 'translateY(-2px)';
      });
      card.addEventListener('mouseleave', function() {
        this.style.transform = 'translateY(0)';
      });
    });
  });
</script>
</body>
</html>
