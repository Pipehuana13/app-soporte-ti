<?php
$title = 'Login';
require __DIR__ . '/../layouts/header.php';
?>

<div class="row justify-content-center">
  <div class="col-12 col-md-6 col-lg-4">
    <div class="card shadow-sm">
      <div class="card-body">
        <h1 class="h4 mb-3">Login</h1>

        <?php if (!empty($error)): ?>
          <div class="alert alert-danger py-2"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <form method="post" action="<?= BASE_URL ?>/login">
          <div class="mb-3">
            <label class="form-label">Email</label>
            <input class="form-control" type="email" name="email" required>
          </div>

          <div class="mb-3">
            <label class="form-label">Contraseña</label>
            <input class="form-control" type="password" name="password" required>
          </div>

          <button class="btn btn-primary w-100" type="submit">Entrar</button>
        </form>
      </div>
    </div>
  </div>
</div>

<?php require __DIR__ . '/../layouts/footer.php'; ?>
