<h1>Dashboard</h1>

<p>Hola <?= htmlspecialchars($_SESSION['user']['name'] ?? $_SESSION['user']['email']) ?></p>

<a href="/logout">Cerrar sesión</a>
