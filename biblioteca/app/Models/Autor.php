<?php
namespace App\Models;

class Autor extends Model {
    protected $table = 'autor';
    protected $primaryKey = 'id_autor';

    public function all(){
        $sql = "SELECT * FROM {$this->table} ORDER BY nombre ASC";
        return $this->query($sql)->get();
    }

    public function buscarOCrear($nombre){
        $nombre = $nombre;

        if ($nombre === '') {
            return null;
        }

        $sql = "SELECT * FROM {$this->table} WHERE nombre = '" . $nombre . "'";
        $existente = $this->query($sql)->first();

        if ($existente) {
            return $existente;
        }

        return $this->create(['nombre' => $nombre]);
    }
}