<?php
    namespace App\Controllers;

    use App\Models\Representante;
    use function App\Middlewares\isAuthenticated;
    require_once __DIR__ . '../../Middlewares/AuthMiddleware.php';

    class RepresentantesController {
        private $representanteModel;
        private $decoded;

        public function __construct($database) {
            $this->representanteModel = new Representante($database);
        }      

        public function obtenerRepresentantePorCedula($cedula){
            isAuthenticated();
            return $this->representanteModel->obtenerRepresentantePorCedula($cedula);
        }

        public function obtenerRepresentantes(int $page, int $limit){
            isAuthenticated();
            $offset = ($page - 1) * $limit;
            $representantes =  $this->representanteModel->obtenerRepresentantes($limit, $offset);
            $totalRepresentantes = $representantes? count($representantes) : 0;
            return [
                 "data" => $representantes,
                 "pagination" => [
                     "current_page" => $page,
                     "per_page" => $limit,
                     "total" => $totalRepresentantes,
                     "total_pages" => ceil($totalRepresentantes / $limit)
                ]
            ];
        }

        public function insertarRepresentante() {
            header("Content-Type: application/json");
            $this->decoded = isAuthenticated();
            if($this->decoded->rol === "admin"){
                $data = json_decode(file_get_contents("php://input"));
                if(empty($data->cedula_reprelegal) || empty($data->nombre) || empty($data->telefono)){
                    http_response_code(400);                     
                    return  json_encode(["error" => 'el representante no pudo ser creado']);
                }
                return $this->representanteModel->insertarRepresentante($data);
            }else{
                http_response_code(403);
                echo json_encode(["error" => "Acceso denegado"]);
                exit;
            }
        }

        public function actualizarRepresentante(){
            header("Content-Type: application/json");
            $this->decoded = isAuthenticated();
            if($this->decoded->rol === "admin"){
                $data = json_decode(file_get_contents("php://input"));
                if(empty($data->cedula_reprelegal) || empty($data->nombre) || empty($data->telefono)){
                    http_response_code(400);                     
                    return  json_encode(["error" => 'el representante no pudo ser creado']);
                }
                return $this->representanteModel->actualizarRepresentante($data);
            }else{
                http_response_code(403);
                echo json_encode(["error" => "Acceso denegado"]);
                exit;
            }            
        }
    }
