<?php

    namespace App\Controllers;

    use App\Models\Prestamo;
    use App\Models\Ejemplar;
    use App\Models\InventarioModel;
    use App\Models\User;

    class PrestamosController extends Controller{
        protected $model;
        protected $ejemplarModel;
        protected $libroModel;
        protected $userModel;

        public function __construct() {
            session_start();
            $this->model = new Prestamo();
            $this->ejemplarModel = new Ejemplar();
            $this->libroModel = new InventarioModel();
            $this->userModel = new User();
        }

        public function index(){
            return $this->view('prestamos');
        }

        public function obtener_prestamos() {
            // Variables enviadas por jqGrid
            $page = $_POST['page'];
            $limit = $_POST['rows'];
            $sidx = $_POST['sidx'];
            $sord = $_POST['sord'];

            if (!$sidx) $sidx = 1;

            $registros = $this->model->all();
            $count = count($registros);

            $total_pages = $count > 0 ? ceil($count/$limit) : 0;

            if ($page > $total_pages) $page = $total_pages;
            $start = $limit * $page - $limit;
            if($start < 0) $start = 0;

            $responce = new \stdClass();
            $responce->page = $page;
            $responce->total = $total_pages;
            $responce->records = $count;

            $i = 0;
            foreach($registros as $prestamo) {
                $responce->rows[$i]['id'] = $prestamo['id_prestamo'];
                $responce->rows[$i]['cell'] = [
                    $prestamo['id_libro'],
                    $prestamo['titulo'],
                    $prestamo['nombre_lector'],
                    $prestamo['tipo_identificacion'],
                    $prestamo['numero_documento'],
                    $prestamo['fecha_prestamo'],
                    $prestamo['fecha_devolucion'],
                    $this->estado_prestamo($prestamo),
                    $prestamo['fecha_devolucion_real'],
                ];
                $i++;
            }

            echo json_encode($responce);
        }

        public function obtener_libros_disponibles() {
            echo json_encode(['registros' => $this->libroModel->disponiblesParaPrestamo()]);
        }

        public function guardar_prestamo() {

            $data = $this->datos_prestamo();

            $error = $this->validar_prestamo($data);
            if ($error) {
                echo json_encode(['success' => false, 'message' => $error]);
                return;
            }

            $libro = $this->libroModel->find($data['id_libro']);

            if (!$libro) {
                echo json_encode(['success' => false, 'message' => 'El libro seleccionado no existe.']);
                return;
            }

            if (!$libro['disponible']) {
                echo json_encode(['success' => false, 'message' => 'Ese libro ya no está disponible para préstamo.']);
                return;
            }

            $ejemplar = $this->ejemplarModel->obtenerOCrearPorLibro($data['id_libro']);

            $usuarioSesion = $_SESSION['user'] ?? null;
            $idUsuario = null;
            if ($usuarioSesion) {
                $registroUsuario = $this->userModel->where('usuario', '=', $usuarioSesion)->first();
                $idUsuario = $registroUsuario['id_usuario'] ?? null;
            }

            $prestamo = $this->model->create([
                'id_usuario'           => $idUsuario,
                'nombre_lector'        => $data['nombre_lector'],
                'id_ejemplar'          => $ejemplar['id_ejemplar'],
                'fecha_prestamo'       => date('Y-m-d'),
                'fecha_devolucion'     => $data['fecha_devolucion'],
                'tipo_identificacion'  => $data['tipo_identificacion'],
                'numero_documento'     => $data['numero_documento'],
                'devuelto'             => 0,
            ]);

            if (!$prestamo) {
                echo json_encode(['success' => false, 'message' => 'No se pudo registrar el préstamo.']);
                return;
            }

            // El libro queda ocupado mientras dure el préstamo
            $this->libroModel->update($data['id_libro'], ['disponible' => 0]);

            echo json_encode(['success' => true, 'prestamo' => $prestamo]);
        }

        public function devolver_prestamo() {

            $id = $_POST['id'] ?? null;

            if (!$id) {
                echo json_encode(['success' => false, 'message' => 'No se indicó el préstamo a devolver']);
                return;
            }

            $libro = $this->model->obtenerLibroDe($id);

            $devuelto = $this->model->devolver($id);

            if (!$devuelto) {
                echo json_encode(['success' => false, 'message' => 'No se pudo registrar la devolución.']);
                return;
            }

            if ($libro) {
                $this->libroModel->update($libro['id_libro'], ['disponible' => 1]);
            }

            echo json_encode(['success' => true]);
        }

        public function eliminar_prestamo() {

            $id = $_POST['id'] ?? null;

            if (!$id) {
                echo json_encode(['success' => false, 'message' => 'No se indicó el préstamo a eliminar']);
                return;
            }

            // Si el préstamo seguía activo, liberamos el libro antes de borrar el registro
            $libro = $this->model->obtenerLibroDe($id);
            $prestamo = $this->model->find($id);

            $eliminado = $this->model->delete($id);

            if ($eliminado && $libro && $prestamo && !$prestamo['devuelto']) {
                $this->libroModel->update($libro['id_libro'], ['disponible' => 1]);
            }

            echo json_encode(['success' => $eliminado]);
        }

        private function estado_prestamo($prestamo) {
            if ($prestamo['devuelto']) {
                return 'Devuelto';
            }
            if ($prestamo['fecha_devolucion'] && $prestamo['fecha_devolucion'] < date('Y-m-d')) {
                return 'Vencido';
            }
            return 'Prestado';
        }

        private function datos_prestamo() {
            return [
                'id_libro'             => $_POST['id_libro'] ?? '',
                'nombre_lector'        => trim($_POST['nombre_lector'] ?? ''),
                'tipo_identificacion'  => $_POST['tipo_identificacion'] ?? '',
                'numero_documento'     => trim($_POST['numero_documento'] ?? ''),
                'fecha_devolucion'     => $_POST['fecha_devolucion'] ?? '',
            ];
        }

        private function validar_prestamo($data) {
            if ($data['id_libro'] === '') {
                return 'Debes seleccionar un libro';
            }
            if ($data['nombre_lector'] === '') {
                return 'El nombre del lector es obligatorio';
            }
            if ($data['tipo_identificacion'] === '') {
                return 'Debes seleccionar un tipo de identificación';
            }
            if ($data['numero_documento'] === '') {
                return 'El número de documento es obligatorio';
            }
            if ($data['fecha_devolucion'] === '') {
                return 'Debes indicar la fecha límite de devolución';
            }
            if ($data['fecha_devolucion'] < date('Y-m-d')) {
                return 'La fecha de devolución no puede ser anterior a hoy';
            }
            return null;
        }
    }