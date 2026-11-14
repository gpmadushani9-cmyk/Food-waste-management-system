<?php
declare(strict_types=1);

final class ReportsController extends Controller {
  public function index(): void {
    Auth::requireLogin();
    $to = (string)($this->get('to') ?: date('Y-m-d'));
    $from = (string)($this->get('from') ?: date('Y-m-d', strtotime('-29 days')));

    $wasteByIng = Waste::totalsByIngredient($from, $to);
    $wasteByMenu = Waste::totalsByMenuItem($from, $to);
    $salesByMenu = Sale::totalsByMenuItem($from, $to);

    // gross profit by menu item using recipe cost (if recipe exists)
    $profitRows = [];
    foreach ($salesByMenu as $row) {
      // find menu id by name (safe enough for small app); better with id in query, but keep simple
      $stmt = DB::pdo()->prepare("SELECT id, price FROM menu_items WHERE name=? LIMIT 1");
      $stmt->execute([$row['name']]);
      $m = $stmt->fetch();
      $menuId = (int)($m['id'] ?? 0);
      $recipeCost = $menuId ? Recipe::recipeCostForMenuItem($menuId) : 0.0;
      $qty = (float)$row['qty'];
      $revenue = (float)$row['revenue'];
      $cogs = $qty * $recipeCost;
      $profitRows[] = [
        'name' => $row['name'],
        'qty' => $qty,
        'revenue' => $revenue,
        'cogs' => $cogs,
        'gross_profit' => $revenue - $cogs
      ];
    }

    usort($profitRows, fn($a,$b) => $b['gross_profit'] <=> $a['gross_profit']);

    $this->render('reports/index', [
      'from' => $from,
      'to' => $to,
      'wasteByIng' => $wasteByIng,
      'wasteByMenu' => $wasteByMenu,
      'profitRows' => $profitRows
    ]);
  }

  public function forecast(): void {
    Auth::requireLogin();
    $days = (int)($this->get('days') ?: 7);
    $horizon = (int)($this->get('horizon') ?: 7);
    $menu = MenuItem::all();

    $forecast = [];
    foreach ($menu as $m) {
      $avg = Sale::forecastMenuItem((int)$m['id'], $days);
      $forecast[] = [
        'menu_item_id' => (int)$m['id'],
        'name' => $m['name'],
        'avg_per_day' => $avg,
        'forecast_qty' => $avg * $horizon
      ];
    }

    usort($forecast, fn($a,$b) => $b['forecast_qty'] <=> $a['forecast_qty']);

    $this->render('reports/forecast', [
      'days' => $days,
      'horizon' => $horizon,
      'forecast' => $forecast
    ]);
  }

  public function recommendations(): void {
    Auth::requireLogin();
    $days = (int)($this->get('days') ?: 7);        // lookback for MA
    $horizon = (int)($this->get('horizon') ?: 7); // forecast period in days

    // forecast menu items
    $menu = MenuItem::all();
    $menuForecast = [];
    foreach ($menu as $m) {
      $avg = Sale::forecastMenuItem((int)$m['id'], $days);
      $menuForecast[(int)$m['id']] = $avg * $horizon; // total qty expected
    }

    // compute ingredient needs using recipes
    $need = []; // ingredient_id => needed qty
    $stmt = DB::pdo()->query("SELECT menu_item_id, ingredient_id, qty_per_item FROM recipes");
    foreach ($stmt->fetchAll() as $r) {
      $mid = (int)$r['menu_item_id'];
      $iid = (int)$r['ingredient_id'];
      $qtyPerItem = (float)$r['qty_per_item'];
      $forecastQty = (float)($menuForecast[$mid] ?? 0);
      $need[$iid] = ($need[$iid] ?? 0) + ($forecastQty * $qtyPerItem);
    }

    $stock = Inventory::stockByIngredient();
    $rows = [];
    foreach ($stock as $s) {
      $iid = (int)$s['id'];
      $current = (float)$s['stock'];
      $needed = (float)($need[$iid] ?? 0);
      $reorderLevel = (float)$s['reorder_level'];

      $target = max($reorderLevel, $needed);
      $toBuy = max(0.0, $target - $current);

      $rows[] = [
        'ingredient' => $s['name'],
        'unit' => $s['unit'],
        'current_stock' => $current,
        'forecast_need' => $needed,
        'reorder_level' => $reorderLevel,
        'suggested_buy' => $toBuy
      ];
    }

    // highlight biggest shortages first
    usort($rows, fn($a,$b) => $b['suggested_buy'] <=> $a['suggested_buy']);

    $this->render('reports/recommendations', [
      'days' => $days,
      'horizon' => $horizon,
      'rows' => $rows
    ]);
  }
}
