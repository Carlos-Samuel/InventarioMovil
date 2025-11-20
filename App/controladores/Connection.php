<?php

    require_once realpath(__DIR__ . '/../vendor/autoload.php');

    $dotenv = Dotenv\Dotenv::createImmutable(dirname(__DIR__));
    $dotenv->load();

    class Connection {
        private static $instance = null;
        private $connection;

        private function __construct() {
            $host     = $_ENV['DB_HOST'] ?? 'localhost';
            $username = $_ENV['DB_USER'] ?? 'root';
            $password = $_ENV['DB_PASS'] ?? '';
            $database = $_ENV['DB_NAME'] ?? '';

            $this->connection = new mysqli($host, $username, $password, $database);

            if ($this->connection->connect_error) {
                die("Error de conexión: " . $this->connection->connect_error);
            }
            
            // Configurar la codificación de caracteres a UTF-8
            $this->connection->set_charset("utf8");
        }

        public static function getInstance() {
            if (self::$instance == null) {
                self::$instance = new Connection();
            }
            return self::$instance;
        }

        public function getConnection() {
            return $this->connection;
        }
    }

?>