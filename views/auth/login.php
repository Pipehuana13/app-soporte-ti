<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <title>Login - Soporte TI</title>
</head>
<body>
  <h1>Login</h1>

  <?php if (!empty($error)): ?>
    <p style="color:red;"><?= htmlspecialchars($error) ?></p>
  <?php endif; ?>

  <form method="post" action="/login">
    <div>
      <label>Email</label>
      <input type="email" name="email" required>
    </div>

    <div>
      <label>Contraseña</label>
      <input type="password" name="pass" required>
    </div>

    <button type="submit">Entrar</button>
  </form>
</body>
</html>
