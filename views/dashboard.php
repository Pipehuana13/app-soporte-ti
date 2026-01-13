<?php
$title = 'Dashboard';
require __DIR__ . '/layouts/header.php';
?>

<div class="card shadow-sm">
  <div class="card-body">
    <h1 class="h4 mb-2">Dashboard</h1>
    <p class="mb-0">
      Bienvenido <?= htmlspecialchars($_SESSION['user']['name'] ?? $_SESSION['user']['email']) ?>
    </p>
  </div>
</div>

<?php require __DIR__ . '/layouts/footer.php'; ?>
