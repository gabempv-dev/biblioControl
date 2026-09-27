<?php
namespace App\Models;

use mysqli;

class Model{
    protected $db_host = DB_HOST;
    protected $db_user = DB_USER;
    protected $db_pass = DB_PASS;
    protected $db_name = DB_NAME;
    protected $connection;
    protected $query;
    protected $table;
    protected $primaryKey = 'id';

    public function __construct(){
        $this->connection();
    }

    public function connection(){
        $this->connection = new mysqli($this->db_host, $this->db_user, $this->db_pass, $this->db_name);

        if ($this->connection->connect_error) {
            die("Error de conexion: ". $this->connection->connect_error);
        }
    }

    public function query($sql){
        $this->query = $this->connection -> query($sql);
        
        return $this;
    }

    public function first(){
        if (!$this->query) return null;
        return $this->query->fetch_assoc();
    }

    public function get(){
        if (!$this->query) return [];
        return $this->query->fetch_all(MYSQLI_ASSOC);
    }

    //Consultas
    public function all(){
        //SELECT * FROM contacts
        $sql = "SELECT * FROM {$this->table}";

        return $this->query($sql)->get();
    }

    public function find($id){
        //SELECT * FROM contacts WHERE id = 1
        $id = (int) $id;
        $sql = "SELECT * FROM {$this->table} WHERE {$this->primaryKey} = {$id}";
        return $this->query($sql)->first();
    }

    public function where($column, $operator, $value = null){
        if($value == null){
            $value = $operator;
            $operator = '=';
        }
        //SELECT * FROM contacts  WHERE name = 'Juan'
        $sql = "SELECT * FROM {$this->table} WHERE {$column} {$operator} '{$value}'";
        return $this->query($sql);

        return $this; 
    }

    // Convierte un valor a como debe escribirse en el SQL: NULL real si viene vacío,
    // o el valor escapado entre comillas si trae contenido.
    protected function valorSql($value){
        if ($value === null || $value === '') {
            return 'NULL';
        }
        return "'" . $this->connection->real_escape_string($value) . "'";
    }

    public function create($data){
        //INSERT INTO contacts(name, email, phone) VALUES ('', '', '')
        $columns = implode(', ', array_keys($data));

        $values = array_map([$this, 'valorSql'], array_values($data));
        $values = implode(', ', $values);

        $sql = "INSERT INTO {$this->table} ({$columns}) VALUES ({$values})";

        $this->query($sql);

        if (!$this->query) return false; // La query falló (ej. llave foránea inexistente)

        $insert_id = $this->connection->insert_id;

        return $this->find($insert_id);
    }

    public function update($id, $data){
        //UPDATE contacts SET name = '', email = '', phone = '' WHERE id = 1
        $fields = [];

        foreach ($data as $key => $value){
            $fields[] = "{$key} = " . $this->valorSql($value);
        }

        $fields = implode(', ', $fields); 

        $id = (int) $id;
        $sql = "UPDATE {$this->table} SET {$fields} WHERE {$this->primaryKey} = {$id}";

        $this->query($sql);

        if (!$this->query) return false;

        return $this->find($id);
    }

    public function delete($id){
        //DELETE FROM contacts WHERE id = 1
        $id = (int) $id;
        $sql = "DELETE FROM {$this->table} WHERE {$this->primaryKey} = {$id}";

        $this->query($sql);

        return (bool) $this->query;
    }

    public function totalLibros(){
        $sql = "SELECT SUM(cantidad) AS total FROM ejemplar";

        return $this->query($sql)->first()['total'] ?? 0;
    }

    public function totalPrestados(){
        $sql = "SELECT COUNT(*) AS total FROM prestamo WHERE fecha_devolucion >= CURDATE() OR fecha_devolucion IS NULL";
        
        return $this->query($sql)->first()['total'] ?? 0;
    }

    public function totalLectores(){
        $sql = "SELECT COUNT(*) AS total FROM usuarios";
        
        return $this->query($sql)->first()['total'] ?? 0;
    }

    public function prestamosVencidos(){
        $sql = "SELECT COUNT(*) AS total FROM prestamo WHERE fecha_devolucion < CURDATE()";

        return $this->query($sql)->first()['total'] ?? 0;
    }
}