<?php
namespace App\Routes;

use App\Config\Database;
use App\Controllers\CommentsController;

// Obtener el método de la solicitud y la URL
$method = $_SERVER['REQUEST_METHOD'];
$request = $_SERVER['REQUEST_URI'];

$database = new Database();
$commentsController = new CommentsController($database);

switch ($method) {
    case 'GET':
        // comment by id
        if (preg_match('/\/comments\/(\d+)/', $request, $matches)) {
            // Ruta para obtener un comentario por ID (e.g., GET /comment/1)
            $commentId = $matches[1];
            echo json_encode($commentsController->getPostById($commentId));
        } elseif(preg_match('/\/comments\/post\/(\d+)/', $request, $matches)) {
            // Ruta para obtener todos los comments de un post
            $postId = $matches[1];
            echo json_encode($commentsController->getcommentsByPost($postId));
        }else {
            // Ruta para obtener todos los usuarios (e.g., GET /users)
            $queryParams = $_GET;
            $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
            $limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 10;
            // echo json_encode($commentsController->getAllPosts($page,$limit));
        }
        break;

    case 'POST':
        // Crear un nuevo comentario (e.g., POST /users)
        $resp = $commentsController->createComment();
        $decode = json_decode($resp);
        if(isset($decode->error))
        {
            echo json_encode(['error' => 'The post could not be created']);
        }else{
            echo $resp;
        }
        break;

    case 'PUT':
        // Actualizar un comentario existente (e.g., PUT /users/1)
        echo $commentsController->updateComment();       
        break;

    case 'DELETE':
        // Eliminar un comentario existente (e.g., DELETE /users/1)
        if (preg_match('/\/comments\/(\d+)/', $request, $matches)) {
            $postId = $matches[1];
            echo $commentsController->deleteComment($postId);
        } else {
            http_response_code(404);
            echo json_encode(['message' => 'Ruta no encontrada']);
        }
        break;

    default:
        // Método no permitido
        http_response_code(405);
        echo json_encode(['message' => 'Método no permitido']);
        break;
}
