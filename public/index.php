<?php

require_once '../vendor/autoload.php';

header("Content-Type: application/json");

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

switch (true) {
    case $uri === '/users' || preg_match('/\/users\/(\d+)/', $uri):
        require '../src/Routes/Users.php';
        break;
    
    case $uri === '/login' || preg_match('/\/login\/(\d+)/', $uri):
        require '../src/Routes/Authentication.php';
        break;

    default:
        http_response_code(404);
        echo json_encode(["message" => "Not Found"]);
        break;
}
