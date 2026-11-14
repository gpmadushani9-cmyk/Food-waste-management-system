<?php
declare(strict_types=1);

final class Auth {
  public static function user(): ?array {
    return $_SESSION['user'] ?? null;
  }

  public static function check(): bool {
    return isset($_SESSION['user']);
  }

  public static function requireLogin(): void {
    if (!self::check()) {
      header('Location: index.php?r=auth/login');
      exit;
    }
  }

  public static function login(array $user): void {
    $_SESSION['user'] = [
      'id' => (int)$user['id'],
      'name' => $user['name'],
      'email' => $user['email'],
      'role' => $user['role'],
    ];
  }

  public static function logout(): void {
    unset($_SESSION['user']);
    session_regenerate_id(true);
  }

  public static function isAdmin(): bool {
    return (self::user()['role'] ?? '') === 'admin';
  }
}
