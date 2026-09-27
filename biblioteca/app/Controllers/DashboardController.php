<?php
namespace App\Controllers;

use App\Models\InventarioModel;

class DashboardController extends Controller{
    protected InventarioModel $model;

    public function __construct(){
        session_start();
        $this->model = new InventarioModel;
    }
    public function index(){
        return $this->view('dashboard',[
            'title' => 'Dashboard'
        ]);
    }

    public function tl(){
        // Evita que warnings/notices previos (HTML) corrompan el JSON de salida
        if (ob_get_length()) {
            ob_clean();
        }

        echo json_encode([
            'total_libros'       => (int) $this->model->totalLibros(),
            'total_prestados'    => (int) $this->model->totalPrestados(),
            'total_lectores'     => (int) $this->model->totalLectores(),
            'prestamos_vencidos' => (int) $this->model->prestamosVencidos(),
        ]);
    }
}