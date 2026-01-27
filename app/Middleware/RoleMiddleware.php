<?php
declare(strict_types=1);

namespace App\Middleware;

final class RoleMiddleware
{
    /**
     * @param string[] $rolesPermitidos
     */
    public static function handle(array $rolesPermitidos): void
    {
        if (!isset($_SESSION['user'])) {
            header('Location: ' . BASE_URL . '/login');
            exit;
        }

        $role = $_SESSION['user']['role'] ?? '';
        if (!in_array($role, $rolesPermitidos, true)) {
            http_response_code(403);
            echo "403 Forbidden";
            exit;
        }
    }
}
