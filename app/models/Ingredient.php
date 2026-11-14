<?php
declare(strict_types=1);

final class Ingredient {
  public static function all(): array {
    return DB::pdo()->query("SELECT * FROM ingredients ORDER BY name")->fetchAll();
  }

  public static function find(int $id): ?array {
    $stmt = DB::pdo()->prepare("SELECT * FROM ingredients WHERE id=?");
    $stmt->execute([$id]);
    $row = $stmt->fetch();
    return $row ?: null;
  }

  public static function create(array $data): void {
    $stmt = DB::pdo()->prepare("INSERT INTO ingredients (name, unit, cost_per_unit, reorder_level) VALUES (?, ?, ?, ?)");
    $stmt->execute([
      $data['name'], $data['unit'], (float)$data['cost_per_unit'], (float)$data['reorder_level']
    ]);
  }

  public static function update(int $id, array $data): void {
    $stmt = DB::pdo()->prepare("UPDATE ingredients SET name=?, unit=?, cost_per_unit=?, reorder_level=? WHERE id=?");
    $stmt->execute([
      $data['name'], $data['unit'], (float)$data['cost_per_unit'], (float)$data['reorder_level'], $id
    ]);
  }

  public static function delete(int $id): void {
    $stmt = DB::pdo()->prepare("DELETE FROM ingredients WHERE id=?");
    $stmt->execute([$id]);
  }
}