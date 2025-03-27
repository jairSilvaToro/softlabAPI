<?php
namespace App\Routes;

use App\Config\Database;
use App\Controllers\EmpresasController;

// Obtener el método de la solicitud y la URL
$method = $_SERVER['REQUEST_METHOD'];
$request = $_SERVER['REQUEST_URI'];

$database = new Database();
$empresasController = new EmpresasController($database);

switch ($method) {
    case 'GET':
        // empresa por cedula

        if (preg_match('/\/empresas\/(\w+)/', $request, $matches)) {
                // Ruta para obtener una empresa por ID (e.g., GET /empresas/1)
                $codigo = $matches[1];
                echo json_encode($empresasController->obtenerEmpresaPorCodigo($codigo));
        }else{
                // Ruta para obtener todos los empresa (e.g., GET /empresas)
                $queryParams = $_GET;
                    $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
                    $limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 10;
                echo json_encode($empresasController->obtenerEmpresas($page,$limit));
        }        
        break;

    case 'POST':
        // Crear un nuevo empresa (e.g., POST /empresas)
        $resp = $empresasController->crearEmpresa();
        $decode = json_decode($resp);
        if(isset($decode->error))
        {
            echo json_encode(['error' => 'la empresa no pudo ser creada']);
        }else{
            echo $resp;
        }
        break;

    case 'PUT':
        // Actualizar una empresa existente (e.g., PUT /empresas/1)
        if (preg_match('#/empresa#', $request, $matches)){
            echo $empresasController->actualizarEmpresa();             
        } else {
            http_response_code(404);
            echo json_encode(['message' => 'Ruta de actualizar no encontrada']);
        }
              
        break;

    case 'DELETE':
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
