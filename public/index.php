<?php
declare(strict_types=1);

use App\Core\Router;
use App\Controllers\AuthController;
use App\Controllers\TicketController;
use App\Middleware\AuthMiddleware;

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
 * HOME
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
 * DASHBOARD
 */
$router->get('/dashboard', function () {
    AuthMiddleware::handle();
    require __DIR__ . '/../views/dashboard.php';
});

/**
 * TICKETS
 */
$tickets = new TicketController();

$router->get('/tickets', function () use ($tickets) {
    AuthMiddleware::handle();
    $tickets->index();
});

$router->get('/tickets/create', function () use ($tickets) {
    AuthMiddleware::handle();
    $tickets->createForm();
});

$router->post('/tickets/create', function () use ($tickets) {
    AuthMiddleware::handle();
    $tickets->store();
});

/**
 * DETALLE ticket: /tickets/{id}
 * (esto funciona si tu Router soporta {id}. Si tu Router usa otro formato,
 * me dices y lo adapto al tuyo.)
 */
$router->get('/tickets/{id}', function ($id) use ($tickets) {
    AuthMiddleware::handle();
    $tickets->show((int)$id);
});

/**
 * CAMBIAR ESTADO / CERRAR (admin)
 */
$router->post('/tickets/status', function () use ($tickets) {
    AuthMiddleware::handle();
    $tickets->updateStatus();
});

/**
 * DISPATCH
 */
$router->dispatch($_SERVER['REQUEST_METHOD'], $_SERVER['REQUEST_URI']);
