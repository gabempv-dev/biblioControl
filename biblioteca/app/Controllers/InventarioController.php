<?php

    namespace App\Controllers;

    use App\Models\InventarioModel;
    use App\Models\Categoria;
    use App\Models\Autor;
    use App\Libraries\Fpdf\FPDF;

    class InventarioController extends Controller{
        protected $model;
        protected $categoriaModel;
        protected $autorModel;

        public function __construct() {
            session_start();
            $this->model = new InventarioModel();
            $this->categoriaModel = new Categoria();
            $this->autorModel = new Autor();
        }

        public function index(){
            return $this->view('inventario');
        }

        public function obtener_libros() {
            // Colocamos el código PHP para realizar la preparación de los datos de la tabla dinámica
            // Variables enviadas por jqGrid
            $page = $_POST['page'];
            $limit = $_POST['rows'];
            $sidx = $_POST['sidx'];
            $sord = $_POST['sord'];

            if (!$sidx) $sidx = 1;

            // Realizamos la petición al modelo para obtener y contar la cantidad de registros de la tabla libro
            // (incluye el nombre de la categoría y del autor gracias al JOIN)
            $registros = $this->model->allConDetalles();
            $count = count($registros);

            // Formula para determinar la cantidad de páginas
            $total_pages = $count > 0 ? ceil($count/$limit) : 0;

            // Condición para verificar si no hay mas de 1 página
            if ($page > $total_pages) $page = $total_pages;
            $start = $limit * $page - $limit;
            if($start < 0) $start = 0;

            // Creamos un objeto utilizando el método stdClass
            $responce = new \stdClass();

            // Agregamos las siguientes propiedades al objeto $responce
            // valores necesarios en el jqGrid
            $responce->page = $page;
            $responce->total = $total_pages;
            $responce->records = $count;

            // Preparamos los datos que se mostraran en la tabla dinamica
            $i = 0;
            foreach($registros as $libro) {
                $responce->rows[$i]['id'] = $libro['id_libro'];
                $responce->rows[$i]['cell'] = [
                    $libro['isbn'],
                    $libro['titulo'],
                    $libro['id_categoria'],
                    $libro['categoria_nombre'],
                    $libro['autor_nombre'],
                    $libro['editorial'],
                    $libro['cantidad_paginas'],
                    $libro['tomo'],
                    $libro['disponible'] ? 'Sí' : 'No'
                ];
                $i++;
            }

            // Enviamos la respuesta en formato JSON
            echo json_encode($responce);
        }

        public function obtener_categorias() {
            echo json_encode(['registros' => $this->categoriaModel->all()]);
        }

        public function obtener_autores() {
            echo json_encode(['registros' => $this->autorModel->all()]);
        }

        

        public function guardar_libro() {

            $data = $this->datos_libro();

            $error = $this->validar_libro($data);
            if ($error) {
                echo json_encode(['success' => false, 'message' => $error]);
                return;
            }

            $libro = $this->model->create($data);

            if (!$libro) {
                echo json_encode(['success' => false, 'message' => 'No se pudo guardar el libro.']);
                return;
            }

            echo json_encode(['success' => true, 'libro' => $libro]);
        }

        public function actualizar_libro() {

            $id = $_POST['id_libro'] ?? null;

            if (!$id) {
                echo json_encode(['success' => false, 'message' => 'No se indicó el libro a actualizar']);
                return;
            }

            $data = $this->datos_libro();

            $error = $this->validar_libro($data);
            if ($error) {
                echo json_encode(['success' => false, 'message' => $error]);
                return;
            }

            $libro = $this->model->update($id, $data);

            if (!$libro) {
                echo json_encode(['success' => false, 'message' => 'No se pudo actualizar el libro.']);
                return;
            }

            echo json_encode(['success' => true, 'libro' => $libro]);
        }

        public function eliminar_libro() {

            $id = $_POST['id'] ?? null;

            if (!$id) {
                echo json_encode(['success' => false, 'message' => 'No se indicó el libro a eliminar']);
                return;
            }

            $eliminado = $this->model->delete($id);

            echo json_encode(['success' => $eliminado]);
        }

        private function datos_libro() {
            $autor = $this->autorModel->buscarOCrear($_POST['autor'] ?? '');

            return [
                'isbn'              => $_POST['isbn'] ?? '',
                'titulo'            => $_POST['titulo'] ?? '',
                'id_categoria'      => $_POST['id_categoria'] ?? '',
                'id_autor'          => $autor['id_autor'] ?? '',
                'editorial'         => $_POST['editorial'] ?? '',
                'cantidad_paginas'  => $_POST['cantidad_paginas'] ?? '',
                'tomo'              => $_POST['tomo'] ?? '',
                'disponible'        => isset($_POST['disponible']) ? 1 : 0,
            ];
        }

        private function validar_libro($data) {
            if ($data['titulo'] === '') {
                return 'El título es obligatorio';
            }
            if ($data['id_categoria'] === '') {
                return 'Debes seleccionar una categoría';
            }
            if ($data['id_autor'] === '') {
                return 'El nombre del autor es obligatorio';
            }
            return null;
        }

        public function pdf(){
            //Instanciamos la clase FPDF en una variable para crear un objeto
            //P -> Portrait(Vertical) | L -> Landscape(Horizontal)
            //cm -> centrimetro | mm -> milimetros
            //Letter -> Carta | Legal -> Oficio
            $pdf = new FPDF('P', 'mm', 'Letter');

            //Agregamos una página al PDF
            $pdf->addPage();

            //Establecemos el tipo de letra, apariencia y tamaño
            $pdf->setFont('Arial', '', 10);

            $ancho = $pdf->GetPageWidth()-20;

            //Creamos una celda con información que se colocara en el PDF
            $pdf->Cell($ancho,5,'INSTITUTO NACIONAL "GENERAL FRANCISCO MENENDEZ"',0,1,'C');
            $pdf->Cell($ancho,5, 'INFRAMEN', 0, 1,'C');
            $pdf->Ln(10);

            $datos = [
                (Object)['codigo' => 'SM-001', 'nombre' => 'Monitor Samsung', 'medida' => 'Unidad', 'precio' => 200],
                (Object)['codigo' => 'SM-002', 'nombre' => 'Mouse Samsung', 'medida' => 'Unidad', 'precio' => 3],
                (Object)['codigo' => 'SM-003', 'nombre' => 'Teclado Samsung', 'medida' => 'Unidad', 'precio' => 50],
                (Object)['codigo' => 'SM-004', 'nombre' => 'Bocinas Samsung', 'medida' => 'Unidad', 'precio' => 100]
            ];
            foreach ($datos as $dato) {
                $pdf->Cell(25,5, $dato->codigo, 1, 0, 'C');
                $pdf->Cell(80,5, $dato->nombre, 1, 0);
                $pdf->Cell(25,5, $dato->medida, 1, 0, 'C');
                $pdf->Cell(25,5, '$ '.$dato->precio, 1, 1, 'C');
            }

            //Mostramos el PDF en una nueva ventana en el navegador
            //I -> Hace que se muestre en una nueva ventana y podamos descargar el PDF posteriormente
            $pdf->output('I', 'ejemplo.pdf');
        }

    }