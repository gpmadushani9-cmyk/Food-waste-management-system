<?php
declare(strict_types=1);

session_start();

$configPath = __DIR__ . '/config/config.php';
if (!file_exists($configPath)) {
  $configPath = __DIR__ . '/config/config.example.php';
}
$GLOBALS['config'] = require $configPath;

function config(string $key, $default = null) {
  $parts = explode('.', $key);
  $value = $GLOBALS['config'];
  foreach ($parts as $p) {
    if (!is_array($value) || !array_key_exists($p, $value)) return $default;
    $value = $value[$p];
  }
  return $value;
}

require __DIR__ . '/core/DB.php';
require __DIR__ . '/core/Auth.php';
require __DIR__ . '/core/CSRF.php';
require __DIR__ . '/core/Router.php';
require __DIR__ . '/core/Controller.php';

require __DIR__ . '/models/User.php';
require __DIR__ . '/models/Ingredient.php';
require __DIR__ . '/models/MenuItem.php';
require __DIR__ . '/models/Recipe.php';
require __DIR__ . '/models/Inventory.php';
require __DIR__ . '/models/Sale.php';
require __DIR__ . '/models/Waste.php';

require __DIR__ . '/controllers/AuthController.php';
require __DIR__ . '/controllers/DashboardController.php';
require __DIR__ . '/controllers/IngredientsController.php';
require __DIR__ . '/controllers/MenuController.php';
require __DIR__ . '/controllers/RecipesController.php';
require __DIR__ . '/controllers/InventoryController.php';
require __DIR__ . '/controllers/SalesController.php';
require __DIR__ . '/controllers/WasteController.php';
require __DIR__ . '/controllers/ReportsController.php';
require __DIR__ . '/controllers/FoodItemsController.php';
require __DIR__ . '/controllers/ProductionController.php';
require __DIR__ . '/controllers/DiscountsController.php';
require __DIR__ . '/controllers/CustomerController.php';
require __DIR__ . '/controllers/OrdersController.php';
