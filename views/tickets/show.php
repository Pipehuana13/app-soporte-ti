<?php
$title = 'Ticket #' . $ticket['id'];
require __DIR__ . '/../layouts/header.php';

$isAdmin = ($_SESSION['user']['role'] ?? '') === 'admin';
?>

<div class="card shadow-sm">
  <div class="card-body">
    <h1 class="h5 mb-3"><?= htmlspecialchars($ticket['title']) ?></h1>

    <p><?= nl2br(htmlspecialchars($ticket['description'])) ?></p>

    <p class="text-muted">
      Creado por <?= htmlspecialchars($ticket['user_name']) ?> · <?= $ticket['created_at'] ?>
    </p>

    <hr>

    <p><strong>Estado:</strong> <?= $ticket['status'] ?></p>
    <p><strong>Prioridad:</strong> <?= $ticket['priority'] ?></p>

    <?php if ($isAdmin): ?>
      <form method="post" action="<?= BASE_URL ?>/tickets/update" class="row g-2 mt-3">
        <input type="hidden" name="id" value="<?= $ticket['id'] ?>">

        <div class="col-md-4">
          <select class="form-select" name="status">
            <?php foreach (['abierto','en_proceso','cerrado'] as $s): ?>
              <option value="<?= $s ?>" <?= $ticket['status']===$s?'selected':'' ?>>
                <?= ucfirst(str_replace('_',' ', $s)) ?>
              </option>
            <?php endforeach; ?>
          </select>
        </div>

        <div class="col-md-4">
          <select class="form-select" name="priority">
            <?php foreach (['baja','media','alta'] as $p): ?>
              <option value="<?= $p ?>" <?= $ticket['priority']===$p?'selected':'' ?>>
                <?= ucfirst($p) ?>
              </option>
            <?php endforeach; ?>
          </select>
        </div>

        <div class="col-md-4">
          <button class="btn btn-primary w-100">Actualizar</button>
        </div>
      </form>
    <?php endif; ?>

    <a class="btn btn-link mt-3" href="<?= BASE_URL ?>/tickets">← Volver</a>
  </div>
</div>

<?php require __DIR__ . '/../layouts/footer.php'; ?>
