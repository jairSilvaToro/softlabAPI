<?php
    namespace App\Controllers;

    use App\Models\Empresa;
    use function App\Middlewares\isAuthenticated;
    require_once __DIR__ . '../../Middlewares/AuthMiddleware.php';

    class EmpresasController {
        private $empresaModel;
        private $decoded;

        public function __construct($database) {
            $this->empresaModel = new Empresa($database);
        }      

        public function obtenerEmpresaPorCodigo($codigo){
            isAuthenticated();
            return $this->empresaModel->obtenerEmpresaPorCodigo($codigo);
        }

        public function obtenerEmpresas(int $page, int $limit){
            isAuthenticated();
            $offset = ($page - 1) * $limit;
            $empresas = $this->empresaModel->obtenerEmpresas($limit, $offset);
            $totalEmpresas = $empresas? count($empresas) : 0;
            return [
                 "data" => $empresas,
                 "pagination" => [
                     "current_page" => $page,
                     "per_page" => $limit,
                     "total" => $totalEmpresas,
                     "total_pages" => ceil($totalEmpresas / $limit)
                ]
            ];
        }

        public function crearEmpresa() {
            header("Content-Type: application/json");
            $this->decoded = isAuthenticated();
            if($this->decoded->rol === "admin"){
                $data = json_decode(file_get_contents("php://input"));
                if(empty($data->cod_empresa) || empty($data->nombre_empresa) || empty($data->cedula_reprelegal)){   
                    http_response_code(400);                  
                    return  json_encode(["error" => 'La empresa no pudo ser creada']);
                }
                return $this->empresaModel->crearEmpresa($data);
            }else{
                http_response_code(403);
                echo json_encode(["error" => "Acceso denegado"]);
                exit;
            }
        }     

        public function actualizarEmpresa(){
            header("Content-Type: application/json");
            $this->decoded = isAuthenticated();
            if($this->decoded->rol === "admin"){
                $data = json_decode(file_get_contents("php://input"));
                if(empty($data->cod_empresa) || empty($data->nombre_empresa) || empty($data->cedula_reprelegal)){   
                    http_response_code(400);                  
                    return  json_encode(["error" => 'La empresa no pudo ser actualizada']);
                }
                return $this->empresaModel->actualizarEmpresa($data);
            }else{
                http_response_code(403);
                echo json_encode(["error" => "Acceso denegado"]);
                exit;
            }
        }
    }
