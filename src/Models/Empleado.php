<?php
namespace App\Models;
use PDO;
use PDOException;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class Empleado {
    private $conn;
    private $jwtSecret;
    private $table = 'empleado';

    public function __construct($database) {
        $this->conn  = $database->connect();
        $this->jwtSecret = $_ENV['JWT_SECRET'];
    }   

    public function obtenerEmpleados(int $limit = 10, int $offset = 0) {
        if (!$this->conn) {
            return "No se pudo establecer la conexión a la base de datos.";
        }    

        $query = "SELECT empleado.cedula_empleado,
                empleado.nombre,
                empleado.apellido,
                empleado.telefono,
                (SELECT nombre_rol FROM roles WHERE roles.cod_rol = empleado.cod_rol) AS rol
                FROM $this->table
                LIMIT :limit OFFSET :offset";
    
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

    public function obtenerEmpleadoPorCedula(string $cedula) {
        if (!$this->conn) {
            return "No se pudo establecer la conexión a la base de datos.";
        }

        $query = "SELECT empleado.cedula_empleado,
                         empleado.nombre,
                         empleado.apellido,
                         empleado.telefono,
                         (SELECT nombre_rol FROM roles WHERE roles.cod_rol = empleado.cod_rol) AS rol
                FROM $this->table
                WHERE empleado.cedula_empleado = :cedula"; 

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':cedula', $cedula, PDO::PARAM_INT);
        
        $stmt->execute();
        $this->conn = null;
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function insertarEmpleado($nuevoEmpleado) {

        try {
            // Preparar la consulta
            $query = "INSERT INTO $this->table (
                cedula_empleado,
                cod_rol,
                nombre,
                apellido,
                telefono,
                password
            ) VALUES (:cedula,:rol,:nombre,:apellido,:telefono,:password)";
            $stmt = $this->conn->prepare($query);
            $hashedPassword = password_hash($nuevoEmpleado->cedula_empleado, PASSWORD_BCRYPT);

            $stmt->bindParam(':cedula', $nuevoEmpleado->cedula_empleado);
            $stmt->bindParam(':rol', $nuevoEmpleado->cod_rol);
            $stmt->bindParam(':nombre', $nuevoEmpleado->nombre);
            $stmt->bindParam(':apellido', $nuevoEmpleado->apellido);
            $stmt->bindParam(':telefono', $nuevoEmpleado->telefono);
            $stmt->bindParam(':password', $hashedPassword);
            
    
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

    public function eliminarEmpleado(string $cedula){
        if (!$this->conn) {
            return "No se pudo establecer la conexión a la base de datos.";
        }

        $query = "DELETE FROM $this->table WHERE empleado.cedula_empleado = :cedula";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':cedula', $cedula, PDO::PARAM_INT);
        
        if ($stmt->execute()) {
            $this->conn = null;
            $rowCount = $stmt->rowCount();
            if( $rowCount == 0){                    
                return json_encode(['error' => 'El empleado no pudo ser eliminado']);
            }
            return 1;
        }
        $this->conn = null;               
        return false;
    }

    public function actualizarEmpleado($empleado){

        try {
            if (!$this->conn) {
                return "No se pudo establecer la conexión a la base de datos.";
            }
            $query = "UPDATE 
                            $this->table
                        SET 
                            nombre = :nombre,
                            apellido = :apellido,
                            telefono = :telefono,
                            cod_rol = :rol
                        WHERE 
                            cedula_empleado = :cedula";
            $stmt = $this->conn->prepare($query);
            
            $stmt->bindParam(':nombre', $empleado->nombre);
            $stmt->bindParam(':apellido', $empleado->apellido);
            $stmt->bindParam(':telefono', $empleado->telefono);
            $stmt->bindParam(':rol', $empleado->cod_rol);
            $stmt->bindParam(':cedula', $empleado->cedula_empleado);
            if ($stmt->execute()) {
                $this->conn = null;
                $rowCount = $stmt->rowCount();
                if( $rowCount == 0){                    
                    return json_encode(['error' => 'El empleado no pudo ser actualizado']);
                }
                return 1;
            }
            $this->conn = null;               
            return false;
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            return false;
        }
    }

    public function authenticate($empleado){
        
        try {
            // $query = "SELECT cedula_empleado,nombre,apellido,cod_rol,telefono,password FROM $this->table WHERE cedula_empleado = :cedula";
            $query = "SELECT cedula_empleado,nombre,apellido,empleado.cod_rol,roles.nombre_rol,telefono,password 
                      FROM $this->table 
                      INNER JOIN roles 
                      ON empleado.cod_rol = roles.cod_rol 
                      WHERE cedula_empleado = :cedula";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':cedula', $empleado->username);
            $stmt->execute();
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if($user && password_verify($empleado->password, $user['password'])){
                $payload = [
                    'iss' => 'softlabAPI',
                    'iat' => time(),
                    'exp' => time() + (60 * 60), // Expira en 1 hora
                    'userId' => $user['cedula_empleado'],
                    'rol' => $user['nombre_rol']
                ];
                $jwt = JWT::encode($payload, $this->jwtSecret, 'HS256');
                return ['token' => $jwt];                
            }
            $this->conn = null;
            return null;
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            return null;
        }
    } 
}
