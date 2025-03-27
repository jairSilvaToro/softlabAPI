<?php
namespace App\Routes;

use App\Config\Database;
use App\Controllers\EmpleadosController;

// Obtener el método de la solicitud y la URL
$method = $_SERVER['REQUEST_METHOD'];
$request = $_SERVER['REQUEST_URI'];

$database = new Database();
$empleadosController = new EmpleadosController($database);

switch ($method) {
    case 'GET':
        // empleado por cedula

        if (preg_match('/\/empleados\/(\d+)/', $request, $matches)) {
                // Ruta para obtener un empleado por ID (e.g., GET /empleado/1)
                $cedula = $matches[1];
                echo json_encode($empleadosController->obtenerEmpleadoPorCedula($cedula));
        }else{
                // Ruta para obtener todos los empleados (e.g., GET /empleados)
                $queryParams = $_GET;
                    $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
                    $limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 10;          
                echo json_encode($empleadosController->obtenerEmpleados($page,$limit));
        }        
        break;

    case 'POST':
        // Crear un nuevo empleado (e.g., POST /empleado)
        $resp = $empleadosController->crearEmpleado();
        $decode = json_decode($resp);
        if(isset($decode->error))
        {
            echo json_encode(['error' =>$decode->error]);
        }else{
            echo $resp;
        }
        break;

    case 'PUT':
        // Actualizar un empleado existente (e.g., PUT /users/1)
        if (preg_match('#/empleado#', $request, $matches)){
            echo $empleadosController->actualizarEmpleado();             
        } else {
            http_response_code(404);
            echo json_encode(['message' => 'Ruta de actualizar no encontrada']);
        }
              
        break;

    case 'DELETE':
        // Eliminar un comentario existente (e.g., DELETE /users/1)
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
