<?php
declare(strict_types=1);

namespace App\Core;

final class Router
{
    /**
     * @var array<string, array<string, callable|array>>
     */
    private array $routes = [
        'GET'  => [],
        'POST' => [],
        'PUT'  => [],
        'PATCH'=> [],
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

    public function put(string $path, callable|array $handler): void
    {
        $this->routes['PUT'][$this->normalizePath($path)] = $handler;
    }

    public function patch(string $path, callable|array $handler): void
    {
        $this->routes['PATCH'][$this->normalizePath($path)] = $handler;
    }

    public function delete(string $path, callable|array $handler): void
    {
        $this->routes['DELETE'][$this->normalizePath($path)] = $handler;
    }

    public function dispatch(string $method, string $uri): void
    {
        $method = strtoupper($method);
        $path = $this->normalizePath($this->stripQueryString($uri));

        $handler = $this->routes[$method][$path] ?? null;

        if ($handler === null) {
            // Si la ruta existe pero con otro método => 405
            if ($this->pathExistsInOtherMethod($path)) {
                http_response_code(405);
                echo "405 Method Not Allowed";
                return;
            }

            http_response_code(404);
            echo "404 Not Found";
            return;
        }

        $this->runHandler($handler);
    }

    private function runHandler(callable|array $handler): void
    {
        // Handler como [objetoController, 'metodo']
        if (is_array($handler)) {
            // Formato esperado: [$controllerInstance, 'methodName']
            if (count($handler) !== 2) {
                throw new \InvalidArgumentException("Route handler array must be [controller, method].");
            }

            [$controller, $action] = $handler;

            if (!is_object($controller) || !is_string($action)) {
                throw new \InvalidArgumentException("Route handler array must be [object, string].");
            }

            if (!method_exists($controller, $action)) {
                $class = get_class($controller);
                throw new \BadMethodCallException("Method {$class}::{$action} does not exist.");
            }

            $controller->{$action}();
            return;
        }

        // Callable normal (closure / función)
        $handler();
    }

    private function stripQueryString(string $uri): string
    {
        $qPos = strpos($uri, '?');
        if ($qPos === false) {
            return $uri;
        }
        return substr($uri, 0, $qPos);
    }

    private function normalizePath(string $path): string
    {
        $path = trim($path);

        if ($path === '') {
            return '/';
        }

        // Asegura que empiece con /
        if ($path[0] !== '/') {
            $path = '/' . $path;
        }

        // Quita trailing slash si no es root
        if (strlen($path) > 1) {
            $path = rtrim($path, '/');
        }

        return $path;
    }

    private function pathExistsInOtherMethod(string $path): bool
    {
        foreach ($this->routes as $m => $map) {
            if (isset($map[$path])) {
                return true;
            }
        }
        return false;
    }
}
