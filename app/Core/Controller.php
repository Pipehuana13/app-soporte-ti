<?php
namespace App\Core;

class Controller {
  protected function view(string $view, array $data = []): void {
    extract($data);
    $viewFile = __DIR__ . "/../../views/{$view}.php";
    require __DIR__ . "/../../views/layouts/main.php";
  }

  protected function redirect(string $path): void {
    header("Location: {$path}");
    exit;
  }
}
