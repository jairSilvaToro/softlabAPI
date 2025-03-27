<?php

// Incluimos el archivo de autoload de Composer para cargar automáticamente las dependencias
require_once '../vendor/autoload.php';

// Establecemos el encabezado de la respuesta como JSON
header("Content-Type: application/json");

// Obtenemos la URI de la solicitud y la parseamos para extraer la ruta
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// Usamos una estructura switch para manejar las rutas de la aplicación
switch (true) {
    // Caso para rutas relacionadas con usuarios
    case $uri === '/roles' || preg_match('/\/roles\/(\d+)/', $uri) || preg_match('/\/roles\/paginate/', $uri):
        // Incluimos el archivo de rutas de usuarios
        require '../src/Routes/Roles.php';
        break;
    
    // Caso para rutas relacionadas con autenticación (login)
    case $uri === '/login' || preg_match('/\/login\/(\d+)/', $uri):
        // Incluimos el archivo de rutas de autenticación
        require '../src/Routes/Authentication.php';
        break;
    
    // Caso para rutas relacionadas con empleado
    case $uri === '/empleados' || preg_match('/\/empleados\/(\d+)/', $uri) || preg_match('/\/empleados\/paginate/', $uri):
        // Incluimos el archivo de rutas de empleados
        require '../src/Routes/Empleados.php';
        break;

    // Caso para rutas relacionadas con representantes
    case $uri === '/representantes' || preg_match('/\/representantes\/(\d+)/', $uri) || preg_match('/\/representantes\/paginate/', $uri):
        // Incluimos el archivo de rutas de representantes
        require '../src/Routes/Representantes.php';
        break;

        // Caso para rutas relacionadas con empresas
    case $uri === '/empresas' || preg_match('/\/empresas\/(\w+)/', $uri) || preg_match('/\/empresas\/paginate/', $uri):        
        // Incluimos el archivo de rutas de representantes
        require '../src/Routes/Empresas.php';
        break;

    // Caso por defecto para rutas no encontradas
    default:
        // Establecemos el código de respuesta HTTP como 404 (No encontrado)
        http_response_code(404);       
        // Enviamos una respuesta JSON indicando que la ruta no fue encontrada
        echo json_encode(["message" => "Not Found"]);
}