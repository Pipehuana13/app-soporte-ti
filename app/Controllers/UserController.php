<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Repositories\UserRepository;

class UserController
{
    private UserRepository $users;

    public function __construct()
    {
        $this->users = new UserRepository();
    }

    private function requireAdmin(): void
    {
        if (($_SESSION['user']['role'] ?? '') !== 'admin') {
            http_response_code(403);
            exit('403 Forbidden');
        }
    }

    public function index(): void
    {
        $this->requireAdmin();

        // OJO: debe existir all() en tu UserRepository
        $users = $this->users->all();

        require __DIR__ . '/../../views/users/index.php';
    }

    public function createForm(): void
    {
        $this->requireAdmin();
        require __DIR__ . '/../../views/users/create.php';
    }

    public function store(): void
    {
        $this->requireAdmin();

        $name  = trim($_POST['name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $role  = trim($_POST['role'] ?? 'user');
        $pass  = (string)($_POST['password'] ?? '');

        if ($name === '' || $email === '' || $pass === '') {
            $_SESSION['flash_error'] = 'Todos los campos son obligatorios.';
            header('Location: ' . BASE_URL . '/users/create');
            exit;
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $_SESSION['flash_error'] = 'Email inválido.';
            header('Location: ' . BASE_URL . '/users/create');
            exit;
        }

        if (!in_array($role, ['admin', 'user'], true)) {
            $role = 'user';
        }

        // OJO: debe existir create() en tu UserRepository
        $this->users->create($name, $email, $pass, $role);

        $_SESSION['flash_success'] = 'Usuario creado correctamente.';
        header('Location: ' . BASE_URL . '/users');
        exit;
    }

    public function delete(): void
    {
        $this->requireAdmin();

        $id = (int)($_POST['id'] ?? 0);
        if ($id <= 0) {
            header('Location: ' . BASE_URL . '/users');
            exit;
        }

        $me = (int)($_SESSION['user']['id'] ?? 0);
        if ($id === $me) {
            $_SESSION['flash_error'] = 'No puedes eliminar tu propio usuario.';
            header('Location: ' . BASE_URL . '/users');
            exit;
        }

        // OJO: debe existir deleteById() en tu UserRepository
        $this->users->deleteById($id);

        $_SESSION['flash_success'] = 'Usuario eliminado.';
        header('Location: ' . BASE_URL . '/users');
        exit;
    }
}
