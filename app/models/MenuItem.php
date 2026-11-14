<?php
declare(strict_types=1);

final class MenuItem {
  public static function all(): array {
    return DB::pdo()->query("SELECT id, name, selling_price as price FROM food_items ORDER BY name")->fetchAll();
  }

  public static function find(int $id): ?array {
    $stmt = DB::pdo()->prepare("SELECT id, name, selling_price as price FROM food_items WHERE id=?");
    $stmt->execute([$id]);
    $row = $stmt->fetch();
    return $row ?: null;
  }

  public static function create(array $data): void {
    $stmt = DB::pdo()->prepare("INSERT INTO food_items (name, selling_price, category, cost_price) VALUES (?, ?, ?, ?)");
    $stmt->execute([
      $data['name'], (float)$data['price'], $data['category'] ?? 'Main Course', (float)($data['cost_price'] ?? 0)
    ]);
  }

  public static function update(int $id, array $data): void {
    $stmt = DB::pdo()->prepare("UPDATE food_items SET name=?, selling_price=? WHERE id=?");
    $stmt->execute([
      $data['name'], (float)$data['price'], $id
    ]);
  }

  public static function delete(int $id): void {
    $stmt = DB::pdo()->prepare("DELETE FROM food_items WHERE id=?");
    $stmt->execute([$id]);
  }
}
