<?php
declare(strict_types=1);

namespace App\Repositories;

use PDO;
use App\Core\DB;

final class TicketRepository
{
    private PDO $db;

    public function __construct()
    {
        $this->db = DB::pdo();
    }

    public function create(string $title, string $description, int $userId): int
    {
        $stmt = $this->db->prepare("
            INSERT INTO tickets (title, description, user_id)
            VALUES (:title, :description, :user_id)
        ");

        $stmt->execute([
            'title' => $title,
            'description' => $description,
            'user_id' => $userId,
        ]);

        return (int)$this->db->lastInsertId();

    }

    public function getAll(): array
    {
        return $this->db
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
        $stmt = $this->db->prepare("
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
        $stmt = $this->db->prepare("
            SELECT t.*, u.name AS user_name
            FROM tickets t
            JOIN users u ON u.id = t.user_id
            WHERE t.id = :id
            LIMIT 1
        ");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch() ?: null;
    }

    public function updateStatus(int $ticketId, string $newStatus, int $changedBy): void
{
    // 1) estado actual
    $stmt = $this->db->prepare("SELECT status FROM tickets WHERE id = :id LIMIT 1");
    $stmt->execute(['id' => $ticketId]);
    $row = $stmt->fetch(\PDO::FETCH_ASSOC);

    if (!$row) {
        throw new \RuntimeException('Ticket no existe');
    }

    $oldStatus = (string)$row['status'];

    if ($oldStatus === $newStatus) {
        return; // no hay cambio
    }

    // 2) update
    $stmt = $this->db->prepare("UPDATE tickets SET status = :status WHERE id = :id");
    $stmt->execute(['status' => $newStatus, 'id' => $ticketId]);

    // 3) guardar historial
    $stmt = $this->db->prepare("
        INSERT INTO ticket_status_history (ticket_id, old_status, new_status, changed_by)
        VALUES (:ticket_id, :old_status, :new_status, :changed_by)
    ");
    $stmt->execute([
        'ticket_id' => $ticketId,
        'old_status' => $oldStatus,
        'new_status' => $newStatus,
        'changed_by' => $changedBy,
    ]);
}

public function getStatusHistory(int $ticketId): array
{
    $stmt = $this->db->prepare("
        SELECT h.*, u.name AS user_name
        FROM ticket_status_history h
        LEFT JOIN users u ON u.id = h.changed_by
        WHERE h.ticket_id = :ticket_id
        ORDER BY h.changed_at DESC
    ");
    $stmt->execute(['ticket_id' => $ticketId]);
    return $stmt->fetchAll(\PDO::FETCH_ASSOC) ?: [];
}

public function addComment(int $ticketId, int $userId, string $comment): void
{
    $stmt = $this->db->prepare("
        INSERT INTO ticket_comments (ticket_id, user_id, comment)
        VALUES (:ticket_id, :user_id, :comment)
    ");
    $stmt->execute([
        'ticket_id' => $ticketId,
        'user_id' => $userId,
        'comment' => $comment,
    ]);
}

public function getComments(int $ticketId): array
{
    $stmt = $this->db->prepare("
        SELECT c.*, u.name AS user_name
        FROM ticket_comments c
        LEFT JOIN users u ON u.id = c.user_id
        WHERE c.ticket_id = :ticket_id
        ORDER BY c.created_at DESC
    ");
    $stmt->execute(['ticket_id' => $ticketId]);
    return $stmt->fetchAll(\PDO::FETCH_ASSOC) ?: [];
}

}
