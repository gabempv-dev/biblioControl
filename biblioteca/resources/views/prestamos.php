<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Préstamos</title>
  <link rel="stylesheet" href="css/dashboard.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.3.1/css/all.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="css/libs/ui.jqgrid-bootstrap5.css">
</head>
<body>

  <aside class="sidebar">
        <div>
            <div class="brand">
                <i class="fa-solid fa-book-bookmark"></i>
                <h2>BiblioControl</h2>
            </div>

            <nav class="sidebar-nav">
                <ul>
                    <li><a href="/dashboard"><i class="fa-solid fa-house"></i> Inicio</a></li>
                    <li><a href="/inventario"><i class="fa-solid fa-book"></i> Catálogo Libros</a></li>
                    <li><a href="/prestamos" class="active"><i class="fa-solid fa-hand-holding-hand"></i> Préstamos</a></li>
                    <li><a href="#"><i class="fa-solid fa-users"></i> Lectores</a></li>
                </ul>
            </nav>
        </div>
        
        <div class="btns-abajo">
            <a href="/logout" class="btn-sidebar cerrar_sesion"><i class="fa-solid fa-right-from-bracket"></i> Cerrar Sesión</a>
        </div>
    </aside>

    <div class="main-wrapper">
        <header class="topbar">
            <div class="user-info">
                <span>Hola, <strong id="usuario"><?= htmlspecialchars($_SESSION['user'] ?? 'Bibliotecario') ?></strong></span>
                <img src="https://objetivoligar.com/wp-content/uploads/2017/03/blank-profile-picture-973460_1280.jpg" alt="Foto de Perfil">
            </div>
        </header>

    <main class="workspace">
      <h1 class="page-title">Administración de Préstamos</h1>

      <div class="mb-3 d-flex gap-2">
        <button type="button" id="btnNuevoPrestamo" class="btn-disponible">
          <i class="fa-solid fa-plus"></i> Nuevo Préstamo
        </button>
        <button type="button" id="btnDevolverPrestamo" class="btn-disponible">
          <i class="fa-solid fa-rotate-left"></i> Marcar Devuelto
        </button>
        <button type="button" id="btnEliminarPrestamo" class="btn-eliminar">
          <i class="fa-solid fa-trash"></i> Eliminar
        </button>
      </div>

      <table id="prestamos"></table>
      <div id="navprestamos"></div>
    </main>

  </div>

  <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasPrestamo" aria-labelledby="offcanvasPrestamoTitulo">
    <div class="offcanvas-header">
      <h5 class="offcanvas-title" id="offcanvasPrestamoTitulo">Nuevo Préstamo</h5>
      <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body">
      <form id="formPrestamo">

        <div id="formPrestamoError" class="alert alert-danger d-none"></div>

        <div class="mb-2">
          <label for="id_libro" class="form-label">Libro *</label>
          <select class="form-select" id="id_libro" name="id_libro" required>
            <option value="">Selecciona un libro disponible</option>
          </select>
        </div>

        <div class="mb-2">
          <label for="nombre_lector" class="form-label">Nombre del Lector *</label>
          <input type="text" class="form-control" id="nombre_lector" name="nombre_lector" maxlength="150" required>
        </div>

        <div class="row">
          <div class="col-6 mb-2">
            <label for="tipo_identificacion" class="form-label">Tipo de Identificación *</label>
            <select class="form-select" id="tipo_identificacion" name="tipo_identificacion" required>
              <option value="">Selecciona</option>
              <option value="DUI">DUI</option>
              <option value="Carnet Estudiantil">Carnet Estudiantil</option>
              <option value="Pasaporte">Pasaporte</option>
              <option value="Otro">Otro</option>
            </select>
          </div>
          <div class="col-6 mb-2">
            <label for="numero_documento" class="form-label">N.º de Documento *</label>
            <input type="text" class="form-control" id="numero_documento" name="numero_documento" maxlength="50" required>
          </div>
        </div>

        <div class="mb-3">
          <label for="fecha_devolucion" class="form-label">Fecha Límite de Devolución *</label>
          <input type="date" class="form-control" id="fecha_devolucion" name="fecha_devolucion" required>
        </div>

        <div class="d-flex gap-2">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="offcanvas">Cancelar</button>
          <button type="submit" class="btn btn-primary">Guardar</button>
        </div>

      </form>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
  <script src="js/libs/jquery.min.js"></script>
  <script src="js/libs/jquery.jqgrid.min.js"></script>
  <script src="js/libs/grid.locale-es.js"></script>
  <script src="js/views/app.js"></script>
  <script src="js/views/prestamos.js"></script>
</body>
</html>