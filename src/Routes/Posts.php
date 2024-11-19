<?php
namespace App\Routes;

use App\Config\Database;
use App\Controllers\PostsController;

// Obtener el método de la solicitud y la URL
$method = $_SERVER['REQUEST_METHOD'];
$request = $_SERVER['REQUEST_URI'];

$database = new Database();
$postController = new PostsController($database);

switch ($method) {
    case 'GET':
        // Listar todos los posts
        if (preg_match('/\/posts\/(\d+)/', $request, $matches)) {
            // Ruta para obtener un usuario por ID (e.g., GET /users/1)
            $postId = $matches[1];
            echo json_encode($postController->getPostById($postId));
        } elseif(preg_match('/\/posts\/user\/(\d+)/', $request, $matches)) {
            // Ruta para obtener todos los posts de un usuario
            $postId = $matches[1];
            echo json_encode($postController->getPostsByUser($postId));
        }else {
            // Ruta para obtener todos los usuarios (e.g., GET /users)
            echo json_encode($postController->getAllPosts());
        }
        break;

    case 'POST':
        // Crear un nuevo post (e.g., POST /users)
        $resp = $postController->createPost();
        $decode = json_decode($resp);
        if(isset($decode->error))
        {
            echo json_encode(['error' => 'The post could not be created']);
        }else{
            echo $resp;
        }
        break;

    case 'PUT':
        // Actualizar un usuario existente (e.g., PUT /users/1)
        echo $postController->updatePost();       
        break;

    case 'DELETE':
        // Eliminar un post existente (e.g., DELETE /users/1)
        if (preg_match('/\/posts\/(\d+)/', $request, $matches)) {
            $postId = $matches[1];
            echo $postController->deletePost($postId);
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
