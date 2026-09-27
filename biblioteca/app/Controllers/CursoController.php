<?php
namespace App\Controllers;

class CursoController extends Controller {
    
    public function index() {
    
        return $this->view('cursos/index', [
            'title' => 'Nuestros Cursos'
        ]);
    }

    public function show($slug) {
        return $this->view('cursos/show', [
            'title' => 'Detalle del Curso',
            'curso' => $slug
        ]);
    }
}