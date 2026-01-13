<?php
$title = 'Tickets';
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

function priorityBadge(string $priority): string {
    return match ($priority) {
        'alta'  => '<span class="badge text-bg-danger">Alta</span>',
        'media' => '<span class="badge text-bg-primary">Media</span>',
        'baja'  => '<span class="badge text-bg-info">Baja</span>',
        default => '<span class="badge text-bg-light">N/D</span>',
    };
}
?>

<div class="d-flex justify-content-between align-items-center mb-3">
  <h1 class="h4 mb-0">Tickets</h1>
  <a class="btn btn-primary" href="<?= BASE_URL ?>/tickets/create">Nuevo ticket</a>
</div>

<div class="card shadow-sm">
  <div class="table-responsive">
    <table class="table table-hover align-middle mb-0">
      <thead class="table-light">
        <tr>
          <th style="width:70px">#</th>
          <th>Título</th>
          <th style="width:140px">Estado</th>
          <th style="width:140px">Prioridad</th>
          <?php if ($isAdmin): ?>
            <th style="width:220px">Usuario</th>
          <?php endif; ?>
          <th style="width:180px">Fecha</th>
        </tr>
      </thead>
      <tbody>
        <?php if (empty($tickets)): ?>
          <tr>
            <td colspan="<?= $isAdmin ? 6 : 5 ?>" class="text-center text-muted py-4">
              No hay tickets aún.
            </td>
          </tr>
        <?php else: ?>
          <?php foreach ($tickets as $t): ?>
            <tr>
              <td><?= (int)$t['id'] ?></td>

              <td>
                <a class="text-decoration-none fw-semibold"
                   href="<?= BASE_URL ?>/tickets/<?= (int)$t['id'] ?>">
                  <?= htmlspecialchars($t['title']) ?>
                </a>
                <div class="text-muted small">
                  <?= htmlspecialchars(mb_strimwidth($t['description'] ?? '', 0, 80, '...')) ?>
                </div>
              </td>

              <td><?= statusBadge((string)$t['status']) ?></td>
              <td><?= priorityBadge((string)$t['priority']) ?></td>

              <?php if ($isAdmin): ?>
                <td><?= htmlspecialchars($t['user_name'] ?? '') ?></td>
              <?php endif; ?>

              <td class="text-muted small"><?= htmlspecialchars($t['created_at']) ?></td>
            </tr>
          <?php endforeach; ?>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>

<?php require __DIR__ . '/../layouts/footer.php'; ?>
