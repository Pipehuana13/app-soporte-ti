<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Repositories\UserRepository;

class AuthController
{
    private UserRepository $users;

    public function __construct()
    {
        $this->users = new UserRepository();
    }

    public function showLogin(): void
    {
        // Si ya está logueado, lo mandamos al dashboard
        if (isset($_SESSION['user'])) {
            header('Location: dashboard');
            exit;
        }

        $error = $_SESSION['flash_error'] ?? null;
        unset($_SESSION['flash_error']);

        require __DIR__ . '/../../views/login.php';
    }

    public function doLogin(): void
    {
        $email = trim($_POST['email'] ?? '');
        $password = (string)($_POST['password'] ?? '');

        // Validación básica
        if ($email === '' || $password === '') {
            $_SESSION['flash_error'] = 'Ingresa correo y contraseña.';
            header('Location: login');
            exit;
        }

        $user = $this->users->findActiveByEmail($email);

        // Credenciales
        if (!$user || !isset($user['password_hash']) || !password_verify($password, $user['password_hash'])) {
            $_SESSION['flash_error'] = 'Credenciales incorrectas.';
            header('Location: login');
            exit;
        }

        // Seguridad: evita fixation
        session_regenerate_id(true);

        $_SESSION['user'] = [
            'id'    => (int)$user['id'],
            'name'  => (string)$user['name'],
            'email' => (string)$user['email'],
            'role'  => (string)$user['role'],
        ];

        header('Location: dashboard');
        exit;
    }

    public function logout(): void
    {
        // Limpia sesión de forma correcta
        $_SESSION = [];

        if (ini_get('session.use_cookies')) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000, $params['path'], $params['domain'], $params['secure'], $params['httponly']);
        }

        session_destroy();

        header('Location: login');
        exit;
    }
}
