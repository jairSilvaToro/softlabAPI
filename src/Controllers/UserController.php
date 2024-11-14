<?php
    namespace App\Controllers;

    use App\Models\User;
    use function App\Middlewares\isAuthenticated;
    require_once __DIR__ . '../../Middlewares/AuthMiddleware.php';

    class UserController {
        private $userModel;

        public function __construct($database) {
            $this->userModel = new User($database);
        }

        public function getUsers() {
            isAuthenticated();
            return $this->userModel->getAllUsers();
        }

        public function getUserById($userId){
            isAuthenticated();
            return $this->userModel->getUserById($userId);
        }

        public function createUser() {
            // isAuthenticated();
            $data = json_decode(file_get_contents("php://input"));
            return $this->userModel->createUser($data->name, $data->email, $data->username, $data->password);
        }

        public function deleteUser($userId){
            isAuthenticated();
            return $this->userModel->deleteUser($userId);
        }

        public function updateUser(){
            isAuthenticated();
            $data = json_decode(file_get_contents("php://input"));
            return $this->userModel->updateUser($data->name, $data->email, $data->username, $data->id);
        }
    }
