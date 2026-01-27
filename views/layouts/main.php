<?php use App\Core\Auth; Auth::start(); $u = Auth::user(); ?>
<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Soporte TI</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<nav class="navbar navbar-expand-lg bg-white border-bottom">
  <div class="container">
    <a class="navbar-brand" href="/tickets">Soporte TI</a>
    <div class="ms-auto">
      <?php if ($u): ?>
        <span class="me-3"><?= htmlspecialchars($u['name']) ?> (<?= $u['role'] ?>)</span>
        <a class="btn btn-outline-secondary btn-sm" href="/logout">Salir</a>
      <?php endif; ?>
    </div>
  </div>
</nav>

<script>
if ("Notification" in window) {
    if (Notification.permission === "default") {
        Notification.requestPermission();
    }
}
</script>


<main class="container py-4">
  <?php require $viewFile; ?>
</main>
</body>
</html>
