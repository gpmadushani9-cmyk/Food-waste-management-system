<?php
declare(strict_types=1);

final class Router {
  public function dispatch(): void {
    $route = $_GET['r'] ?? 'dashboard/index';
    $route = trim($route, '/');
    [$controller, $action] = array_pad(explode('/', $route, 2), 2, 'index');

    $map = [
      'auth' => AuthController::class,
      'dashboard' => DashboardController::class,
      'ingredients' => IngredientsController::class,
      'menu' => MenuController::class,
      'recipes' => RecipesController::class,
      'inventory' => InventoryController::class,
      'sales' => SalesController::class,
      'waste' => WasteController::class,
      'reports' => ReportsController::class,
      'food_items' => FoodItemsController::class,
      'production' => ProductionController::class,
      'discounts' => DiscountsController::class,
      'customer' => CustomerController::class,
      'orders' => OrdersController::class,
    ];

    if (!isset($map[$controller])) {
      http_response_code(404);
      echo "Route not found";
      exit;
    }

    $class = $map[$controller];
    $obj = new $class();

    if (!method_exists($obj, $action)) {
      http_response_code(404);
      echo "Action not found";
      exit;
    }

    $obj->$action();
  }
}
