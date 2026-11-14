<?php
declare(strict_types=1);

final class Inventory {
  public static function addTxn(array $data): void {
    $stmt = DB::pdo()->prepare("
      INSERT INTO inventory_transactions (ingredient_id, txn_type, quantity, note, txn_date)
      VALUES (?, ?, ?, ?, ?)
    ");
    $stmt->execute([
      (int)$data['ingredient_id'],
      $data['txn_type'],
      (float)$data['quantity'],
      $data['note'] ?? '',
      $data['txn_date']
    ]);
  }

  public static function recent(int $limit = 50): array {
    $stmt = DB::pdo()->prepare("
      SELECT t.*, i.name AS ingredient_name, i.unit
      FROM inventory_transactions t
      JOIN ingredients i ON i.id=t.ingredient_id
      ORDER BY t.txn_date DESC, t.id DESC
      LIMIT ?
    ");
    $stmt->bindValue(1, $limit, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll();
  }

  public static function stockByIngredient(): array {
    $sql = "
      SELECT i.id, i.name, i.unit, i.reorder_level,
        COALESCE(SUM(CASE WHEN t.txn_type='IN' THEN t.quantity WHEN t.txn_type='OUT' THEN -t.quantity WHEN t.txn_type='ADJUST' THEN t.quantity ELSE 0 END),0) AS stock
      FROM ingredients i
      LEFT JOIN inventory_transactions t ON t.ingredient_id=i.id
      GROUP BY i.id
      ORDER BY i.name
    ";
    return DB::pdo()->query($sql)->fetchAll();
  }

  public static function stockFor(int $ingredientId): float {
    $stmt = DB::pdo()->prepare("
      SELECT COALESCE(SUM(CASE WHEN txn_type='IN' THEN quantity WHEN txn_type='OUT' THEN -quantity WHEN txn_type='ADJUST' THEN quantity ELSE 0 END),0) AS stock
      FROM inventory_transactions
      WHERE ingredient_id=?
    ");
    $stmt->execute([$ingredientId]);
    return (float)$stmt->fetch()['stock'];
  }
}
