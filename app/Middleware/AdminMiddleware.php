<?php
declare(strict_types=1);

namespace App\Middleware;

final class AdminMiddleware
{
    public static function handle(): void
    {
        if (!isset($_SESSION['user'])) {
            header('Location: login');
            exit;
        }

        if (($_SESSION['user']['role'] ?? '') !== 'admin') {
            http_response_code(403);
            echo "403 Forbidden";
            exit;
        }
    }
}
