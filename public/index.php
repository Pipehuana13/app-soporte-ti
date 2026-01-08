<?php
declare(strict_types=1);

use App\Core\Router;
use App\Controllers\AuthController;
use App\Middleware\AuthMiddleware;

require __DIR__ . '/../vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(dirname(__DIR__));
$dotenv->safeLoad();


session_start();

$router = new Router();

// Base path (ej: /app_Soporte/public)
$base = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? ''));
$base = rtrim($base, '/');
if ($base === '') $base = '';

// Home: si está logeado -> dashboard, si no -> login
$router->get('/', function () use ($base) {
    if (isset($_SESSION['user'])) {
        header("Location: {$base}/dashboard");
        exit;
    }
    header("Location: {$base}/login");
    exit;
});

// Login
$router->get('/login', [new AuthController($base), 'showLogin']);
$router->post('/login', [new AuthController($base), 'doLogin']);

// Logout
$router->get('/logout', [new AuthController($base), 'logout']);

// Ruta protegida (ejemplo)
$router->get('/dashboard', function () use ($base) {
    AuthMiddleware::handle($base);

    echo "<h1>Dashboard</h1>";
    echo "<p>Hola, " . htmlspecialchars($_SESSION['user']['name']) . "</p>";
    echo "<p><a href='{$base}/logout'>Cerrar sesión</a></p>";
});

$router->dispatch($_SERVER['REQUEST_METHOD'], $_SERVER['REQUEST_URI']);
