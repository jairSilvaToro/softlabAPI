<?php
namespace App\Models;
use PDO;
use PDOException;
use Firebase\JWT\JWT;

class Comment {
    private $conn;
    private $jwtSecret;
    private $table = 'comments';

    public function __construct($database) {
        $this->conn  = $database->connect();
        $this->jwtSecret = $_ENV['JWT_SECRET'];
    }   

    public function getCommentsByPost($postId, $limit = 10, $offset = 0) {
        if (!$this->conn) {
            return "No se pudo establecer la conexión a la base de datos.";
        }
    
        $query = "SELECT comments.comment, comments.created_at, users.username 
                  FROM $this->table
                  INNER JOIN users ON comments.id_user = users.id 
                  WHERE comments.id_posts = :postId
                  LIMIT :limit OFFSET :offset";
    
        try {
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':postId', $postId, PDO::PARAM_INT);
            $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
            $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
    
            $stmt->execute();
            $this->conn = null;
    
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return "Error en la consulta: " . $e->getMessage();
        }
    }
    

    public function getCommentById($commentId) {
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
        $stmt->bindParam(':postId', $commentId, PDO::PARAM_INT);
        
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

    public function createComment($comment) {

        try {
            // Preparar la consulta
            $query = "INSERT INTO $this->table (comment, id_posts,id_user) VALUES (:comment, :idPost,:idUser)";
            $stmt = $this->conn->prepare($query);

            $stmt->bindParam(':comment', $comment->comment);
            $stmt->bindParam(':idUser', $comment->id_user);
            $stmt->bindParam(':idPost', $comment->id_posts);
    
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

    public function deleteComment($commentId){
        if (!$this->conn) {
            return "No se pudo establecer la conexión a la base de datos.";
        }

        $query = "DELETE FROM $this->table WHERE comments.id = :commentId";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':commentId', $commentId, PDO::PARAM_INT);
        
        if ($stmt->execute()) {
            $this->conn = null;
            return true;
        }
        $this->conn = null;               
        return false;
    }

    public function updateComment($comment,$id){

        try {
            if (!$this->conn) {
                return "No se pudo establecer la conexión a la base de datos.";
            }
            $query = "UPDATE $this->table SET comment = :comment WHERE comments.id = :id";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':comment', $comment);
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
