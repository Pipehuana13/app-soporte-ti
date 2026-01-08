<div class="card">
  <div class="card-body">
    <h5 class="mb-3">Nuevo ticket</h5>

    <form method="post" action="/tickets/store">
      <input type="hidden" name="csrf" value="<?= htmlspecialchars($csrf) ?>">

      <div class="mb-3">
        <label class="form-label">Título</label>
        <input class="form-control" name="title" required maxlength="180">
      </div>

      <div class="mb-3">
        <label class="form-label">Descripción</label>
        <textarea class="form-control" name="description" rows="5" required></textarea>
      </div>

      <div class="row">
        <div class="col-md-6 mb-3">
          <label class="form-label">Categoría</label>
          <select class="form-select" name="category">
            <option>Hardware</option><option>Software</option><option>Red</option><option>Accesos</option><option selected>Otros</option>
          </select>
        </div>
        <div class="col-md-6 mb-3">
          <label class="form-label">Prioridad</label>
          <select class="form-select" name="priority">
            <option>Baja</option><option selected>Media</option><option>Alta</option><option>Crítica</option>
          </select>
        </div>
      </div>

      <button class="btn btn-primary">Crear</button>
      <a class="btn btn-link" href="/tickets">Cancelar</a>
    </form>
  </div>
</div>
