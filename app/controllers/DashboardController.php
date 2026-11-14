<?php
declare(strict_types=1);

final class DashboardController extends Controller {
  public function index(): void {
    Auth::requireLogin();

    $to = date('Y-m-d');
    $from = date('Y-m-d', strtotime('-6 days'));
    $today = date('Y-m-d');

    $stock = Inventory::stockByIngredient();
    $wasteTotals = Waste::totals($from, $to);
    $salesByDate = Sale::totalsByDate($from, $to);
    $salesTotal = 0.0;
    foreach ($salesByDate as $r) $salesTotal += (float)$r['revenue'];

    // Today's production stats
    $todayProduction = DB::query("
      SELECT SUM(quantity_produced) as total_produced, 
             SUM(quantity_sold) as total_sold, 
             SUM(quantity_leftover) as total_leftover
      FROM daily_production WHERE production_date = ?", [$today])[0] ?? ['total_produced' => 0, 'total_sold' => 0, 'total_leftover' => 0];

    // Active discounts count
    $activeDiscounts = DB::query("
      SELECT COUNT(*) as count FROM discounts 
      WHERE discount_date = ? AND status = 'active'", [$today])[0]['count'] ?? 0;

    // Waste percentage
    $wastePercentage = $todayProduction['total_produced'] > 0 
      ? ($todayProduction['total_leftover'] / $todayProduction['total_produced']) * 100 
      : 0;

    $this->render('dashboard/index', [
      'from' => $from,
      'to' => $to,
      'stock' => $stock,
      'wasteTotals' => $wasteTotals,
      'salesByDate' => $salesByDate,
      'salesTotal' => $salesTotal,
      'todayProduction' => $todayProduction,
      'activeDiscounts' => $activeDiscounts,
      'wastePercentage' => $wastePercentage,
    ]);
  }
}
