<?php
declare(strict_types=1);

final class Recipe {
  public static function rowsForMenuItem(int $menuId): array {
    $stmt = DB::pdo()->prepare("
      SELECT r.*, i.name AS ingredient_name, i.unit, i.cost_per_unit
      FROM recipes r
      JOIN ingredients i ON i.id = r.ingredient_id
      WHERE r.menu_item_id = ?
      ORDER BY i.name
    ");
    $stmt->execute([$menuId]);
    return $stmt->fetchAll();
  }

  public static function upsert(int $menuId, int $ingredientId, float $qtyPerItem): void {
    $stmt = DB::pdo()->prepare("
      INSERT INTO recipes (menu_item_id, ingredient_id, qty_per_item)
      VALUES (?, ?, ?)
      ON DUPLICATE KEY UPDATE qty_per_item=VALUES(qty_per_item)
    ");
    $stmt->execute([$menuId, $ingredientId, $qtyPerItem]);
  }

  public static function delete(int $menuId, int $ingredientId): void {
    $stmt = DB::pdo()->prepare("DELETE FROM recipes WHERE menu_item_id=? AND ingredient_id=?");
    $stmt->execute([$menuId, $ingredientId]);
  }

  public static function recipeCostForMenuItem(int $menuId): float {
    $stmt = DB::pdo()->prepare("
      SELECT COALESCE(SUM(r.qty_per_item * i.cost_per_unit), 0) AS cost
      FROM recipes r
      JOIN ingredients i ON i.id=r.ingredient_id
      WHERE r.menu_item_id=?
    ");
    $stmt->execute([$menuId]);
    return (float)$stmt->fetch()['cost'];
  }
}
