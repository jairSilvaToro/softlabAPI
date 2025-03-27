<?php
    namespace App\Controllers;

    use App\Models\Empleado;
    use function App\Middlewares\isAuthenticated;
    require_once __DIR__ . '../../Middlewares/AuthMiddleware.php';

    class EmpleadosController {
        private $empleadoModel;
        private $decoded;

        public function __construct($database) {
            $this->empleadoModel = new Empleado($database);
        }      

        public function obtenerEmpleadoPorCedula(string $cedula){
            
            isAuthenticated();
            return $this->empleadoModel->obtenerEmpleadoPorCedula($cedula);
        }

        public function obtenerEmpleados(int $page, int $limit){
            isAuthenticated();
            $offset = ($page - 1) * $limit;
            $users = $this->empleadoModel->obtenerEmpleados($limit, $offset);
            $totalEmpleados = $users? count($users) : 0;
            return [
                "data" => $users,
                "pagination" => [
                    "current_page" => $page,
                    "per_page" => $limit,
                    "total" => $totalEmpleados,
                    "total_pages" => ceil($totalEmpleados / $limit)
                ]
            ];
        }

        public function crearEmpleado() {
            $this->decoded = isAuthenticated();
            if($this->decoded->rol === "admin"){
                $data = json_decode(file_get_contents("php://input"));
                if(empty($data->cedula_empleado) || empty($data->nombre) || empty($data->apellido)){                     
                    return  json_encode(["error" => 'el empleado no pudo ser creado']);
                }
                return $this->empleadoModel->insertarEmpleado($data);
            }else{
                http_response_code(403);
                echo json_encode(["error" => "Acceso denegado"]);
                exit;
            }
        }

        public function eliminarEmpleado(string $cedula){
            $this->decoded = isAuthenticated();
            if($this->decoded->rol === "admin"){
                return $this->empleadoModel->eliminarEmpleado($cedula);
            }else{
                http_response_code(403);
                echo json_encode(["error" => "Acceso denegado"]);
                exit;
            }           
        }

        public function actualizarEmpleado(){
            $this->decoded = isAuthenticated();
            if($this->decoded->rol  === "admin"){
                $data = json_decode(file_get_contents("php://input"));
                if(empty($data->cedula_empleado) || empty($data->nombre) || empty($data->apellido)){                                         
                    return  json_encode(["error" => 'el empleado no pudo ser actualizado']);
                }               
                $respuesta =  $this->empleadoModel->actualizarEmpleado($data);
                return $respuesta;
            }else{
                http_response_code(403);
                echo json_encode(["error" => "Acceso denegado"]);
                exit;
            } 
            
        }        
    }
