<?php
namespace App\Routes;

use App\Config\Database;
use App\Controllers\RepresentantesController;

// Obtener el método de la solicitud y la URL
$method = $_SERVER['REQUEST_METHOD'];
$request = $_SERVER['REQUEST_URI'];

$database = new Database();
$representantesController = new RepresentantesController($database);

switch ($method) {
    case 'GET':
        // representante por cedula

        if (preg_match('/\/representantes\/(\d+)/', $request, $matches)) {
                // Ruta para obtener un representante por ID (e.g., GET /representante/1)
                $cedula = $matches[1];                
                echo json_encode($representantesController->obtenerRepresentantePorCedula($cedula));
        }else{
                // Ruta para obtener todos los representante (e.g., GET /representantes)
                $queryParams = $_GET;
                    $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
                    $limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 10;
                    // echo json_encode($commentsController->getAllPosts($page,$limit));
                echo json_encode($representantesController->obtenerRepresentantes($page,$limit));
        }        
        break;
    case 'POST':
        // Crear un nuevo representante (e.g., POST /representantes)
        $resp = $representantesController->insertarRepresentante();
        $decode = json_decode($resp);
        if(isset($decode->error))
        {
            echo json_encode(['error' => 'el representante no pudo ser creado']);
        }else{
            echo $resp;
        }
        break;

    case 'PUT':
        // Actualizar un representante existente (e.g., PUT /representante)
        if (preg_match('#/representantes#', $request, $matches)){
            echo $representantesController->actualizarRepresentante();             
        } else {
            http_response_code(404);
            echo json_encode(['message' => 'Ruta de actualizar no encontrada']);
        }              
        break;

    case 'DELETE':
        // Eliminar un representante existente (e.g., DELETE /users/1)
        if (preg_match('/\/empleado\/(\d+)/', $request, $matches)) {
            $cedula = $matches[1];
            echo $empleadosController->eliminarEmpleado($cedula);
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
