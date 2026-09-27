<?php
namespace App\Models;

class Categoria extends Model {
    protected $table = 'categoria';
    protected $primaryKey = 'id_categoria';

    public function all(){
        $sql = "SELECT * FROM {$this->table} ORDER BY categoria ASC";
        return $this->query($sql)->get();
    }

    public function conConteoLibros(){
        $sql = "SELECT categoria.id_categoria,
                       categoria.categoria,
                       COUNT(libro.id_libro) AS total_libros
                FROM categoria
                LEFT JOIN libro ON libro.id_categoria = categoria.id_categoria
                GROUP BY categoria.id_categoria, categoria.categoria
                ORDER BY categoria.categoria ASC";
        return $this->query($sql)->get();
    }

    public function existeNombre($nombre, $excluirId = null){
        $nombre = $this->connection->real_escape_string($nombre);
        $sql = "SELECT id_categoria FROM {$this->table} WHERE categoria = '{$nombre}'";

        if ($excluirId) {
            $sql .= " AND id_categoria != " . (int) $excluirId;
        }

        return (bool) $this->query($sql)->first();
    }

    public function contarLibros($id){
        $id = (int) $id;
        $sql = "SELECT COUNT(*) AS total FROM libro WHERE id_categoria = {$id}";
        return (int) ($this->query($sql)->first()['total'] ?? 0);
    }
}