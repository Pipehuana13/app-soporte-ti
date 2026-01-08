<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Core\Auth;
use App\Core\Csrf;
use App\Repositories\TicketRepository;

final class TicketController extends Controller {
  public function index(): void {
    Auth::start();
    if (!Auth::check()) $this->redirect('/login');

    $u = Auth::user();
    $repo = new TicketRepository();
    $tickets = $repo->allForUser($u['id'], $u['role']);

    $this->view('tickets/index', ['tickets' => $tickets, 'user' => $u]);
  }

  public function createForm(): void {
    Auth::start();
    if (!Auth::check()) $this->redirect('/login');

    $this->view('tickets/create', ['csrf' => Csrf::token()]);
  }

  public function store(): void {
    Auth::start();
    if (!Auth::check()) $this->redirect('/login');
    Csrf::verify($_POST['csrf'] ?? null);

    $u = Auth::user();
    $repo = new TicketRepository();
    $id = $repo->create([
      'title' => trim($_POST['title'] ?? ''),
      'description' => trim($_POST['description'] ?? ''),
      'category' => $_POST['category'] ?? 'Otros',
      'priority' => $_POST['priority'] ?? 'Media',
      'requester_id' => $u['id'],
    ]);

    $this->redirect("/tickets/show?id={$id}");
  }

  public function show(): void {
    Auth::start();
    if (!Auth::check()) $this->redirect('/login');

    $id = (int)($_GET['id'] ?? 0);
    $repo = new TicketRepository();
    $ticket = $repo->find($id);

    if (!$ticket) { http_response_code(404); echo "Ticket no existe"; return; }

    $this->view('tickets/show', ['ticket' => $ticket]);
  }
}
