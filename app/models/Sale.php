<?php
declare(strict_types=1);
// change food_item_id to menu_item_id
final class Sale {
  public static function add(array $data): void {
    $stmt = DB::pdo()->prepare("
      INSERT INTO sales (sale_date, food_item_id, quantity, revenue)
      VALUES (?, ?, ?, ?)
    ");
    $stmt->execute([
      $data['sale_date'],
      (int)$data['menu_item_id'],
      (float)$data['quantity'],
      (float)$data['revenue']
    ]);
  }

  public static function recent(int $limit = 50): array {
    $stmt = DB::pdo()->prepare("
      SELECT s.*, f.name AS menu_item_name
      FROM sales s
      JOIN food_items f ON f.id=s.food_item_id
      ORDER BY s.sale_date DESC, s.id DESC
      LIMIT ?
    ");
    $stmt->bindValue(1, $limit, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll();
  }

  public static function totalsByDate(string $from, string $to): array {
    $stmt = DB::pdo()->prepare("
      SELECT sale_date, SUM(revenue) AS revenue, SUM(quantity) AS qty
      FROM sales
      WHERE sale_date BETWEEN ? AND ?
      GROUP BY sale_date
      ORDER BY sale_date
    ");
    $stmt->execute([$from, $to]);
    return $stmt->fetchAll();
  }

  public static function totalsByMenuItem(string $from, string $to): array {
    $stmt = DB::pdo()->prepare("
      SELECT f.name, SUM(s.quantity) AS qty, SUM(s.revenue) AS revenue
      FROM sales s
      JOIN food_items f ON f.id=s.food_item_id
      WHERE s.sale_date BETWEEN ? AND ?
      GROUP BY f.id
      ORDER BY revenue DESC
    ");
    $stmt->execute([$from, $to]);
    return $stmt->fetchAll();
  }

  public static function forecastMenuItem(int $menuItemId, int $days = 7): float {
    // moving average of last N days (qty/day)
    $stmt = DB::pdo()->prepare("
      SELECT sale_date, SUM(quantity) AS qty
      FROM sales
      WHERE food_item_id=? AND sale_date >= DATE_SUB(CURDATE(), INTERVAL ? DAY)
      GROUP BY sale_date
      ORDER BY sale_date DESC
      LIMIT ?
    ");
    $stmt->execute([$menuItemId, $days, $days]);
    $rows = $stmt->fetchAll();
    if (!$rows) return 0.0;
    $sum = 0.0;
    foreach ($rows as $r) $sum += (float)$r['qty'];
    return $sum / max(1, count($rows));
  }
}
