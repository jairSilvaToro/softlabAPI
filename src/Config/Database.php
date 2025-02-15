<?php
// Definimos el espacio de nombres para la clase de configuración de la base de datos
namespace App\Config;

// Importamos las clases necesarias para trabajar con PDO y manejar excepciones
use PDO;
use PDOException;
use Dotenv\Dotenv; // Importamos Dotenv para manejar variables de entorno

// Cargamos las variables de entorno desde el archivo .env ubicado en la raíz del proyecto
$dotenv = Dotenv::createImmutable(__DIR__ . '/../../');
$dotenv->load();

// Definimos la clase Database que manejará la conexión a la base de datos
class Database {
    // Propiedades privadas para almacenar los detalles de la conexión
    private $host;       // Host de la base de datos
    private $db_name;    // Nombre de la base de datos
    private $username;   // Nombre de usuario de la base de datos
    private $password;   // Contraseña de la base de datos
    private $conn;       // Objeto de conexión PDO

    // Constructor de la clase
    public function __construct() {
        // Asignamos los valores de las variables de entorno o usamos valores predeterminados
        $this->host = $_ENV['DB_HOST'] ?? 'localhost';         // Host de la base de datos (o 'localhost' por defecto)
        $this->db_name = $_ENV['DB_NAME'] ?? 'api-post';       // Nombre de la base de datos (o 'api-post' por defecto)
        $this->username = $_ENV['DB_USERNAME'] ?? 'root';     // Usuario de la base de datos (o 'root' por defecto)
        $this->password = $_ENV['DB_PASSWORD'] ?? '';          // Contraseña de la base de datos (o vacío por defecto)
    }

    // Método para establecer la conexión a la base de datos
    public function connect() {
        // Inicializamos la conexión como nula
        $this->conn = null;

        try {
            // Intentamos crear una nueva instancia de PDO para conectarnos a la base de datos
            $this->conn = new PDO(
                "mysql:host={$this->host};dbname={$this->db_name}", // DSN (Data Source Name)
                $this->username, // Nombre de usuario
                $this->password  // Contraseña
            );

            // Configuramos PDO para que lance excepciones en caso de errores
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            // Si ocurre un error durante la conexión, mostramos un mensaje de error
            echo "Connection error: " . $e->getMessage();
        }

        // Devolvemos el objeto de conexión PDO
        return $this->conn;
    }
}