<?php
$title = 'Ticket #' . (int)$ticket['id'];
require __DIR__ . '/../layouts/header.php';

$isAdmin = (($_SESSION['user']['role'] ?? '') === 'admin');

function statusBadge(string $status): string {
    return match ($status) {
        'abierto'     => '<span class="badge text-bg-success">Abierto</span>',
        'en_proceso'  => '<span class="badge text-bg-warning">En proceso</span>',
        'cerrado'     => '<span class="badge text-bg-secondary">Cerrado</span>',
        default       => '<span class="badge text-bg-light">N/D</span>',
    };
}
?>

<div class="card shadow-sm">
  <div class="card-body">
    <div class="d-flex justify-content-between align-items-start gap-3">
      <div>
        <h1 class="h5 mb-2"><?= htmlspecialchars($ticket['title']) ?></h1>
        <div class="mb-3"><?= statusBadge((string)$ticket['status']) ?></div>
      </div>

      <a class="btn btn-outline-secondary btn-sm" href="<?= BASE_URL ?>/tickets">Volver</a>
    </div>

    <p class="mb-3"><?= nl2br(htmlspecialchars($ticket['description'])) ?></p>

    <p class="text-muted small mb-4">
      Creado por <strong><?= htmlspecialchars($ticket['user_name']) ?></strong>
      · <?= htmlspecialchars((string)$ticket['created_at']) ?>
    </p>

    <?php if ($isAdmin): ?>
      <form method="post" action="<?= BASE_URL ?>/tickets/status" class="d-flex gap-2">
        <input type="hidden" name="id" value="<?= (int)$ticket['id'] ?>">

        <button class="btn btn-outline-success btn-sm" name="status" value="abierto">
          Abrir
        </button>

        <button class="btn btn-outline-warning btn-sm" name="status" value="en_proceso">
          En proceso
        </button>

        <button class="btn btn-outline-secondary btn-sm" name="status" value="cerrado"
                onclick="return confirm('¿Cerrar este ticket?')">
          Cerrar
        </button>
      </form>
    <?php endif; ?>
  </div>
</div>

<?php require __DIR__ . '/../layouts/footer.php'; ?>
