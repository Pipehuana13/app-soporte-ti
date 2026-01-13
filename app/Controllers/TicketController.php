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
        $tickets = $this->tickets->getAll();
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
        $userId = (int) $_SESSION['user']['id'];

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

    require __DIR__ . '/../../views/tickets/show.php';
}

public function update(): void
{
    $id = (int) ($_POST['id'] ?? 0);
    $status = $_POST['status'] ?? '';
    $priority = $_POST['priority'] ?? '';

    if (!in_array($status, ['abierto','en_proceso','cerrado'], true)) {
        return;
    }

    if (!in_array($priority, ['baja','media','alta'], true)) {
        return;
    }

    $this->tickets->updateStatus($id, $status, $priority);

    header('Location: ' . BASE_URL . '/tickets/' . $id);
    exit;
}

}
