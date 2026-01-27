<?php
declare(strict_types=1);

use App\Core\Router;
use App\Controllers\AuthController;
use App\Controllers\TicketController;
use App\Middleware\AuthMiddleware;
use App\Middleware\AdminMiddleware;
use App\Controllers\UserController;

require __DIR__ . '/../vendor/autoload.php';

// Cargar variables desde .env
$dotenv = Dotenv\Dotenv::createImmutable(dirname(__DIR__));
$dotenv->safeLoad();


session_start();

/**
 * BASE_URL automático para XAMPP en subcarpeta
 * Ej: /app_Soporte/public
 */
$baseUrl = rtrim(dirname($_SERVER['SCRIPT_NAME'] ?? ''), '/\\');
define('BASE_URL', ($baseUrl === '' || $baseUrl === '/') ? '' : $baseUrl);

$router = new Router();
$users = new UserController();

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

$router->get('/tickets/show', function () use ($tickets) {
    AuthMiddleware::handle();
    $id = (int)($_GET['id'] ?? 0);
    $tickets->show($id);
});

/**
 * DETALLE ticket: /tickets/{id}
 * (esto funciona si Router soporta {id}. Si Router usa otro formato,
 *.)
 */
$router->get('/tickets/{id}', function () use ($tickets) {
    AuthMiddleware::handle();
    $id = (int)($_GET['id'] ?? 0);
    $tickets->show($id);
});

/**
 * CAMBIAR ESTADO / CERRAR (admin)
 */
$router->post('/tickets/status', function () use ($tickets) {
    AuthMiddleware::handle();
    $tickets->updateStatus();
});

$router->post('/tickets/comment', function () use ($tickets) {
    AuthMiddleware::handle();
    $tickets->addComment();
});

// USUARIOS
$users = new UserController();

$router->get('/users', function () use ($users) {
    AdminMiddleware::handle();
    $users->index();
});
// CREAR USUARIO
$router->get('/users/create', function () use ($users) {
    AdminMiddleware::handle();
    $users->createForm(); // CREAR
});
// CREAR ALMACENAR O PAPOI?
$router->post('/users/create', function () use ($users) {
    AdminMiddleware::handle();
    $users->store(); // ALMACENAR??
});
// ELIMINAR O DESACTIVAR 
$router->post('/users/delete', function () use ($users) {
    AdminMiddleware::handle();
    $users->delete(); // ELIMINAR O DESACTIVAR PAPOI
});


/**
 * DISPATCH
 */
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) ?? '/';

// si estás en /app_Soporte/public, se lo quitamos para que el router reciba /tickets
$path = substr($uri, strlen(BASE_URL)) ?: '/';

$router->dispatch($_SERVER['REQUEST_METHOD'], $path);

