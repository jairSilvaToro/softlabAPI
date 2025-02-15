<?php
// Definimos el espacio de nombres para el controlador
namespace App\Controllers;

// Importamos el modelo de usuario
use App\Models\User;

// Definimos la clase AuthenticationController que manejará la autenticación de usuarios
class AuthenticationController {
    // Declaramos una propiedad privada para almacenar la instancia del modelo de usuario
    private $userModel;

    // Constructor de la clase que recibe una conexión a la base de datos como parámetro
    public function __construct($database) {
        // Inicializamos el modelo de usuario con la conexión a la base de datos
        $this->userModel = new User($database);
    }

    // Método para manejar el proceso de inicio de sesión
    public function login(){
        // Obtenemos los datos enviados en el cuerpo de la solicitud en formato JSON y los decodificamos
        $data = json_decode(file_get_contents("php://input"));

        // Verificamos si se han proporcionado tanto el nombre de usuario como la contraseña
        if(isset($data->username, $data->password)){
            // Intentamos autenticar al usuario utilizando el modelo de usuario
            $result = $this->userModel->authenticate($data->username, $data->password);

            // Si la autenticación es exitosa, devolvemos un mensaje de éxito y un token
            if ($result) {
                echo json_encode(["message"=> "Autenticacion exitosa","token" => $result['token']]);
            } else {
                // Si las credenciales son incorrectas, devolvemos un mensaje de error
                echo json_encode(["message" => "Credenciales incorrectas"]);
            }

        }else{
            // Si no se proporcionaron todos los datos necesarios, devolvemos un mensaje de error
            echo json_encode(["message" => "Datos incompletos"]);
        }
    }
}