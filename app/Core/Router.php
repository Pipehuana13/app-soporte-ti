<?php
declare(strict_types=1);

namespace App\Core;

final class Router
{
    /**
     * @var array<string, array<string, callable|array>>
     */
    private array $routes = [
        'GET'    => [],
        'POST'   => [],
        'PUT'    => [],
        'PATCH'  => [],
        'DELETE' => [],
    ];

    public function get(string $path, callable|array $handler): void
    {
        $this->routes['GET'][$this->normalizePath($path)] = $handler;
    }

    public function post(string $path, callable|array $handler): void
    {
        $this->routes['POST'][$this->normalizePath($path)] = $handler;
    }

    public function dispatch(string $method, string $uri): void
    {
        $method = strtoupper($method);

        // Solo el path, sin query string
        $path = parse_url($uri, PHP_URL_PATH) ?? '/';

        // Base path del proyecto (ej: /app_Soporte/public)
        $basePath = rtrim(dirname($_SERVER['SCRIPT_NAME'] ?? ''), '/\\');
        if ($basePath !== '' && $basePath !== '/') {
            if (str_starts_with($path, $basePath)) {
                $path = substr($path, strlen($basePath));
            }
        }

        $path = $this->normalizePath($path);

        $handler = $this->routes[$method][$path] ?? null;

        if ($handler === null) {
            http_response_code(404);
            echo "404 Not Found";
            return;
        }

        $this->runHandler($handler);
    }

    private function runHandler(callable|array $handler): void
    {
        if (is_array($handler)) {
            [$controller, $action] = $handler;
            $controller->{$action}();
            return;
        }

        $handler();
    }

    private function normalizePath(string $path): string
    {
        $path = trim($path);
        if ($path === '') return '/';

        if ($path[0] !== '/') $path = '/' . $path;
        if (strlen($path) > 1) $path = rtrim($path, '/');

        return $path;
    }
}
