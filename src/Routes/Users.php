<?php
namespace App\Routes;

use App\Config\Database;
use App\Controllers\UserController;

// Obtener el método de la solicitud y la URL
$method = $_SERVER['REQUEST_METHOD'];
$request = $_SERVER['REQUEST_URI'];

$database = new Database();
$userController = new UserController($database);

switch ($method) {
    case 'GET':
        // Listar todos los usuarios o uno en específico
        if (preg_match('/\/users\/(\d+)/', $request, $matches)) {
            // Ruta para obtener un usuario por ID (e.g., GET /users/1)
            $userId = $matches[1];
            echo json_encode($userController->getUserById($userId));
        } else {
            // Ruta para obtener todos los usuarios (e.g., GET /users)
            echo json_encode($userController->getUsers());
        }
        break;

    case 'POST':
        // Crear un nuevo usuario (e.g., POST /users)
        $resp = $userController->createUser();
        $decode = json_decode($resp);
        if(isset($decode->error))
        {
            echo json_encode(['error' => 'The user could not be created']);
        }else{
            echo $resp;
        }      
        break;

    case 'PUT':
        // Actualizar un usuario existente (e.g., PUT /users/1)
        $resp = $userController->updateUser();       
        break;

    case 'DELETE':
        // Eliminar un usuario existente (e.g., DELETE /users/1)
        if (preg_match('/\/users\/(\d+)/', $request, $matches)) {
            $userId = $matches[1];
            echo $userController->deleteUser($userId);
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
