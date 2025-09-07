<?php
require_once 'config/Database.php';

class TalleresModel {
    private $conn;
    private $table = 'talleres';

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
     
    public function create($nombre, $descripcion, $fecha, $ubicacion, $precio, $cupo_maximo, $imagen_url) {
    // El cupo_actual se inicializa en 0 ya que es un nuevo registro
    $cupo_actual = 0;

    $stmt = $this->conn->prepare("INSERT INTO {$this->table} 
    (nombre, descripcion, fecha, ubicacion, precio, cupo_maximo, cupo_actual, imagen_url) 
    VALUES (:nombre, :descripcion, :fecha, :ubicacion, :precio, :cupo_maximo, :cupo_actual, :imagen_url)");

    // Vincula todos los parámetros a los valores correspondientes
    $stmt->bindParam(':nombre', $nombre);
    $stmt->bindParam(':descripcion', $descripcion);
    $stmt->bindParam(':fecha', $fecha);
    $stmt->bindParam(':ubicacion', $ubicacion);
    $stmt->bindParam(':precio', $precio);
    $stmt->bindParam(':cupo_maximo', $cupo_maximo);
    $stmt->bindParam(':cupo_actual', $cupo_actual); // Vincula el valor inicializado
    $stmt->bindParam(':imagen_url', $imagen_url);

    // Si la ejecución de la consulta es exitosa, devuelve el nuevo taller
    if ($stmt->execute()) {
        $id = $this->conn->lastInsertId();
        return $this->findById($id);
    }
    return null;
}
  

 public function update($id, $nombre, $descripcion, $fecha, $ubicacion, $precio, $cupo_maximo, $imagen_url) {
    $stmt = $this->conn->prepare("UPDATE {$this->table} SET 
        nombre = :nombre, 
        descripcion = :descripcion, 
        fecha = :fecha, 
        ubicacion = :ubicacion, 
        precio = :precio, 
        cupo_maximo = :cupo_maximo, 
        imagen_url = :imagen_url
        WHERE id = :id");

    // Vincula todos los parámetros
    $stmt->bindParam(':id', $id);
    $stmt->bindParam(':nombre', $nombre);
    $stmt->bindParam(':descripcion', $descripcion);
    $stmt->bindParam(':fecha', $fecha);
    $stmt->bindParam(':ubicacion', $ubicacion);
    $stmt->bindParam(':precio', $precio);
    $stmt->bindParam(':cupo_maximo', $cupo_maximo);
    $stmt->bindParam(':imagen_url', $imagen_url);

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
