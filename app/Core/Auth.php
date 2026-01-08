<?php
namespace App\Core;

final class Auth {
  public static function start(): void {
    if (session_status() === PHP_SESSION_NONE) session_start();
  }

  public static function user(): ?array {
    self::start();
    return $_SESSION['user'] ?? null;
  }

  public static function check(): bool {
    return self::user() !== null;
  }

  public static function login(array $user): void {
    self::start();
    $_SESSION['user'] = [
      'id' => $user['id'],
      'name' => $user['name'],
      'email' => $user['email'],
      'role' => $user['role'],
    ];
  }

  public static function logout(): void {
    self::start();
    unset($_SESSION['user']);
  }
}
