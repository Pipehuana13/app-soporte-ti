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

<?php if (!empty($_SESSION['flash_error'])): ?>
  <div class="alert alert-danger">
    <?= $_SESSION['flash_error']; unset($_SESSION['flash_error']); ?>
  </div>
<?php endif; ?>

<div class="card mt-3">
  <div class="card-header">Comentarios</div>
  <div class="card-body">

    <?php if (($ticket['status'] ?? '') !== 'cerrado'): ?>
      <form method="post" action="<?= BASE_URL ?>/tickets/comment" class="mb-3">
        <input type="hidden" name="ticket_id" value="<?= (int)$ticket['id'] ?>">
        <textarea name="comment"
                  class="form-control"
                  rows="3"
                  placeholder="Escribe un comentario..."
                  required></textarea>
        <div class="mt-2 d-flex justify-content-end">
          <button class="btn btn-primary btn-sm" type="submit">Agregar comentario</button>
        </div>
      </form>
    <?php else: ?>
      <div class="alert alert-secondary mb-3">
        Este ticket está cerrado. No se pueden agregar comentarios.
      </div>
    <?php endif; ?>

    <?php if (empty($comments)): ?>
      <div class="text-muted">Sin comentarios aún.</div>
    <?php else: ?>
      <?php foreach ($comments as $c): ?>
        <div class="border rounded p-2 mb-2">
          <div class="small text-muted">
            <?= htmlspecialchars($c['created_at']) ?> — <?= htmlspecialchars($c['user_name'] ?? 'N/D') ?>
          </div>
          <div><?= nl2br(htmlspecialchars($c['comment'])) ?></div>
        </div>
      <?php endforeach; ?>
    <?php endif; ?>

  </div>
</div>


<?php require __DIR__ . '/../layouts/footer.php'; ?>
