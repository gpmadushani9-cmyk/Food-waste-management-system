<?php
declare(strict_types=1);

final class Waste {
  public static function add(array $data): void {
    $stmt = DB::pdo()->prepare("
      INSERT INTO waste_logs (waste_date, ingredient_id, food_item_id, waste_type, quantity, cost, note)
      VALUES (?, ?, ?, ?, ?, ?, ?)
    ");
    $stmt->execute([
      $data['waste_date'],
      $data['ingredient_id'] !== '' ? (int)$data['ingredient_id'] : null,
      $data['menu_item_id'] !== '' ? (int)$data['menu_item_id'] : null,
      $data['waste_type'],
      (float)$data['quantity'],
      (float)$data['cost'],
      $data['note'] ?? ''
    ]);
  }

  public static function recent(int $limit=50): array {
    $stmt = DB::pdo()->prepare("
      SELECT w.*, i.name AS ingredient_name, f.name AS menu_item_name
      FROM waste_logs w
      LEFT JOIN ingredients i ON i.id=w.ingredient_id
      LEFT JOIN food_items f ON f.id=w.food_item_id
      ORDER BY w.waste_date DESC, w.id DESC
      LIMIT ?
    ");
    $stmt->bindValue(1, $limit, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll();
  }

  public static function totalsByIngredient(string $from, string $to): array {
    $stmt = DB::pdo()->prepare("
      SELECT i.name, SUM(w.quantity) AS qty, SUM(w.cost) AS cost
      FROM waste_logs w
      JOIN ingredients i ON i.id=w.ingredient_id
      WHERE w.ingredient_id IS NOT NULL AND w.waste_date BETWEEN ? AND ?
      GROUP BY i.id
      ORDER BY cost DESC
    ");
    $stmt->execute([$from, $to]);
    return $stmt->fetchAll();
  }

  public static function totalsByMenuItem(string $from, string $to): array {
    $stmt = DB::pdo()->prepare("
      SELECT f.name, SUM(w.quantity) AS qty, SUM(w.cost) AS cost
      FROM waste_logs w
      JOIN food_items f ON f.id=w.food_item_id
      WHERE w.food_item_id IS NOT NULL AND w.waste_date BETWEEN ? AND ?
      GROUP BY f.id
      ORDER BY cost DESC
    ");
    $stmt->execute([$from, $to]);
    return $stmt->fetchAll();
  }

  public static function totals(string $from, string $to): array {
    $stmt = DB::pdo()->prepare("
      SELECT SUM(cost) AS waste_cost, SUM(quantity) AS waste_qty
      FROM waste_logs
      WHERE waste_date BETWEEN ? AND ?
    ");
    $stmt->execute([$from, $to]);
    $r = $stmt->fetch();
    return [
      'waste_cost' => (float)($r['waste_cost'] ?? 0),
      'waste_qty' => (float)($r['waste_qty'] ?? 0),
    ];
  }
}
