<?php
namespace App\Models;
use PDO;
use PDOException;
use Firebase\JWT\JWT;

class Rol {
    private $conn;
    private $jwtSecret;
    private $table = 'roles';

    public function __construct($database) {
        $this->conn  = $database->connect();
        $this->jwtSecret = $_ENV['JWT_SECRET'];
    } 
    
    public function obtenerRoles(){
        if (!$this->conn) {
            return "No se pudo establecer la conexión a la base de datos.";
        }
        $query = "SELECT * FROM $this->table";
        $stmt = $this->conn->prepare($query);

        $stmt->execute();
        $rol = $stmt->fetchAll(\PDO::FETCH_ASSOC);        
        return $rol ? $rol : null;
        
    }
  
    public function crearRol($rol) {
        try {
            // Preparar la consulta
            $query = "INSERT INTO $this->table (nombre_rol) VALUES (:rol)";
            $stmt = $this->conn->prepare($query);

            $stmt->bindParam(':rol', $rol);
    
            if ($stmt->execute()) {
                $this->conn = null;
                return true;
            }
            $this->conn = null;               
            return false;
        } catch (PDOException $e) {
            return json_encode(['error' => $e->getMessage()]);
        } 
    }

    public function eliminarRol($cod_rol){
        if (!$this->conn) {
            return "No se pudo establecer la conexión a la base de datos.";
        }

        $query = "DELETE FROM $this->table WHERE roles.cod_rol = :cod";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':cod', $cod_rol, PDO::PARAM_INT);
        
        if ($stmt->execute()) {
            $rowCount = $stmt->rowCount();
            $this->conn = null;
            return $rowCount;
        }
        $this->conn = null;               
        return false;
    }

    public function actualizarRol($rol, $cod){

        try {
            if (!$this->conn) {
                return "No se pudo establecer la conexión a la base de datos.";
            }
            $query = "UPDATE $this->table SET nombre_rol = :rol WHERE roles.cod_rol = :cod";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':rol', $rol);
            $stmt->bindParam(':cod', $cod);
            if ($stmt->execute()) {
                $this->conn = null;
                return true;
            }
            $this->conn = null;               
            return false;
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            return false;
        }
    }
}
