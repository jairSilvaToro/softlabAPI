<?php
// Este script verifica si JWT_SECRET está definido y, si no, lo genera y lo agrega al .env

$envPath = __DIR__ . '/../.env';
$secretKey = bin2hex(random_bytes(32)); // Genera una clave de 64 caracteres hexadecimal

if (file_exists($envPath)) {
    $envContent = file_get_contents($envPath);

    // Si no existe la clave JWT_SECRET, añadirla
    if (!str_contains($envContent, 'JWT_SECRET=')) {
        file_put_contents($envPath, "\nJWT_SECRET=$secretKey", FILE_APPEND | LOCK_EX);
        echo "JWT_SECRET generado y añadido al .env\n";
    } else {
        echo "JWT_SECRET ya existe en el .env\n";
    }
} else {
    echo "Archivo .env no encontrado.\n";
}
