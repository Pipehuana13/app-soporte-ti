<?php
$title = 'Tickets';
require __DIR__ . '/../layouts/header.php';
?>

<div class="d-flex justify-content-between align-items-center mb-3">
  <h1 class="h4">Tickets</h1>
  <a class="btn btn-primary" href="<?= BASE_URL ?>/tickets/create">Nuevo ticket</a>
</div>

<div class="card shadow-sm">
  <div class="table-responsive">
    <table class="table mb-0">
      <thead>
        <tr>
          <th>#</th>
          <th>Título</th>
          <th>Estado</th>
          <th>Prioridad</th>
          <th>Usuario</th>
          <th>Fecha</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($tickets as $t): ?>
          <tr>
            <td><?= $t['id'] ?></td>
            <td><?= htmlspecialchars($t['title']) ?></td>
            <td><?= $t['status'] ?></td>
            <td><?= $t['priority'] ?></td>
            <td><?= htmlspecialchars($t['user_name']) ?></td>
            <td><?= $t['created_at'] ?></td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>

<?php require __DIR__ . '/../layouts/footer.php'; ?>
