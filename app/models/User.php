<?php
declare(strict_types=1);

final class User {
  public static function countAll(): int {
    $stmt = DB::pdo()->query("SELECT COUNT(*) AS c FROM users");
    return (int)$stmt->fetch()['c'];
  }

  public static function findByEmail(string $email): ?array {
    $stmt = DB::pdo()->prepare("SELECT * FROM users WHERE email = ? LIMIT 1");
    $stmt->execute([$email]);
    $row = $stmt->fetch();
    return $row ?: null;
  }

  public static function create(string $name, string $email, string $password, string $role): int {
    $hash = password_hash($password, PASSWORD_DEFAULT);
    $stmt = DB::pdo()->prepare("INSERT INTO users (name, email, password_hash, role) VALUES (?, ?, ?, ?)");
    $stmt->execute([$name, $email, $hash, $role]);
    return (int)DB::pdo()->lastInsertId();
  }
}
