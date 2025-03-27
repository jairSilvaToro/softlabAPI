<?php
    namespace App\Controllers;

    use App\Models\Rol;
    use function App\Middlewares\isAuthenticated;
    require_once __DIR__ . '../../Middlewares/AuthMiddleware.php';

    class RolesController {
        private $rolModel;

        public function __construct($database) {
            $this->rolModel = new Rol($database);
        }
        
        public function obtenerRoles(int $page, int $limit) {
            isAuthenticated();
            $offset = ($page - 1) * $limit;
            $users = $this->rolModel->obtenerRoles($limit, $offset);
            $totalUsers = $users? count($users) : 0;

            return [
                "data" => $users,
                "pagination" => [
                    "current_page" => $page,
                    "per_page" => $limit,
                    "total" => $totalUsers,
                    "total_pages" => ceil($totalUsers / $limit)
                ]
            ];
        }

        public function crearRol() {
            isAuthenticated();
            $data = json_decode(file_get_contents("php://input"));  
            
            if($data->rol != ""){
                return $this->rolModel->crearRol($data->rol);
            }else{
                http_response_code(400); // Establece el código de estado HTTP 400
                return json_encode(["error" => "No se permite el campo 'rol' vacio"]);
            }
        }

        public function eliminarRol($cod){
            isAuthenticated();
            $resultado = $this->rolModel->eliminarRol($cod);
            if($resultado === 0){
                http_response_code(400);
                return json_encode(["error" => "No se pudo eliminar el rol"]);
            }else{
                return json_encode(["message" => "rol eliminado"]);
            }            
        }

        public function actualizarRol(){
            isAuthenticated();
            $data = json_decode(file_get_contents("php://input"));
            return $this->rolModel->actualizarRol($data->rol, $data->cod);
        }
    }
