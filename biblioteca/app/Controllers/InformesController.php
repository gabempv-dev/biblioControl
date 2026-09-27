<?php

    namespace App\Controllers;

    use App\Models\InventarioModel;
    use App\Models\Categoria;
    use App\Models\Autor;
    use App\Libraries\Fpdf\FPDF;

    class InformesController extends Controller{
        protected $inventarioModel;
        protected $categoriaModel;

        public function __construct() {
            session_start();
            $this->inventarioModel = new InventarioModel();
            $this->categoriaModel = new Categoria();
        }

        public function inventario(){
            $libros = $this->inventarioModel->allConDetalles();

            $pdf = new FPDF('L', 'mm', 'Letter');
            $pdf->AddPage();

            $ancho = $pdf->GetPageWidth() - 20;

            $pdf->SetFont('Arial', 'B', 13);
            $pdf->Cell($ancho, 7, 'BIBLIOCONTROL', 0, 1, 'C');

            $pdf->SetFont('Arial', 'B', 11);
            $pdf->Cell($ancho, 7, 'Informe de Inventario de Libros', 0, 1, 'C');

            $pdf->Ln(6);

            $columnas = [
                'ISBN'      => 22,
                'Titulo'    => 55,
                'Categoria' => 30,
                'Autor'     => 35,
                'Editorial' => 28,
                'Pag.'      => 10,
                'Disp.'     => 10,
            ];

            $pdf->SetFont('Arial', 'B', 9);
            $pdf->SetFillColor(44, 39, 37);
            $pdf->SetTextColor(255, 255, 255);

            foreach ($columnas as $texto => $ancho) {
                $pdf->Cell($ancho, 7, $texto, 1, 0, 'C', true);
            }
            $pdf->Ln();

            $pdf->SetFont('Arial', '', 8);
            $pdf->SetTextColor(0, 0, 0);

            $disponibles = 0;

            foreach ($libros as $libro) {
                $pdf->Cell(22, 6, $libro['isbn'], 1, 0,);
                $pdf->Cell(55, 6, $libro['titulo'], 1, 0,);
                $pdf->Cell(30, 6, $libro['categoria_nombre'], 1, 0,);
                $pdf->Cell(35, 6, $libro['autor_nombre'], 1, 0,);
                $pdf->Cell(28, 6, $libro['editorial'], 1, 0,);
                $pdf->Cell(10, 6, $libro['cantidad_paginas'], 1, 0,);
                $pdf->Cell(10, 6, $libro['disponible'] ? 'Si' : 'No', 1, 1,);
            }

            $pdf->Output('I', 'informe_inventario_libros.pdf');
        }

        public function categorias(){
            $categorias = $this->categoriaModel->conConteoLibros();
 
            $pdf = new FPDF('P', 'mm', 'Letter');
            $pdf->AddPage();
 
            $ancho = $pdf->GetPageWidth() - 20;
 
            $pdf->SetFont('Arial', 'B', 13);
            $pdf->Cell($ancho, 7, 'BIBLIOCONTROL', 0, 1, 'C');
 
            $pdf->SetFont('Arial', 'B', 11);
            $pdf->Cell($ancho, 7, "Informe de Categorias", 0, 1, 'C');
 
            $pdf->SetFont('Arial', '', 9);
            $pdf->Cell($ancho, 5, 'Generado el ' . date('d/m/Y H:i'), 0, 1, 'C');
 
            $pdf->Ln(6);
 
            $columnas = [
                'Categoria'          => 110,
                'Cantidad de Libros' => 60,
            ];

            $pdf->SetFont('Arial', 'B', 9);
            $pdf->SetFillColor(44, 39, 37);
            $pdf->SetTextColor(255, 255, 255);
 
            foreach ($columnas as $texto => $ancho) {
                $pdf->Cell($ancho, 7, $texto, 1, 0, 'C', true);
            }
            $pdf->Ln();
 
            $pdf->SetFont('Arial', '', 8);
            $pdf->SetTextColor(0, 0, 0);
 
            $totalLibros = 0;
 
            foreach ($categorias as $cat) {
                $totalLibros += (int) $cat['total_libros'];
                $pdf->Cell(110, 6, $cat['categoria'], 1, 0);
                $pdf->Cell(60, 6, $cat['total_libros'], 1, 1, 'C');
            }
 
            $pdf->Ln(4);
            $pdf->SetFont('Arial', 'B', 9);
            $pdf->Cell(0, 6, 'Total de categorias: ' . count($categorias) . '   |   Total de libros clasificados: ' . $totalLibros, 0, 1);
 
            $pdf->Output('I', 'informe_categorias.pdf');
        }

    }