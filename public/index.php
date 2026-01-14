<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1"/>
  <title>To-Do App PRO</title>

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body{ background:#0b1220; }
    .card{ background: rgba(255,255,255,.03); border:1px solid rgba(255,255,255,.08); }
    .muted{ color:#9ca3af; }
    .task-done{ text-decoration: line-through; opacity:.7; }
    .pill{ border:1px solid rgba(255,255,255,.12); background: rgba(255,255,255,.03); }
  </style>
</head>
<body class="text-light">

<div class="container py-4">
  <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
    <div>
      <h1 class="h3 mb-1">✅ Gestor de Tareas PRO</h1>
      <div class="muted small">Prioridades · Fechas límite · Filtros · Estadísticas</div>
    </div>
    <button class="btn btn-outline-light" id="btnReload" type="button">Actualizar</button>
  </div>

  <!-- STATS -->
  <div class="row g-3 mb-3" id="statsRow">
    <div class="col-6 col-lg-2">
      <div class="card rounded-4 shadow-sm h-100"><div class="card-body">
        <div class="text-secondary small" >Total</div>
        <div class="h3 mb-0" id="stTotal">0</div>
      </div></div>
    </div>
    <div class="col-6 col-lg-2">
      <div class="card rounded-4 shadow-sm h-100"><div class="card-body">
        <div class="text-secondary small">Pendientes</div>
        <div class="h3 mb-0" id="stPending">0</div>
      </div></div>
    </div>
    <div class="col-6 col-lg-2">
      <div class="card rounded-4 shadow-sm h-100"><div class="card-body">
        <div class="text-secondary small">Completadas</div>
        <div class="h3 mb-0" id="stDone">0</div>
      </div></div>
    </div>
    <div class="col-6 col-lg-3">
      <div class="card rounded-4 shadow-sm h-100"><div class="card-body">
        <div class="text-secondary small">Vencidas</div>
        <div class="h3 mb-0" id="stOverdue">0</div>
        <div class="muted small">solo pendientes</div>
      </div></div>
    </div>
    <div class="col-12 col-lg-3">
      <div class="card rounded-4 shadow-sm h-100"><div class="card-body">
        <div class="text-secondary small">Para hoy</div>
        <div class="h3 mb-0" id="stToday">0</div>
        <div class="muted small">solo pendientes</div>
      </div></div>
    </div>
  </div>

  <div class="row g-3">
    <!-- FORM -->
    <div class="col-12 col-lg-5">
      <div class="card rounded-4 shadow-sm">
        <div class="card-body">
          <h2 class="h5 mb-2">Agregar tarea</h2>

          <form id="formAdd" class="row g-2">
            <div class="col-12">
              <label class="form-label">Título</label>
              <input class="form-control form-control-lg" id="title"
                     placeholder="Ej: Estudiar para el examen" maxlength="180" required>
            </div>

            <div class="col-12 col-md-6">
              <label class="form-label">Prioridad</label>
              <select class="form-select" id="priority">
                <option value="high">Alta</option>
                <option value="medium" selected>Media</option>
                <option value="low">Baja</option>
              </select>
            </div>

            <div class="col-12 col-md-6">
              <label class="form-label">Fecha límite</label>
              <input type="date" class="form-control" id="due_date">
            </div>

            <div class="col-12 d-grid mt-1">
              <button class="btn btn-success btn-lg">Agregar</button>
            </div>
          </form>

          <div class="text-secondary small mt-2" id="msg"></div>
          <div class="muted small mt-3">
            Las tareas se guardan en MySQL (y en LocalStorage como respaldo).
          </div>
        </div>
      </div>
    </div>

    <!-- LIST -->
    <div class="col-12 col-lg-7">
      <div class="card rounded-4 shadow-sm">
        <div class="card-body">

          <div class="d-flex flex-wrap gap-2 justify-content-between align-items-center mb-2">
            <h2 class="h5 mb-0">Mis tareas</h2>
            <input id="search" class="form-control" style="max-width:260px;" placeholder="Buscar...">
          </div>

          <!-- filtros -->
          <div class="row g-2 mb-3">
            <div class="col-12 col-md-4">
              <label class="form-label">Estado</label>
              <select id="f_status" class="form-select">
                <option value="all" selected>Todas</option>
                <option value="pending">Pendientes</option>
                <option value="done">Completadas</option>
              </select>
            </div>

            <div class="col-12 col-md-4">
              <label class="form-label">Prioridad</label>
              <select id="f_priority" class="form-select">
                <option value="" selected>Todas</option>
                <option value="high">Alta</option>
                <option value="medium">Media</option>
                <option value="low">Baja</option>
              </select>
            </div>

            <div class="col-12 col-md-4">
              <label class="form-label">Fecha</label>
              <select id="f_due" class="form-select">
                <option value="" selected>Todas</option>
                <option value="today">Para hoy</option>
                <option value="overdue">Vencidas</option>
                <option value="nodue">Sin fecha</option>
              </select>
            </div>
          </div>

          <div class="list-group list-group-flush" id="list"></div>

          <div class="text-secondary small mt-2" id="count"></div>
        </div>
      </div>
    </div>
  </div>
</div>

<script src="assets/app.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
