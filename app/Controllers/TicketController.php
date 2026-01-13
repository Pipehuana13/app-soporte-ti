<?php
declare(strict_types=1);

namespace App\Repositories;

use PDO;

final class TicketRepository
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

    public function create(string $title, string $description, int $userId): void
    {
        $stmt = $this->pdo->prepare("
            INSERT INTO tickets (title, description, user_id)
            VALUES (:title, :description, :user_id)
        ");

        $stmt->execute([
            'title' => $title,
            'description' => $description,
            'user_id' => $userId,
        ]);
    }

    public function getAll(): array
    {
        return $this->pdo
            ->query("
                SELECT t.*, u.name AS user_name
                FROM tickets t
                JOIN users u ON u.id = t.user_id
                ORDER BY t.created_at DESC
            ")
            ->fetchAll();
    }

    public function getByUser(int $userId): array
    {
        $stmt = $this->pdo->prepare("
            SELECT t.*, u.name AS user_name
            FROM tickets t
            JOIN users u ON u.id = t.user_id
            WHERE t.user_id = :uid
            ORDER BY t.created_at DESC
        ");
        $stmt->execute(['uid' => $userId]);
        return $stmt->fetchAll();
    }

    public function find(int $id): ?array
    {
        $stmt = $this->pdo->prepare("
            SELECT t.*, u.name AS user_name
            FROM tickets t
            JOIN users u ON u.id = t.user_id
            WHERE t.id = :id
            LIMIT 1
        ");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch() ?: null;
    }

    public function updateStatus(int $id, string $status): void
    {
        $stmt = $this->pdo->prepare("
            UPDATE tickets
            SET status = :status
            WHERE id = :id
        ");
        $stmt->execute([
            'status' => $status,
            'id' => $id,
        ]);
    }
}
