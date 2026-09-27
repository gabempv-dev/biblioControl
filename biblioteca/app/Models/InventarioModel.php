<?php
namespace App\Models;
 
class InventarioModel extends Model{
    protected $table = 'libro';
    protected $primaryKey = 'id_libro';

    public function allConDetalles(){
        $sql = "SELECT libro.*,
                       categoria.categoria AS categoria_nombre,
                       autor.nombre AS autor_nombre
                FROM libro
                LEFT JOIN categoria ON libro.id_categoria = categoria.id_categoria
                LEFT JOIN autor ON libro.id_autor = autor.id_autor
                ORDER BY libro.titulo ASC";

        return $this->query($sql)->get();
    }
}