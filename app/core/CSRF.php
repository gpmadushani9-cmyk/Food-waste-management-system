<?php
declare(strict_types=1);

final class CSRF {
  public static function token(): string {
    $key = config('security.csrf_key', 'csrf_token');
    if (empty($_SESSION[$key])) {
      $_SESSION[$key] = bin2hex(random_bytes(16));
    }
    return $_SESSION[$key];
  }

  public static function verify(?string $token): bool {
    $key = config('security.csrf_key', 'csrf_token');
    return isset($_SESSION[$key]) && is_string($token) && hash_equals($_SESSION[$key], $token);
  }
}
