<?php
namespace App\Core;

final class Csrf {
  public static function token(): string {
    Auth::start();
    if (empty($_SESSION['csrf'])) {
      $_SESSION['csrf'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf'];
  }

  public static function verify(?string $token): void {
    Auth::start();
    if (!$token || !hash_equals($_SESSION['csrf'] ?? '', $token)) {
      http_response_code(419);
      exit('CSRF inválido');
    }
  }
}
