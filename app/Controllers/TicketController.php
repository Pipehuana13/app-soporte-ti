<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Repositories\TicketRepository;

final class TicketController
{
    private TicketRepository $tickets;

    public function __construct()
    {
        $this->tickets = new TicketRepository();
    }

    public function index(): void
    {
        $role = $_SESSION['user']['role'] ?? 'user';
        $userId = (int)($_SESSION['user']['id'] ?? 0);

        if ($role === 'admin') {
            $tickets = $this->tickets->getAll();
        } else {
            $tickets = $this->tickets->getByUser($userId);
        }

        require __DIR__ . '/../../views/tickets/index.php';
    }

    public function createForm(): void
    {
        require __DIR__ . '/../../views/tickets/create.php';
    }

    public function store(): void
    {
        $title = trim($_POST['title'] ?? '');
        $description = trim($_POST['description'] ?? '');
        $userId = (int)($_SESSION['user']['id'] ?? 0);

        if ($title === '' || $description === '') {
            $_SESSION['flash_error'] = 'Todos los campos son obligatorios';
            header('Location: ' . BASE_URL . '/tickets/create');
            exit;
        }

        $this->tickets->create($title, $description, $userId);

        header('Location: ' . BASE_URL . '/tickets');
        exit;
    }

    public function show(int $id): void
    {
        $ticket = $this->tickets->find($id);

        if (!$ticket) {
            http_response_code(404);
            echo "Ticket no encontrado";
            return;
        }

        // seguridad extra: si NO es admin, solo puede ver sus tickets
        $role = $_SESSION['user']['role'] ?? 'user';
        $userId = (int)($_SESSION['user']['id'] ?? 0);
        if ($role !== 'admin' && (int)$ticket['user_id'] !== $userId) {
            http_response_code(403);
            echo "403 Forbidden";
            return;
        }

        require __DIR__ . '/../../views/tickets/show.php';
    }

    public function updateStatus(): void
    {
        // solo admin puede cambiar estado / cerrar
        if (($_SESSION['user']['role'] ?? '') !== 'admin') {
            http_response_code(403);
            echo "403 Forbidden";
            exit;
        }

        $id = (int)($_POST['id'] ?? 0);
        $status = (string)($_POST['status'] ?? '');

        $valid = ['abierto', 'en_proceso', 'cerrado'];
        if ($id <= 0 || !in_array($status, $valid, true)) {
            http_response_code(400);
            echo "Bad Request";
            exit;
        }

        $this->tickets->updateStatus($id, $status);

        header('Location: ' . BASE_URL . '/tickets/' . $id);
        exit;
    }
}

