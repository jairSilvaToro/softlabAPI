<?php
namespace App\Models;
use PDO;
use PDOException;
use Firebase\JWT\JWT;

class Empresa {
    private $conn;
    private $jwtSecret;
    private $table = 'empresa';

    public function __construct($database) {
        $this->conn  = $database->connect();
        $this->jwtSecret = $_ENV['JWT_SECRET'];
    }   

    public function obtenerEmpresas(int $limit = 10, int $offset = 0) {
        if (!$this->conn) {
            return "No se pudo establecer la conexión a la base de datos.";
        }    

        $query = "SELECT * FROM $this->table LIMIT :limit OFFSET :offset";
    
        try {
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
            $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
    
            $stmt->execute();
            $this->conn = null;
    
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return "Error en la consulta: " . $e->getMessage();
        }
    }    

    public function obtenerEmpresaPorCodigo(string $codigo) {
        if (!$this->conn) {
            return "No se pudo establecer la conexión a la base de datos.";
        }

        $query = "SELECT * FROM $this->table WHERE $this->table.cod_empresa = :codigo"; 

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':codigo', $codigo, PDO::PARAM_STR);
        
        $stmt->execute();
        $this->conn = null;
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function crearEmpresa($nuevaEmpresa) {

        try {
            // Preparar la consulta
            $query = "INSERT INTO $this->table (
                cod_empresa,
                nombre_empresa,
                direccion,
                telefono,
                correo_electronico,
                cedula_reprelegal
            ) VALUES (:cod,:nombre,:direccion,:telefono,:email,:cedula)";
            $stmt = $this->conn->prepare($query);

            $stmt->bindParam(':cod', $nuevaEmpresa->cod_empresa);
            $stmt->bindParam(':nombre', $nuevaEmpresa->nombre_empresa);
            $stmt->bindParam(':direccion', $nuevaEmpresa->direccion);
            $stmt->bindParam(':telefono', $nuevaEmpresa->telefono);
            $stmt->bindParam(':email', $nuevaEmpresa->correo_electronico);
            $stmt->bindParam(':cedula', $nuevaEmpresa->cedula_reprelegal);
    
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

    public function actualizarEmpresa($empresa){

        try {
            if (!$this->conn) {
                return "No se pudo establecer la conexión a la base de datos.";
            }
            $query = "UPDATE 
            $this->table
          SET 
            nombre_empresa = :nombre,
            direccion = :direccion,
            telefono = :telefono,
            correo_electronico = :email,
            cedula_reprelegal = :cedula                           
          WHERE 
            cod_empresa = :cod";

            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':cod', $empresa->cod_empresa);
            $stmt->bindParam(':nombre', $empresa->nombre_empresa);
            $stmt->bindParam(':direccion', $empresa->direccion);
            $stmt->bindParam(':telefono', $empresa->telefono);
            $stmt->bindParam(':email', $empresa->correo_electronico);
            $stmt->bindParam(':cedula', $empresa->cedula_reprelegal);
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
