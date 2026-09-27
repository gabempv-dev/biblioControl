<?php
use Lib\Route;
use App\Controllers\HomeController;
use App\Controllers\LoginController;
use App\Controllers\DashboardController;
use App\Controllers\ClientesController;
use App\Controllers\InventarioController;
use App\Controllers\CategoriasController;
use App\Controllers\InformesController;
use App\Controllers\PrestamosController;

Route::get('/', [HomeController::class, 'index']);

Route::get('/login', [LoginController::class, 'index']);

Route::post('/login/verificar', [LoginController::class, 'verificar']);
Route::get('/logout', [LoginController::class, 'cerrar_sesion']);
Route::get('/dashboard', [DashboardController::class, 'index']);

// CRUD Clientes
Route::get('/clientes', [ClientesController::class, 'index']);
Route::post('/clientes/obtener_clientes', [ClientesController::class, 'obtener_clientes']);

// CRUD Inventario
Route::get('/inventario', [InventarioController::class, 'index']);
Route::post('/inventario/obtener_libros', [InventarioController::class, 'obtener_libros']);
Route::post('/inventario/guardar', [InventarioController::class, 'guardar_libro']);
Route::post('/inventario/actualizar', [InventarioController::class, 'actualizar_libro']);
Route::post('/inventario/eliminar', [InventarioController::class, 'eliminar_libro']);
Route::get('/inventario/categorias', [InventarioController::class, 'obtener_categorias']);
Route::get('/inventario/autores', [InventarioController::class, 'obtener_autores']);
Route::post('/inventario/categorias/guardar', [InventarioController::class, 'guardar_categoria']);

Route::get('inventario/informe', [InventarioController::class,'pdf']);

// CRUD Categorías
Route::get('/categorias', [CategoriasController::class, 'index']);
Route::post('/categorias/obtener_categorias', [CategoriasController::class, 'obtener_categorias']);
Route::post('/categorias/guardar', [CategoriasController::class, 'guardar_categoria']);
Route::post('/categorias/actualizar', [CategoriasController::class, 'actualizar_categoria']);
Route::post('/categorias/eliminar', [CategoriasController::class, 'eliminar_categoria']);

Route::get('/prestamos', [PrestamosController::class, 'index']);
Route::post('/prestamos/obtener_prestamos', [PrestamosController::class, 'obtener_prestamos']);
Route::get('/prestamos/libros_disponibles', [PrestamosController::class, 'obtener_libros_disponibles']);
Route::post('/prestamos/guardar', [PrestamosController::class, 'guardar_prestamo']);
Route::post('/prestamos/devolver', [PrestamosController::class, 'devolver_prestamo']);
Route::post('/prestamos/eliminar', [PrestamosController::class, 'eliminar_prestamo']);

// Informes en PDF (sidebar > Informes)
Route::get('/informes/inventario', [InformesController::class, 'inventario']);
Route::get('/informes/categorias', [InformesController::class, 'categorias']);
Route::get('/informes/autores', [InformesController::class, 'autores']);

Route::get('/contact', function (){
    echo "Hola desde la pagina de contacto";
});

Route::get('/about', function (){
    echo "Hola desde la pagina acerca de";
});

Route::get('/courses/:slug/:category', [HomeController::class, 'show']);

Route::dispatch();