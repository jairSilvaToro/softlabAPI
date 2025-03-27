<?php
namespace App\Routes;

use App\Config\Database;
use App\Controllers\RolesController;

// Obtener el método de la solicitud y la URL
$method = $_SERVER['REQUEST_METHOD'];
$request = $_SERVER['REQUEST_URI'];

$database = new Database();
$rolesController = new RolesController($database);

switch ($method) {
    case 'GET':
        // get all roles
      
        // Ruta para obtener todos los roles (e.g., GET /roles)
        $queryParams = $_GET;
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 10;
        echo json_encode($rolesController->obtenerRoles($page,$limit));
        break;

    case 'POST':
        // Crear un nuevo rol (e.g., POST /roles)
        $resp = $rolesController->crearRol();
        $decode = json_decode($resp);
        if(isset($decode->error))
        {
            echo json_encode(['error' => $decode->error]);
        }else{
            echo $resp;
        }
        break;

    // case 'PUT':
    //     // Actualizar un rol existente (e.g., PUT /users/1)
    //     echo $rolesController->updateComment();       
    //     break;

    case 'DELETE':
        // Eliminar un rol existente (e.g., DELETE /rol/1)
        if (preg_match('/\/roles\/(\d+)/', $request, $matches)) {
            $cod = $matches[1];
            echo $rolesController->eliminarRol($cod);
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
