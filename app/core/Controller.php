<?php
declare(strict_types=1);

abstract class Controller {
  protected function render(string $view, array $data = []): void {
    extract($data);
    $viewFile = __DIR__ . '/../views/' . $view . '.php';
    if (!file_exists($viewFile)) {
      http_response_code(404);
      echo "View not found: " . htmlspecialchars($view);
      exit;
    }
    include __DIR__ . '/../views/layouts/main.php';
  }

  protected function redirect(string $route): void {
    header('Location: index.php?r=' . $route);
    exit;
  }

  protected function get(string $key, $default = null) {
    return $_GET[$key] ?? $default;
  }

  protected function post(string $key, $default = null) {
    return $_POST[$key] ?? $default;
  }
}
