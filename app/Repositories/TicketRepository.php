<?php
namespace App\Repositories;

use App\Core\DB;

final class TicketRepository {
  public function allForUser(int $userId, string $role): array {
    if ($role === 'usuario') {
      $stmt = DB::pdo()->prepare("SELECT * FROM tickets WHERE requester_id = ? ORDER BY id DESC");
      $stmt->execute([$userId]);
      return $stmt->fetchAll();
    }
    // soporte/admin ven todo
    return DB::pdo()->query("SELECT * FROM tickets ORDER BY id DESC")->fetchAll();
  }

  public function create(array $data): int {
    $stmt = DB::pdo()->prepare("
      INSERT INTO tickets (title, description, category, priority, requester_id)
      VALUES (?, ?, ?, ?, ?)
    ");
    $stmt->execute([
      $data['title'], $data['description'], $data['category'], $data['priority'], $data['requester_id']
    ]);
    return (int)DB::pdo()->lastInsertId();
  }

  public function find(int $id): ?array {
    $stmt = DB::pdo()->prepare("SELECT * FROM tickets WHERE id = ? LIMIT 1");
    $stmt->execute([$id]);
    $t = $stmt->fetch();
    return $t ?: null;
  }
}
