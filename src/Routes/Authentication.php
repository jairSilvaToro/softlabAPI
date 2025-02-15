<?php
namespace App\Routes;

use App\Config\Database;
use App\Controllers\AuthenticationController;

// Obtener el método de la solicitud y la URL
$method = $_SERVER['REQUEST_METHOD'];
$request = $_SERVER['REQUEST_URI'];

$database = new Database();
$authController = new AuthenticationController($database);

if($method === 'POST'){
    $authController->login();
}else{
    return json_encode(
        ["message" => "Ruta no encontrada"]
    );
}