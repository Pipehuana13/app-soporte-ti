<div class="d-flex justify-content-between align-items-center mb-3">
  <h4 class="mb-0">Tickets</h4>
  <a class="btn btn-success" href="/tickets/create">Crear ticket</a>
</div>

<div class="card">
  <div class="table-responsive">
    <table class="table table-striped mb-0">
      <thead>
        <tr>
          <th>#</th><th>Título</th><th>Estado</th><th>Prioridad</th><th>Creado</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($tickets as $t): ?>
          <tr>
            <td><?= (int)$t['id'] ?></td>
            <td><a href="/tickets/show?id=<?= (int)$t['id'] ?>"><?= htmlspecialchars($t['title']) ?></a></td>
            <td><?= htmlspecialchars($t['status']) ?></td>
            <td><?= htmlspecialchars($t['priority']) ?></td>
            <td><?= htmlspecialchars($t['created_at']) ?></td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>
