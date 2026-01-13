<?php
$title = 'Nuevo Ticket';
require __DIR__ . '/../layouts/header.php';
?>

<div class="card shadow-sm">
  <div class="card-body">
    <h1 class="h4 mb-3">Crear Ticket</h1>

    <?php if (!empty($_SESSION['flash_error'])): ?>
      <div class="alert alert-danger">
        <?= $_SESSION['flash_error']; unset($_SESSION['flash_error']); ?>
      </div>
    <?php endif; ?>

    <form method="post" action="<?= BASE_URL ?>/tickets/create">
      <div class="mb-3">
        <label class="form-label">Título</label>
        <input class="form-control" name="title" required>
      </div>

      <div class="mb-3">
        <label class="form-label">Descripción</label>
        <textarea class="form-control" name="description" rows="4" required></textarea>
      </div>

      <button class="btn btn-primary">Crear</button>
      <a class="btn btn-secondary" href="<?= BASE_URL ?>/tickets">Cancelar</a>
    </form>
  </div>
</div>

<?php require __DIR__ . '/../layouts/footer.php'; ?>
