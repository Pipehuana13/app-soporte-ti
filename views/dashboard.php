<?php
$title = 'Dashboard';
require __DIR__ . '/layouts/header.php';
?>

<div class="row g-3">
  <div class="col-12">
    <div class="card shadow-sm">
      <div class="card-body">
        <h1 class="h4 mb-2">Dashboard</h1>
        <p class="mb-0">Bienvenido <?= htmlspecialchars($_SESSION['user']['name'] ?? $_SESSION['user']['email']) ?></p>
      </div>
    </div>
  </div>

  <div class="col-12 col-md-4">
    <a class="text-decoration-none" href="<?= BASE_URL ?>/tickets">
      <div class="card shadow-sm">
        <div class="card-body">
          <div class="fw-semibold">Ver tickets</div>
          <div class="text-muted small">Listado de solicitudes</div>
        </div>
      </div>
    </a>
  </div>

  <div class="col-12 col-md-4">
    <a class="text-decoration-none" href="<?= BASE_URL ?>/tickets/create">
      <div class="card shadow-sm">
        <div class="card-body">
          <div class="fw-semibold">Crear ticket</div>
          <div class="text-muted small">Nueva solicitud de soporte</div>
        </div>
      </div>
    </a>
  </div>

  <div class="col-12 col-md-4">
    <a class="text-decoration-none" href="<?= BASE_URL ?>/logout">
      <div class="card shadow-sm">
        <div class="card-body">
          <div class="fw-semibold">Cerrar sesión</div>
          <div class="text-muted small">Salir del sistema</div>
        </div>
      </div>
    </a>
  </div>
</div>

<?php require __DIR__ . '/layouts/footer.php'; ?>
