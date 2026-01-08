<?php
declare(strict_types=1);

namespace App\Repositories;

use App\Core\DB;
use PDO;

final class UserRepository
{
    public function findActiveByEmail(string $email): ?array
    {
        $pdo = DB::pdo();

        $stmt = $pdo->prepare("
            SELECT id, name, email, password_hash, role, active
            FROM users
            WHERE email = :email
            LIMIT 1
        ");
        $stmt->execute(['email' => $email]);

        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$user) return null;

        if ((int)$user['active'] !== 1) {
            return null;
        }

        return $user;
    }
}
