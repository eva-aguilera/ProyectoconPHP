<?php
// Nota: La ruta de la inclusión de la base de datos podría necesitar ser ajustada
// si el archivo Database.php no está en una carpeta paralela.
// Por ejemplo, si está en 'config', debería ser require_once '../config/db.php';
require_once 'config/Database.php';

class OfertaModel {
    private $conn;
    private $table = 'ofertas_laborales';

    // CONSTRUCTOR CORREGIDO: ahora recibe la conexión como parámetro
    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    public function getAll() {
        $stmt = $this->conn->prepare("SELECT * FROM {$this->table}");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
   
    public function findById($id) {
        $stmt = $this->conn->prepare("SELECT * FROM {$this->table} WHERE id = :id");
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function crearOferta($cargo, $descripcion,$empresa, $requisitos, $ubicacion, $salario) {
        $stmt = $this->conn->prepare("INSERT INTO {$this->table} 
        (cargo, descripcion,empresa, requisitos, ubicacion, salario, estado) 
        VALUES (:cargo, :descripcion,:empresa, :requisitos, :ubicacion, :salario, 'activa')");

          //Vincula todos los parámetros
         $stmt->bindParam(':cargo', $cargo);
         $stmt->bindParam(':descripcion', $descripcion);
         $stmt->bindParam(':empresa', $empresa);
         $stmt->bindParam(':requisitos', $requisitos);
         $stmt->bindParam(':ubicacion', $ubicacion);
         $stmt->bindParam(':salario', $salario);

         if ($stmt->execute()) {
             $id = $this->conn->lastInsertId();
             return $this->findById($id);
         }
         return null;
     }
    //Nota: El método findById() es necesario para que el código funcione

    public function actualizarOferta($id, $cargo, $descripcion,$empresa, $requisitos, $ubicacion, $salario) {
        $stmt = $this->conn->prepare("UPDATE {$this->table} SET cargo = :cargo, descripcion = :descripcion, 
        empresa = :empresa,requisitos = :requisitos, ubicacion = :ubicacion, salario = :salario WHERE id = :id");
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':cargo', $cargo);
        $stmt->bindParam(':descripcion', $descripcion);
        $stmt->bindParam(':empresa', $empresa);
         $stmt->bindParam(':requisitos', $requisitos);
         $stmt->bindParam(':ubicacion', $ubicacion);
         $stmt->bindParam(':salario', $salario);


        if ($stmt->execute() && $stmt->rowCount() > 0) {
            return $this->findById($id);
        }
        return null;
    }

    public function delete($id) {
        $stmt = $this->conn->prepare("DELETE FROM {$this->table} WHERE id = :id");
        $stmt->bindParam(':id', $id);
        return $stmt->execute() && $stmt->rowCount() > 0;
    }
}