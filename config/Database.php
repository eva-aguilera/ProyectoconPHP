<?php
class Database {
    private $host = "localhost";
    private $db_name = "tejelanas_db";   // nombre base de datos
    private $username = "root";  // usuario de base de datos
    private $password = "";     // contraseña de base de datos
    private $conn;

    public function getConnection() {
        $this->conn = null;

        try {
            $this->conn = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->db_name, $this->username, $this->password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $exception) {
            echo "Error de conexión: " . $exception->getMessage();
        }

        return $this->conn;
    }
}
?>
