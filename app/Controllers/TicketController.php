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

// Notificación (flash)
$ticketId = $this->tickets->create($title, $description, $userId);

$_SESSION['notify'] = [
  'title' => '📩 Ticket creado',
  'body'  => 'Tu ticket fue creado correctamente.'
];

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

        $history = $this->tickets->getStatusHistory($id);
        $comments = $this->tickets->getComments($id);

        require __DIR__ . '/../../views/tickets/show.php';
    }

    public function addComment(): void
{
    // Debe estar logueado
    if (!isset($_SESSION['user'])) {
        header('Location: ' . BASE_URL . '/login');
        exit;
    }

    $ticketId = (int)($_POST['ticket_id'] ?? 0);
    $comment  = trim((string)($_POST['comment'] ?? ''));
    $userId   = (int)($_SESSION['user']['id'] ?? 0);

    if ($ticketId <= 0 || $comment === '') {
        $_SESSION['flash_error'] = 'Comentario vacío o ticket inválido.';
        header('Location: ' . BASE_URL . '/tickets/show?id=' . $ticketId);
        exit;
    }

    // Verificar que exista el ticket
    $ticket = $this->tickets->find($ticketId);
    if (!$ticket) {
        http_response_code(404);
        echo 'Ticket no encontrado';
        exit;
    }

    // Seguridad: usuario normal solo puede comentar sus tickets
    $role = $_SESSION['user']['role'] ?? 'user';
    if ($role !== 'admin' && (int)$ticket['user_id'] !== $userId) {
        http_response_code(403);
        echo '403 Forbidden';
        exit;
    }

    // (Opcional recomendado) No permitir comentarios si está cerrado
    if (($ticket['status'] ?? '') === 'cerrado') {
        $_SESSION['flash_error'] = 'No puedes comentar un ticket cerrado.';
        header('Location: ' . BASE_URL . '/tickets/show?id=' . $ticketId);
        exit;
    }

    // Guardar comentario
    $this->tickets->addComment($ticketId, $userId, $comment);

    header('Location: ' . BASE_URL . '/tickets/show?id=' . $ticketId);
    exit;
}
    public function updateStatus(): void
{
    if (($_SESSION['user']['role'] ?? '') !== 'admin') {
        http_response_code(403);
        exit('403 Forbidden');
    }

    $id = (int)($_POST['id'] ?? 0);
    $status = (string)($_POST['status'] ?? '');

    $valid = ['abierto', 'en_proceso', 'cerrado'];
    if ($id <= 0 || !in_array($status, $valid, true)) {
        http_response_code(400);
        exit('Bad Request');
    }

    // Regla: si está cerrado, no se puede reabrir (si quieres permitirlo, te lo cambio)
    $ticket = $this->tickets->find($id);
    if (!$ticket) {
        http_response_code(404);
        exit('Ticket no encontrado');
    }
    if (($ticket['status'] ?? '') === 'cerrado') {
        $_SESSION['flash_error'] = 'Este ticket ya está cerrado y no se puede modificar.';
        header('Location: ' . BASE_URL . '/tickets');
        exit;
    }

    $changedBy = (int)($_SESSION['user']['id'] ?? 0);

    $this->tickets->updateStatus($id, $status, $changedBy);

    header('Location: ' . BASE_URL . '/tickets'); // vuelve a la lista
    exit;
}

}

