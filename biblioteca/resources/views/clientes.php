<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Clientes</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.3.1/css/all.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="css/libs/ui.jqgrid-bootstrap5.css">
  <link rel="stylesheet" href="css/views/app.css">
</head>
<body>

  <aside class="sidebar">
    <div>
      <a href="#" class="brand-logo">
        <span class="logo-icon">&lt;/&gt;</span>
        <span>SRS System</span>
      </a>

      <nav class="menu-container">
        <div class="menu-label">Navegación</div>
        <ul class="nav-list">
          
          <li>
            <a href="#" class="nav-link active">
              <div class="nav-item-content">
                <span>📊</span>
                <span>Dashboard</span>
              </div>
            </a>
          </li>

          <li class="has-submenu">
            <div class="nav-link">
              <div class="nav-item-content">
                <span>📦</span>
                <span>Proyectos</span>
              </div>
              <span class="chevron">▶</span>
            </div>
            <ul class="submenu">
              <li><a href="#" class="nav-link">Administración de Proyectos</a></li>
              <li><a href="#" class="nav-link">Requerimientos Funcionales</a></li>
              <li><a href="#" class="nav-link">Requerimientos No Funcionales</a></li>
            </ul>
          </li>

          <li class="has-submenu">
            <div class="nav-link">
              <div class="nav-item-content">
                <span>👥</span>
                <span>Usuarios</span>
              </div>
              <span class="chevron">▶</span>
            </div>
            <ul class="submenu">
              <li><a href="#" class="nav-link">Administración de Usuarios</a></li>
              <li><a href="#" class="nav-link">Permisos y Roles</a></li>
            </ul>
          </li>

          <li>
            <a href="#" class="nav-link">
              <div class="nav-item-content">
                <span>⚙️</span>
                <span>Configuración</span>
              </div>
            </a>
          </li>

        </ul>
      </nav>
    </div>

    <div>
      <a href="/logout" class="nav-link" style="color: #ef4444;">
        <div class="nav-item-content">
          <span>🚪</span>
          <span>Cerrar Sesión</span>
        </div>
      </a>
    </div>
  </aside>

  <div class="main-wrapper">
    
    <header class="topbar">
      <div class="search-box">
        <span class="search-icon">🔍</span>
        <input type="text" placeholder="Buscar módulo o registro...">
      </div>

      <div class="user-profile">
        <div class="user-info">
          <div class="user-name"><?= $_SESSION['user'] ?></div>
          <div class="user-role">ejemplo@estudiante.edu</div>
        </div>
        <div class="user-avatar">CM</div>
      </div>
    </header>

    <main class="workspace">
      <h1 class="page-title">Administración de Clientes</h1>

      <table id="clientes"></table>
      <div id="navclientes"></div>
    </main>

  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
  <script src="js/libs/jquery.min.js"></script>
  <script src="js/libs/grid.locale-es.js"></script>
  <script src="js/libs/jquery.jqgrid.min.js"></script>
  <script src="js/views/app.js"></script>
  <script src="js/views/clientes.js"></script>
</body>
</html>