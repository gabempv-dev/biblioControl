<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - BiblioControl</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="/css/dashboard.css">
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
                    <li><a href="/dashboard" class="active"><i class="fa-solid fa-house"></i> Inicio</a></li>
                    <li><a href="/categorias"><i class="fa-solid fa-cat"></i> Categorias</a></li>
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

        <main class="main">
            <div class="header-title">
                <h1>Panel de Control</h1>
                <p>Resumen general del estado de la biblioteca</p>
            </div>

            <div class="cards">
                <div class="card">
                    <div class="card-icon card-icon-total"><img src="img/image.png" alt=""></div>
                    <div class="card-data">
                        <h3>Total Libros</h3>
                        <p id="total-libros">0</p>
                    </div>
                </div>

                <div class="card">
                    <div class="card-icon card-icon-prestados"><i class="fa-solid fa-book-reader"></i></div>
                    <div class="card-data">
                        <h3>Libros Prestados</h3>
                        <p id="total-prestados">0</p>
                    </div>
                </div>

                <div class="card">
                    <div class="card-icon card-icon-activos"><i class="fa-solid fa-user-check"></i></div>
                    <div class="card-data">
                        <h3>Lectores Activos</h3>
                        <p id="total-lectores">0</p>
                    </div>
                </div>

                <div class="card alerta">
                    <div class="card-icon card-icon-alerta"><i class="fa-solid fa-clock-rotate-left"></i></div>
                    <div class="card-data">
                        <h3>Préstamos Vencidos</h3>
                        <p id="prestamos-vencidos">0</p>
                    </div>
                </div>
            </div>

            <section class="table-section">
                <div class="table-header">
                    <h2>Préstamos Recientes</h2>
                </div>
                <table>
                    <thead>
                        <tr>
                            <th>Libro</th>
                            <th>Lector</th>
                            <th>Fecha Devolución</th>
                            <th>Estado</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="book-title"><i class="fa-regular fa-bookmark"></i></td>
                            <td></td>
                            <td></td>
                            <!--<td><span class="badge prestado"><i class="fa-solid fa-clock"></i> En préstamo</span></td>-->
                        </tr>
                        <tr>
                            <td class="book-title"><i class="fa-regular fa-bookmark"></i></td>
                            <td></td>
                            <td></td>
                            <!--<td><span class="badge devuelto"><i class="fa-solid fa-circle-check"></i> Devuelto</span></td>-->
                        </tr>
                        <tr>
                            <td class="book-title"><i class="fa-regular fa-bookmark"></i></td>
                            <td></td>
                            <td></td>
                            <!--<td><span class="badge vencido"><i class="fa-solid fa-triangle-exclamation"></i> Vencido</span></td>-->
                        </tr>
                    </tbody>
                </table>
            </section>
        </main>
    </div>
<script src="/js/views/sidebar.js"></script>
<script src="/js/views/dashboard.js"></script>
</body>
</html>