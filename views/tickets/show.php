<div class="card">
  <div class="card-body">
    <div class="d-flex justify-content-between">
      <h5 class="mb-1">#<?= (int)$ticket['id'] ?> - <?= htmlspecialchars($ticket['title']) ?></h5>
      <span class="badge text-bg-secondary"><?= htmlspecialchars($ticket['status']) ?></span>
    </div>

    <div class="text-muted mb-3">
      Categoría: <?= htmlspecialchars($ticket['category']) ?> · Prioridad: <?= htmlspecialchars($ticket['priority']) ?> · Creado: <?= htmlspecialchars($ticket['created_at']) ?>
    </div>

    <p class="mb-0"><?= nl2br(htmlspecialchars($ticket['description'])) ?></p>
  </div>
</div>
