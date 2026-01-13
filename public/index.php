<?php
declare(strict_types=1);

use App\Core\Router;
use App\Controllers\AuthController;
use App\Controllers\TicketController;
use App\Middleware\AuthMiddleware;
use App\Controllers\UserController;
use App\Middleware\AdminMiddleware;

require __DIR__ . '/../vendor/autoload.php';

session_start();

/**
 * BASE_URL automático para XAMPP en subcarpeta
 * Ej: /app_Soporte/public
 */
$baseUrl = rtrim(dirname($_SERVER['SCRIPT_NAME'] ?? ''), '/\\');
define('BASE_URL', ($baseUrl === '' || $baseUrl === '/') ? '' : $baseUrl);

$router = new Router();

/**
 * HOME → login
 */
$router->get('/', function () {
    header('Location: ' . BASE_URL . '/login');
    exit;
});

/**
 * AUTH
 */
$auth = new AuthController();

$router->get('/login', [$auth, 'showLogin']);
$router->post('/login', [$auth, 'doLogin']);
$router->get('/logout', [$auth, 'logout']);

/**
 * DASHBOARD (protegido)
 */
$router->get('/dashboard', function () {
    AuthMiddleware::handle();
    require __DIR__ . '/../views/dashboard.php';
});

/**
 * TICKETS
 */
$ticket = new TicketController();

/* Listar tickets */
$router->get('/tickets', function () use ($ticket) {
    AuthMiddleware::handle();
    $ticket->index();
});

/* Form crear ticket */
$router->get('/tickets/create', function () use ($ticket) {
    AuthMiddleware::handle();
    $ticket->createForm();
});

/* Guardar ticket */
$router->post('/tickets/create', function () use ($ticket) {
    AuthMiddleware::handle();
    $ticket->store();
});

/* Ver ticket */
$router->get('/tickets/{id}', function ($id) use ($ticket) {
    AuthMiddleware::handle();
    $ticket->show((int)$id);
});

/* Actualizar ticket (admin) */
$router->post('/tickets/update', function () use ($ticket) {
    AuthMiddleware::handle();
    $ticket->update();
});

$userAdmin = new UserController();

$router->get('/admin/users', function () use ($userAdmin) {
    AdminMiddleware::handle();
    $userAdmin->index();
});

$router->get('/admin/users/create', function () use ($userAdmin) {
    AdminMiddleware::handle();
    $userAdmin->createForm();
});

$router->post('/admin/users/create', function () use ($userAdmin) {
    AdminMiddleware::handle();
    $userAdmin->store();
});

$router->post('/admin/users/toggle', function () use ($userAdmin) {
    AdminMiddleware::handle();
    $userAdmin->toggleActive();
});

$router->post('/admin/users/delete', function () use ($userAdmin) {
    AdminMiddleware::handle();
    $userAdmin->delete();
});


/**
 * DISPATCH
 */
$router->dispatch($_SERVER['REQUEST_METHOD'], $_SERVER['REQUEST_URI']);
