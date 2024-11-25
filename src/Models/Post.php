<?php
namespace App\Models;
use PDO;
use PDOException;
use Firebase\JWT\JWT;

class Post {
    private $conn;
    private $jwtSecret;
    private $table = 'posts';

    public function __construct($database) {
        $this->conn  = $database->connect();
        $this->jwtSecret = $_ENV['JWT_SECRET'];
    }   

    public function getAllPosts($limit, $offset) {
        if (!$this->conn) {
            return "No se pudo establecer la conexión a la base de datos.";
        }

        $query = "SELECT posts.post, posts.created_at, users.username 
                    FROM $this->table
                    INNER JOIN users on posts.id_users = users.id 
                    LIMIT :limit OFFSET :offset";
        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        $user = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        return $user ? $user : null;
    }

    public function getTotalPosts() {
        if (!$this->conn) {
            return "No se pudo establecer la conexión a la base de datos.";
        }
        $query = "SELECT COUNT(*) AS total FROM ".$this->table;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $this->conn = null;
        return $stmt->fetch(PDO::FETCH_ASSOC)['total'];
    }

    public function getPostById($postId) {
        if (!$this->conn) {
            return "No se pudo establecer la conexión a la base de datos.";
        }

        $query = "SELECT 
                    id AS post_id,
                    post,
                    created_at,
                    (SELECT username FROM users WHERE users.id = posts.id_users) AS username
                FROM $this->table 
                WHERE id = :postId";        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':postId', $postId, PDO::PARAM_INT);
        
        $stmt->execute();
        $this->conn = null;
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function getPostsByUser($userId) {
        if (!$this->conn) {
            return "No se pudo establecer la conexión a la base de datos.";
        }

        $query = "SELECT post, created_at FROM $this->table WHERE id_users = :userId";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
        
        $stmt->execute();
        $this->conn = null;
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function createPost($post,$userId) {

        try {
                // Preparar la consulta
                $query = "INSERT INTO $this->table (post, id_users) VALUES (:post, :userId)";
                $stmt = $this->conn->prepare($query);
    
                $stmt->bindParam(':post', $post);
                $stmt->bindParam(':userId', $userId);
    
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

    public function deletePost($postId){
        if (!$this->conn) {
            return "No se pudo establecer la conexión a la base de datos.";
        }

        $query = "DELETE FROM $this->table WHERE posts.id = :postId";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':postId', $postId, PDO::PARAM_INT);
        
        if ($stmt->execute()) {
            $this->conn = null;
            return true;
        }
        $this->conn = null;               
        return false;
    }

    public function updatePost($post,$id){

        try {
            if (!$this->conn) {
                return "No se pudo establecer la conexión a la base de datos.";
            }
            $query = "UPDATE posts SET post = :post WHERE posts.id = :id";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':post', $post);
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
