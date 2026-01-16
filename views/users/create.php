<?php
$title = 'Crear usuario';
require __DIR__ . '/../layouts/header.php';
?>

<h1 class="h4 mb-3">Crear usuario</h1>

<?php if (!empty($_SESSION['flash_error'])): ?>
  <div class="alert alert-danger">
    <?= $_SESSION['flash_error']; unset($_SESSION['flash_error']); ?>
  </div>
<?php endif; ?>

<div class="card shadow-sm">
  <div class="card-body">
    <form method="post" action="<?= BASE_URL ?>/users/create">
      <div class="mb-3">
        <label class="form-label">Nombre</label>
        <input class="form-control" name="name" required>
      </div>

      <div class="mb-3">
        <label class="form-label">Email</label>
        <input class="form-control" name="email" type="email" required>
      </div>

      <div class="mb-3">
        <label class="form-label">Contraseña</label>
        <input class="form-control" name="password" type="password" required>
      </div>

      <div class="mb-3">
        <label class="form-label">Rol</label>
        <select class="form-select" name="role">
          <option value="user">Usuario</option>
          <option value="admin">Administrador</option>
        </select>
      </div>

      <div class="d-flex gap-2 justify-content-end">
        <a class="btn btn-outline-secondary" href="<?= BASE_URL ?>/users">Volver</a>
        <button class="btn btn-primary" type="submit">Crear</button>
      </div>
    </form>
  </div>
</div>

<?php require __DIR__ . '/../layouts/footer.php'; ?>
