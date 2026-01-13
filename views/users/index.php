<?php
$title = 'Usuarios';
require __DIR__ . '/../layouts/header.php';
?>

<div class="d-flex justify-content-between align-items-center mb-3">
  <h1 class="h4 mb-0">Usuarios</h1>
  <a class="btn btn-primary" href="<?= BASE_URL ?>/admin/users/create">Crear usuario</a>
</div>

<?php if (!empty($_SESSION['flash_success'])): ?>
  <div class="alert alert-success"><?= $_SESSION['flash_success']; unset($_SESSION['flash_success']); ?></div>
<?php endif; ?>

<?php if (!empty($_SESSION['flash_error'])): ?>
  <div class="alert alert-danger"><?= $_SESSION['flash_error']; unset($_SESSION['flash_error']); ?></div>
<?php endif; ?>

<div class="card shadow-sm">
  <div class="table-responsive">
    <table class="table mb-0">
      <thead>
        <tr>
          <th>#</th><th>Nombre</th><th>Email</th><th>Rol</th><th>Activo</th><th class="text-end">Acciones</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($users as $u): ?>
          <tr>
            <td><?= (int)$u['id'] ?></td>
            <td><?= htmlspecialchars($u['name']) ?></td>
            <td><?= htmlspecialchars($u['email']) ?></td>
            <td><?= htmlspecialchars($u['role']) ?></td>
            <td><?= ((int)$u['active'] === 1) ? 'Sí' : 'No' ?></td>
            <td class="text-end">
              <?php if ((int)$u['id'] !== (int)($_SESSION['user']['id'] ?? 0)): ?>

                <form method="post" action="<?= BASE_URL ?>/admin/users/toggle" class="d-inline">
                  <input type="hidden" name="id" value="<?= (int)$u['id'] ?>">
                  <input type="hidden" name="active" value="<?= ((int)$u['active'] === 1) ? 0 : 1 ?>">
                  <button class="btn btn-sm btn-outline-secondary">
                    <?= ((int)$u['active'] === 1) ? 'Desactivar' : 'Activar' ?>
                  </button>
                </form>

                <form method="post" action="<?= BASE_URL ?>/admin/users/delete" class="d-inline">
                  <input type="hidden" name="id" value="<?= (int)$u['id'] ?>">
                  <button class="btn btn-sm btn-outline-danger"
                          onclick="return confirm('¿Eliminar? Si tiene tickets se desactivará.')">
                    Eliminar
                  </button>
                </form>

              <?php else: ?>
                <span class="text-muted small">Tu usuario</span>
              <?php endif; ?>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>

<?php require __DIR__ . '/../layouts/footer.php'; ?>
