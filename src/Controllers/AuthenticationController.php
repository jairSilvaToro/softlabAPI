<?php
    namespace App\Controllers;
    use App\Models\User;

    class AuthenticationController {
        private $userModel;

        public function __construct($database) {
            $this->userModel = new User($database);
        }

        public function login(){
            $data = json_decode(file_get_contents("php://input"));
            if(isset($data->username, $data->password)){
                $result = $this->userModel->authenticate($data->username, $data->password);

                if ($result) {
                    echo json_encode(["token" => $result['token']]);
                } else {
                    echo json_encode(["message" => "Credenciales incorrectas"]);
                }

            }else{
                echo json_encode(["message" => "Datos incompletos"]);
            }
        }
    }
    