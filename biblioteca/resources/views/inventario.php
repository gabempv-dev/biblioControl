<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Inventario</title>
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
                    <li><a href="/inventario" class="active"><i class="fa-solid fa-book"></i> Catálogo Libros</a></li>
                    <li><a href="#"><i class="fa-solid fa-hand-holding-hand"></i> Préstamos</a></li>
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
      <h1 class="page-title">Administración de Inventario</h1>

      <div class="mb-3 d-flex gap-2">
        <button type="button" id="btnNuevoLibro" class="btn-disponible">
          <i class="fa-solid fa-plus"></i> Nuevo Libro
        </button>
        <button type="button" id="btnEditarLibro" class="btn-disponible">
          <i class="fa-solid fa-pen"></i> Editar
        </button>
        <button type="button" id="btnEliminarLibro" class="btn-eliminar">
          <i class="fa-solid fa-trash"></i> Eliminar
        </button>
      </div>

      <table id="inventario"></table>
      <div id="navinventario"></div>
    </main>

  </div>

  <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasLibro" aria-labelledby="offcanvasLibroTitulo">
    <div class="offcanvas-header">
      <h5 class="offcanvas-title" id="offcanvasLibroTitulo">Nuevo Libro</h5>
      <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body">
      <form id="formLibro">

        <div id="formLibroError" class="alert alert-danger d-none"></div>

        <input type="hidden" id="id_libro" name="id_libro">

        <div class="mb-2">
          <label for="isbn" class="form-label">ISBN</label>
          <input type="text" class="form-control" id="isbn" name="isbn" maxlength="20">
        </div>

        <div class="mb-2">
          <label for="titulo" class="form-label">Título *</label>
          <input type="text" class="form-control" id="titulo" name="titulo" maxlength="200" required>
        </div>

        <div class="row">
          <div class="col-6 mb-2">
            <label for="id_categoria" class="form-label">Categoría *</label>
            <div class="input-group">
              <select class="form-select" id="id_categoria" name="id_categoria" required>
                <option value="">Selecciona una categoría</option>
              </select>
              <button class="btn btn-outline-secondary" type="button" id="btnNuevaCategoria" title="Agregar categoría nueva">
              </button>
            </div>
            <div id="cajaNuevaCategoria" class="input-group input-group-sm mt-2 d-none">
              <input type="text" class="form-control" id="nuevaCategoriaInput" placeholder="Nombre de la categoría">
              <button class="btn btn-success" type="button" id="btnGuardarCategoria">Agregar</button>
            </div>
          </div>
          <div class="col-6 mb-2">
            <label for="autor" class="form-label">Autor *</label>
            <input type="text" class="form-control" id="autor" name="autor" maxlength="150"
                   placeholder="Escribe el nombre del autor" list="listaAutores" required>
            <datalist id="listaAutores"></datalist>
          </div>
        </div>

        <div class="mb-2">
          <label for="editorial" class="form-label">Editorial</label>
          <input type="text" class="form-control" id="editorial" name="editorial" maxlength="150">
        </div>

        <div class="row">
          <div class="col-6 mb-2">
            <label for="cantidad_paginas" class="form-label">Páginas</label>
            <input type="number" class="form-control" id="cantidad_paginas" name="cantidad_paginas" min="0">
          </div>
          <div class="col-6 mb-2">
            <label for="tomo" class="form-label">Tomo</label>
            <input type="text" class="form-control" id="tomo" name="tomo" maxlength="50">
          </div>
        </div>

        <div class="form-check mb-3">
          <input type="checkbox" class="form-check-input" id="disponible" name="disponible" checked>
          <label for="disponible" class="form-check-label">Disponible</label>
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
  <script src="js/views/inventario.js"></script>
</body>
</html>