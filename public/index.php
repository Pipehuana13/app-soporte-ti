<?php
declare(strict_types=1);

use App\Core\Router;
use App\Controllers\AuthController;

require __DIR__ . '/../vendor/autoload.php';

session_start();

$router = new Router();

$router->get('/', function () {
    header('Location: /login');
    exit;
});

$router->get('/login', [new AuthController(), 'showLogin']);
$router->post('/login', [new AuthController(), 'doLogin']);
$router->get('/logout', [new AuthController(), 'logout']);

$router->dispatch($_SERVER['REQUEST_METHOD'], $_SERVER['REQUEST_URI']);
