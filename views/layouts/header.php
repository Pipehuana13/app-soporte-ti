<?php
declare(strict_types=1);

// BASE_URL viene definido en public/index.php
$user = $_SESSION['user'] ?? null;
?>
<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?= htmlspecialchars($title ?? 'Soporte TI') ?></title>

  <!-- Bootstrap (CDN) -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <div class="container">
    <a class="navbar-brand" href="<?= BASE_URL ?>/dashboard">Soporte TI</a>

    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navMain">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navMain">
      <?php if ($user): ?>
        <ul class="navbar-nav me-auto mb-2 mb-lg-0">
          <li class="nav-item">
            <a class="nav-link" href="<?= BASE_URL ?>/dashboard">Dashboard</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="<?= BASE_URL ?>/tickets">Tickets</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="<?= BASE_URL ?>/tickets/create">Crear ticket</a>
          </li>

      <?php if (($user['role'] ?? '') === 'admin'): ?>
          <li class="nav-item">
            <a class="nav-link" href="<?= BASE_URL ?>/users">Usuarios</a>
          </li>
        <?php endif; ?>
        
        </ul>
      <?php endif; ?>

      <div class="d-flex align-items-center gap-3 ms-auto">
        <?php if ($user): ?>
          <span class="text-white-50 small">
            <?= htmlspecialchars($user['name'] ?? $user['email']) ?> (<?= htmlspecialchars($user['role'] ?? 'user') ?>)
          </span>
          <a class="btn btn-outline-light btn-sm" href="<?= BASE_URL ?>/logout">Cerrar sesión</a>
        <?php else: ?>
          <a class="btn btn-outline-light btn-sm" href="<?= BASE_URL ?>/login">Login</a>
        <?php endif; ?>

      </div>
    </div>
  </div>
</nav>
<main class="container py-4">
