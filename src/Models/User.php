<?php
namespace App\Models;
use PDO;
use PDOException;
use Firebase\JWT\JWT;

class User {
    private $conn;
    private $jwtSecret;
    private $table = 'users';

    public function __construct($database) {
        $this->conn  = $database->connect();
        $this->jwtSecret = $_ENV['JWT_SECRET'];
    }

    public function authenticate($username, $password){
        try {
            $query = "SELECT id, name, email, username, password FROM users WHERE username = :username";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':username', $username);
            $stmt->execute();
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if($user && password_verify($password, $user['password'])){
                $payload = [
                    'iss' => 'api-post',
                    'iat' => time(),
                    'exp' => time() + (30 * 30), // Expira en 1 hora
                    'userId' => $user['id']
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

    public function getAllUsers() {
        if (!$this->conn) {
            return "No se pudo establecer la conexión a la base de datos.";
        }

        $query = "SELECT id, name, email, username FROM " . $this->table;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $user = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        $this->conn = null;
        return $user ? $user : null;
    }

    public function getUserById($userId) {
        if (!$this->conn) {
            return "No se pudo establecer la conexión a la base de datos.";
        }

        $query = "SELECT id, name, email, username FROM users WHERE id = :userId";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
        
        $stmt->execute();
        $this->conn = null;
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function createUser($name, $email, $username, $password) {

        try {
                // Preparar la consulta
                $query = "INSERT INTO users (name, email, username, password) VALUES (:name, :email, :username, :password)";
                $stmt = $this->conn->prepare($query);
    
                // Encriptar la contraseña
                $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
    
                $stmt->bindParam(':name', $name);
                $stmt->bindParam(':email', $email);
                $stmt->bindParam(':username', $username);
                $stmt->bindParam(':password', $hashedPassword);
    
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

    public function deleteUser($userId){
        if (!$this->conn) {
            return "No se pudo establecer la conexión a la base de datos.";
        }

        $query = "DELETE FROM users WHERE users.id = :userId";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
        
        if ($stmt->execute()) {
            $this->conn = null;
            return true;
        }
        $this->conn = null;               
        return false;
    }

    public function updateUser($name, $email, $username, $id){

        try {
            if (!$this->conn) {
                return "No se pudo establecer la conexión a la base de datos.";
            }
            $query = "UPDATE users SET name = :name, email = :email, username = :username WHERE users.id = :id";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':name', $name);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':username', $username);
            $stmt->bindParam(':id', $id);
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
