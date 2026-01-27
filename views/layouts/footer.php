</main>

<footer class="border-top py-3 bg-white">
  <div class="container small text-muted">
    <?= date('Y') ?> · App Soporte TI
  </div>
</footer>

<script>
  // 1) Pedir permiso solo una vez
  if ("Notification" in window) {
    if (Notification.permission === "default") {
      Notification.requestPermission();
    }
  }

  // 2) Mostrar notificación si viene "flash" desde PHP
  <?php if (!empty($_SESSION['notify'])): ?>
    (function () {
      const n = <?= json_encode($_SESSION['notify'], JSON_UNESCAPED_UNICODE) ?>;

      if ("Notification" in window && Notification.permission === "granted") {
        new Notification(n.title, { body: n.body });
      }
    })();
  <?php unset($_SESSION['notify']); endif; ?>
</script>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
