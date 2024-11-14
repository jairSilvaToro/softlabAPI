<?php

namespace App\Middlewares;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Exception;

function isAuthenticated() {
    $headers = apache_request_headers();
    if (isset($headers['Authorization'])) {
        $jwt = str_replace('Bearer ', '', $headers['Authorization']);

        try {
            $secret = $_ENV['JWT_SECRET'];
            $decoded = JWT::decode($jwt, new Key($secret, 'HS256'));

            return $decoded;
        } catch (Exception $e) {
            echo json_encode(["message" => "Token inválido"]);
            http_response_code(401);
            exit();
        }
    } else {
        echo json_encode(["message" => "No se proporcionó token"]);
        http_response_code(401);
        exit();
    }
}
