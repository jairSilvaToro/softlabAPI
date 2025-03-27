<?php
namespace App\Models;
use PDO;
use PDOException;
use Firebase\JWT\JWT;

class Representante {
    private $conn;
    private $jwtSecret;
    private $table = 'reprelegal';

    public function __construct($database) {
        $this->conn  = $database->connect();
        $this->jwtSecret = $_ENV['JWT_SECRET'];
    }   

    public function obtenerRepresentantes($limit = 10, $offset = 0) {
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

    public function obtenerRepresentantePorCedula($cedula) {
        if (!$this->conn) {
            return "No se pudo establecer la conexión a la base de datos.";
        }
    
        $query = "SELECT r.cedula_reprelegal,
                         r.nombre,
                         r.telefono,
                         e.nombre_empresa,
                         e.cod_empresa
                  FROM reprelegal r
                  LEFT JOIN empresa e
                  ON r.cedula_reprelegal = e.cedula_reprelegal
                  WHERE r.cedula_reprelegal = :cedula";
    
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':cedula', $cedula);        
        $stmt->execute();
        $result = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        $this->conn = null;
    
        if (empty($result)) {
            return [];
        }
    
        // Estructurar los resultados
        $representante = [
            'cedula_reprelegal' => $result[0]['cedula_reprelegal'],
            'nombre' => $result[0]['nombre'],
            'telefono' => $result[0]['telefono'],
            'empresas' => []
        ];
    
        foreach ($result as $row) {
            if (!empty($row['nombre_empresa'])) {
                $representante['empresas'][] = [
                    'nombre_empresa' => $row['nombre_empresa'],
                    'cod_empresa' => $row['cod_empresa']
                ];
            }
        }
    
        return $representante;
    }

    public function insertarRepresentante($nuevoRepre) {       

        try {
            // Preparar la consulta
            $query = "INSERT INTO $this->table (cedula_reprelegal,nombre,telefono) VALUES (:cedula,:nombre,:telefono)";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':cedula', $nuevoRepre->cedula_reprelegal);
            $stmt->bindParam(':nombre', $nuevoRepre->nombre);
            $stmt->bindParam(':telefono', $nuevoRepre->telefono);            
    
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

    public function actualizarRepresentante($representante){

        try {
            if (!$this->conn) {
                return "No se pudo establecer la conexión a la base de datos.";
            }
            $query = "UPDATE 
                            $this->table
                        SET 
                            nombre = :nombre,
                            telefono = :telefono
                        WHERE 
                            cedula_reprelegal = :cedula";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':nombre', $representante->nombre);
            $stmt->bindParam(':telefono', $representante->telefono);
            $stmt->bindParam(':cedula', $representante->cedula_reprelegal);
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
