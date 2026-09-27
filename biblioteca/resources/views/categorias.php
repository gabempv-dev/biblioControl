<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Categorías - BiblioControl</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="/css/libs/ui.jqgrid-bootstrap5.css">
    <link rel="stylesheet" href="/css/dashboard.css">
    <link rel="stylesheet" href="/css/inventario.css">
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
                    <li><a href="/categorias" class="active"><i class="fa-solid fa-cat"></i> Categorias</a></li>
                    <li><a href="/inventario"><i class="fa-solid fa-book"></i> Catálogo Libros</a></li>
                    <li><a href="#"><i class="fa-solid fa-hand-holding-hand"></i> Préstamos</a></li>
                    <li><a href="#"><i class="fa-solid fa-users"></i> Lectores</a></li>

                    <li class="has-submenu">
                        <a href="#" class="nav-parent">
                            <span class="nav-parent-label"><i class="fa-solid fa-file-lines"></i> Informes</span>
                            <i class="fa-solid fa-chevron-down submenu-chevron"></i>
                        </a>
                        <ul class="submenu">
                            <li><a href="/informes/inventario" target="_blank"><i class="fa-solid fa-boxes-stacked"></i> Inventario de Libros</a></li>
                            <li><a href="/informes/categorias" target="_blank"><i class="fa-solid fa-tags"></i> Categorías</a></li>
                            <li><a href="/informes/autores" target="_blank"><i class="fa-solid fa-user-pen"></i> Autores</a></li>
                        </ul>
                    </li>
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
            <h1 class="page-title">Administración de Categorías</h1>

            <div class="mb-3 d-flex gap-2">
                <button type="button" id="btnNuevaCategoria" class="btn-disponible">
                    <i class="fa-solid fa-plus"></i> Nueva Categoría
                </button>
                <button type="button" id="btnEditarCategoria" class="btn-disponible">
                    <i class="fa-solid fa-pen"></i> Editar
                </button>
                <button type="button" id="btnEliminarCategoria" class="btn-eliminar">
                    <i class="fa-solid fa-trash"></i> Eliminar
                </button>
            </div>

            <table id="categorias"></table>
            <div id="navcategorias"></div>
        </main>
    </div>

    <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasCategoria" aria-labelledby="offcanvasCategoriaTitulo">
        <div class="offcanvas-header">
            <h5 class="offcanvas-title" id="offcanvasCategoriaTitulo">Nueva Categoría</h5>
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body">
            <form id="formCategoria">

                <div id="formCategoriaError" class="alert alert-danger d-none"></div>

                <input type="hidden" id="id_categoria" name="id_categoria">

                <div class="mb-3">
                    <label for="categoria" class="form-label">Nombre de la categoría *</label>
                    <input type="text" class="form-control" id="categoria" name="categoria" maxlength="100" required>
                </div>

                <div class="d-flex gap-2">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="offcanvas">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Guardar</button>
                </div>

            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
    <script src="/js/libs/jquery.min.js"></script>
    <script src="/js/libs/jquery.jqGrid.min.js"></script>
    <script src="/js/libs/grid.locale-es.js"></script>
    <script src="/js/views/app.js"></script>
    <script src="/js/views/categoria.js"></script>
    <script src="/js/views/sidebar.js"></script>
</body>
</html>