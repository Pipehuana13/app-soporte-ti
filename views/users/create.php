<?php
$title = 'Crear usuario';
require __DIR__ . '/../layouts/header.php';
?>

<div class="card shadow-sm">
  <div class="card-body">
    <h1 class="h4 mb-3">Crear usuario</h1>

    <?php if (!empty($_SESSION['flash_error'])): ?>
      <div class="alert alert-danger"><?= $_SESSION['flash_error']; unset($_SESSION['flash_error']); ?></div>
    <?php endif; ?>

    <form method="post" action="<?= BASE_URL ?>/admin/users/create">
      <div class="mb-3">
        <label class="form-label">Nombre</label>
        <input class="form-control" name="name" required>
      </div>

      <div class="mb-3">
        <label class="form-label">Email</label>
        <input class="form-control" type="email" name="email" required>
      </div>

      <div class="mb-3">
        <label class="form-label">Contraseña</label>
        <input class="form-control" type="password" name="password" required>
      </div>

      <div class="row g-2 mb-3">
        <div class="col-md-6">
          <label class="form-label">Rol</label>
          <select class="form-select" name="role">
            <option value="user">user</option>
            <option value="admin">admin</option>
          </select>
        </div>

        <div class="col-md-6">
          <label class="form-label">Activo</label>
          <select class="form-select" name="active">
            <option value="1" selected>Sí</option>
            <option value="0">No</option>
          </select>
        </div>
      </div>

      <button class="btn btn-primary">Crear</button>
      <a class="btn btn-secondary" href="<?= BASE_URL ?>/admin/users">Volver</a>
    </form>
  </div>
</div>

<?php require __DIR__ . '/../layouts/footer.php'; ?>
