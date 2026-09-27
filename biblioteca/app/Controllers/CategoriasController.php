<?php
namespace App\Controllers;

use App\Models\Categoria;

class CategoriasController extends Controller{
    protected $model;

    public function __construct(){
        session_start();
        $this->model = new Categoria();
    }

    public function index(){
        return $this->view('categorias');
    }

    public function obtener_categorias() {
        // Variables enviadas por jqGrid
        $page = $_POST['page'];
        $limit = $_POST['rows'];
        $sidx = $_POST['sidx'];
        $sord = $_POST['sord'];

        if (!$sidx) $sidx = 1;

        // Traemos las categorías junto con la cantidad de libros que tiene cada una
        $registros = $this->model->conConteoLibros();
        $count = count($registros);

        $total_pages = $count > 0 ? ceil($count/$limit) : 0;

        if ($page > $total_pages) $page = $total_pages;
        $start = $limit * $page - $limit;
        if ($start < 0) $start = 0;

        $responce = new \stdClass();
        $responce->page = $page;
        $responce->total = $total_pages;
        $responce->records = $count;

        $i = 0;
        foreach ($registros as $categoria) {
            $responce->rows[$i]['id'] = $categoria['id_categoria'];
            $responce->rows[$i]['cell'] = [
                $categoria['categoria'],
                $categoria['total_libros'],
            ];
            $i++;
        }

        echo json_encode($responce);
    }

    public function guardar_categoria() {
        $nombre = trim($_POST['categoria'] ?? '');

        $error = $this->validar_categoria($nombre);
        if ($error) {
            echo json_encode(['success' => false, 'message' => $error]);
            return;
        }

        $categoria = $this->model->create(['categoria' => $nombre]);

        if (!$categoria) {
            echo json_encode(['success' => false, 'message' => 'No se pudo guardar la categoría.']);
            return;
        }

        echo json_encode(['success' => true, 'categoria' => $categoria]);
    }

    public function actualizar_categoria() {
        $id = $_POST['id_categoria'] ?? null;
        $nombre = trim($_POST['categoria'] ?? '');

        if (!$id) {
            echo json_encode(['success' => false, 'message' => 'No se indicó la categoría a actualizar']);
            return;
        }

        $error = $this->validar_categoria($nombre, $id);
        if ($error) {
            echo json_encode(['success' => false, 'message' => $error]);
            return;
        }

        $categoria = $this->model->update($id, ['categoria' => $nombre]);

        if (!$categoria) {
            echo json_encode(['success' => false, 'message' => 'No se pudo actualizar la categoría.']);
            return;
        }

        echo json_encode(['success' => true, 'categoria' => $categoria]);
    }

    public function eliminar_categoria() {
        $id = $_POST['id'] ?? null;

        if (!$id) {
            echo json_encode(['success' => false, 'message' => 'No se indicó la categoría a eliminar']);
            return;
        }

        // Regla de negocio: no se permite eliminar una categoría que ya tiene libros asignados
        $totalLibros = $this->model->contarLibros($id);

        if ($totalLibros > 0) {
            echo json_encode([
                'success' => false,
                'message' => "No se puede eliminar: tiene {$totalLibros} libro(s) asignado(s) a esta categoría."
            ]);
            return;
        }

        $eliminado = $this->model->delete($id);

        echo json_encode(['success' => $eliminado]);
    }

    private function validar_categoria($nombre, $excluirId = null) {
        if ($nombre === '') {
            return 'El nombre de la categoría es obligatorio';
        }
        if ($this->model->existeNombre($nombre, $excluirId)) {
            return 'Ya existe una categoría con ese nombre';
        }
        return null;
    }
}