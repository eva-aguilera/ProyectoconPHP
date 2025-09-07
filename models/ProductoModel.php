<?php
// Nota: La ruta de la inclusión de la base de datos podría necesitar ser ajustada
// si el archivo Database.php no está en una carpeta paralela.
// Por ejemplo, si está en 'config', debería ser require_once '../config/db.php';
require_once 'config/Database.php';

class ProductoModel {
    private $conn;
    private $table = 'insumos';

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

    public function create($nombre, $descripcion, $precio, $stock, $categoria) {
    $stmt = $this->conn->prepare("INSERT INTO {$this->table} 
    (nombre, descripcion, precio, stock, categoria) 
    VALUES (:nombre, :descripcion, :precio, :stock, :categoria)");

    $stmt->bindParam(':nombre', $nombre);
    $stmt->bindParam(':descripcion', $descripcion);
    $stmt->bindParam(':precio', $precio);
    $stmt->bindParam(':stock', $stock);
    $stmt->bindParam(':categoria', $categoria); // Nuevo parámetro

    if ($stmt->execute()) {
        $id = $this->conn->lastInsertId();
        return $this->findById($id);
    }
    return null;
}

    public function update($id, $nombre, $descripcion, $precio, $stock, $categoria) {
    $stmt = $this->conn->prepare("UPDATE {$this->table} SET 
        nombre = :nombre, 
        descripcion = :descripcion, 
        precio = :precio, 
        stock = :stock,
        categoria = :categoria
        WHERE id = :id");

    $stmt->bindParam(':id', $id);
    $stmt->bindParam(':nombre', $nombre);
    $stmt->bindParam(':descripcion', $descripcion);
    $stmt->bindParam(':precio', $precio);
    $stmt->bindParam(':stock', $stock);
    $stmt->bindParam(':categoria', $categoria); // Nuevo parámetro

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