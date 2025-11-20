<?php

    require_once realpath(__DIR__ . '/../vendor/autoload.php');

    $dotenv = Dotenv\Dotenv::createImmutable(dirname(__DIR__));
    $dotenv->load();

    class Connection2 {
        private static $instance = null;
        private $connection;

        private function __construct() {
            $host     = $_ENV['DB2_HOST'] ?? 'localhost';
            $username = $_ENV['DB2_USER'] ?? 'root';
            $password = $_ENV['DB2_PASS'] ?? '';
            $database = $_ENV['DB2_NAME'] ?? '';

            $this->connection = new mysqli($host, $username, $password, $database);

            if ($this->connection->connect_error) {
                die("Error de conexión: " . $this->connection->connect_error);
            }

            // Configurar la codificación de caracteres a UTF-8
            $this->connection->set_charset("utf8");
        }

        public static function getInstance2() {
            if (self::$instance == null) {
                self::$instance = new Connection2();
            }
            return self::$instance;
        }

        public function getConnection2() {
            return $this->connection;
        }
    }

?>