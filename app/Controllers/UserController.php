<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Repositories\UserRepository;

final class UserController
{
    private UserRepository $users;

    public function __construct()
    {
        $this->users = new UserRepository();
    }

    public function index(): void
    {
        $users = $this->users->getAll();
        require __DIR__ . '/../../views/users/index.php';
    }

    public function createForm(): void
    {
        require __DIR__ . '/../../views/users/create.php';
    }

    public function store(): void
    {
        $name = trim($_POST['name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $password = (string)($_POST['password'] ?? '');
        $role = $_POST['role'] ?? 'user';
        $active = (int)($_POST['active'] ?? 1);

        if ($name === '' || $email === '' || $password === '') {
            $_SESSION['flash_error'] = 'Todos los campos son obligatorios.';
            header('Location: ' . BASE_URL . '/admin/users/create');
            exit;
        }

        if (!in_array($role, ['admin','user'], true)) $role = 'user';
        $active = ($active === 1) ? 1 : 0;

        try {
            $this->users->create($name, $email, $password, $role, $active);
            $_SESSION['flash_success'] = 'Usuario creado correctamente.';
        } catch (\Throwable $e) {
            $_SESSION['flash_error'] = 'No se pudo crear (¿email duplicado?).';
        }

        header('Location: ' . BASE_URL . '/admin/users');
        exit;
    }

    public function toggleActive(): void
    {
        $id = (int)($_POST['id'] ?? 0);
        $active = (int)($_POST['active'] ?? 1);

        if ($id === (int)($_SESSION['user']['id'] ?? 0)) {
            $_SESSION['flash_error'] = 'No puedes desactivar tu propio usuario.';
            header('Location: ' . BASE_URL . '/admin/users');
            exit;
        }

        $this->users->setActive($id, $active === 1 ? 1 : 0);
        $_SESSION['flash_success'] = 'Estado actualizado.';
        header('Location: ' . BASE_URL . '/admin/users');
        exit;
    }

    public function delete(): void
    {
        $id = (int)($_POST['id'] ?? 0);

        if ($id === (int)($_SESSION['user']['id'] ?? 0)) {
            $_SESSION['flash_error'] = 'No puedes eliminar tu propio usuario.';
            header('Location: ' . BASE_URL . '/admin/users');
            exit;
        }

        // Si tiene tickets, mejor desactivar
        if ($this->users->hasTickets($id)) {
            $this->users->setActive($id, 0);
            $_SESSION['flash_success'] = 'Usuario desactivado (tenía tickets asociados).';
            header('Location: ' . BASE_URL . '/admin/users');
            exit;
        }

        $this->users->deleteById($id);
        $_SESSION['flash_success'] = 'Usuario eliminado.';
        header('Location: ' . BASE_URL . '/admin/users');
        exit;
    }
}
