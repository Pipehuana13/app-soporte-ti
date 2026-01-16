<?php
declare(strict_types=1);

namespace App\Repositories;

use PDO;

class UserRepository
{
    private PDO $pdo;

    public function __construct()
    {
        $this->pdo = new PDO(
            "mysql:host=localhost;dbname=soporte_ti;charset=utf8mb4",
            "root",
            "",
            [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            ]
        );
    }

    public function findActiveByEmail(string $email): ?array
    {
        $stmt = $this->pdo->prepare("
            SELECT id, name, email, password_hash, role, active
            FROM users
            WHERE email = :email
            LIMIT 1
        ");

        $stmt->execute(['email' => $email]);
        $user = $stmt->fetch();

        if (!$user) {
            return null;
        }

        // 👇 AQUÍ ESTABA EL ERROR
        if ((int)$user['active'] !== 1) {
            return null;
        }

        return $user;
    }

    public function getAll(): array
    {
        $stmt = $this->pdo->query("SELECT id, name, email, role, active FROM users ORDER BY id DESC");
        return $stmt->fetchAll(\PDO::FETCH_ASSOC) ?: [];
    }

public function create(string $name, string $email, string $password, string $role = 'user'): void
{
    $hash = password_hash($password, PASSWORD_DEFAULT);

    $stmt = $this->pdo->prepare("
        INSERT INTO users (name, email, password_hash, role, active)
        VALUES (:name, :email, :password_hash, :role, 1)
    ");

    $stmt->execute([
        'name' => $name,
        'email' => $email,
        'password_hash' => $hash,
        'role' => $role,
    ]);
}

public function setActive(int $id, int $active): void
{
    $stmt = $this->pdo->prepare("UPDATE users SET active = :a WHERE id = :id");
    $stmt->execute(['a' => $active, 'id' => $id]);
}

public function hasTickets(int $id): bool
{
    $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM tickets WHERE user_id = :id");
    $stmt->execute(['id' => $id]);
    return (int)$stmt->fetchColumn() > 0;
}

public function deleteById(int $id): void
{
    $stmt = $this->pdo->prepare("DELETE FROM users WHERE id = :id");
    $stmt->execute(['id' => $id]);
}

public function all(): array
{
    $stmt = $this->pdo->query("SELECT id, name, email, role, active FROM users ORDER BY id DESC");
    return $stmt->fetchAll(\PDO::FETCH_ASSOC) ?: [];
}


}
