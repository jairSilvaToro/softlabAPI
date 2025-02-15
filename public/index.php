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
    case $uri === '/users' || preg_match('/\/users\/(\d+)/', $uri) || preg_match('/\/users\/paginate/', $uri):
        // Incluimos el archivo de rutas de usuarios
        require '../src/Routes/Users.php';
        break;
    
    // Caso para rutas relacionadas con autenticación (login)
    case $uri === '/login' || preg_match('/\/login\/(\d+)/', $uri):
        // Incluimos el archivo de rutas de autenticación
        require '../src/Routes/Authentication.php';
        break;
    
    // Caso para rutas relacionadas con publicaciones (posts)
    case $uri === '/posts' || preg_match('/\/posts\/(\d+)/', $uri) || preg_match('/\/posts\/user\/(\d+)/', $uri) || preg_match('/\/posts\/paginate/', $uri):
        // Incluimos el archivo de rutas de publicaciones
        require '../src/Routes/Posts.php';
        break;

    // Caso para rutas relacionadas con comentarios
    case $uri === '/comments' || preg_match('/\/comments\/(\d+)/', $uri) || preg_match('/\/comments\/post\/(\d+)/', $uri) || preg_match('/\/comments\/paginate/', $uri):
        // Incluimos el archivo de rutas de comentarios
        require '../src/Routes/Comments.php';
        break;

    // Caso por defecto para rutas no encontradas
    default:
        // Establecemos el código de respuesta HTTP como 404 (No encontrado)
        http_response_code(404);       
        // Enviamos una respuesta JSON indicando que la ruta no fue encontrada
        echo json_encode(["message" => "Not Found"]);
}