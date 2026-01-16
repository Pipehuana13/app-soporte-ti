<?php
$title = 'Usuarios';
require __DIR__ . '/../layouts/header.php';
?>

<div class="d-flex justify-content-between align-items-center mb-3">
  <h1 class="h4 mb-0">Usuarios</h1>
  <a class="btn btn-primary" href="<?= BASE_URL ?>/users/create">Nuevo usuario</a>
</div>

<?php if (!empty($_SESSION['flash_error'])): ?>
  <div class="alert alert-danger">
    <?= $_SESSION['flash_error']; unset($_SESSION['flash_error']); ?>
  </div>
<?php endif; ?>

<?php if (!empty($_SESSION['flash_success'])): ?>
  <div class="alert alert-success">
    <?= $_SESSION['flash_success']; unset($_SESSION['flash_success']); ?>
  </div>
<?php endif; ?>

<div class="card shadow-sm">
  <div class="table-responsive">
    <table class="table table-hover align-middle mb-0">
      <thead class="table-light">
        <tr>
          <th>#</th>
          <th>Nombre</th>
          <th>Email</th>
          <th>Rol</th>
          <th>Activo</th>
          <th style="width:140px"></th>
        </tr>
      </thead>
      <tbody>
        <?php if (empty($users)): ?>
          <tr>
            <td colspan="6" class="text-center text-muted py-4">No hay usuarios.</td>
          </tr>
        <?php else: ?>
          <?php foreach ($users as $u): ?>
            <tr>
              <td><?= (int)$u['id'] ?></td>
              <td><?= htmlspecialchars($u['name']) ?></td>
              <td><?= htmlspecialchars($u['email']) ?></td>
              <td><span class="badge text-bg-dark"><?= htmlspecialchars($u['role']) ?></span></td>
              <td><?= ((int)$u['active'] === 1) ? 'Sí' : 'No' ?></td>
              <td class="text-end">
                <form method="post" action="<?= BASE_URL ?>/users/delete"
                      onsubmit="return confirm('¿Eliminar este usuario?');">
                  <input type="hidden" name="id" value="<?= (int)$u['id'] ?>">
                  <button class="btn btn-sm btn-danger" type="submit">Eliminar</button>
                </form>
              </td>
            </tr>
          <?php endforeach; ?>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>

<?php require __DIR__ . '/../layouts/footer.php'; ?>
